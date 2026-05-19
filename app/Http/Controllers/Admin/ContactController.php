<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\Concerns\AppliesAdminTableFilters;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactReplyLog;
use App\Models\MailDeliveryLog;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContactController extends Controller {
    use AppliesAdminTableFilters;

    public function index() {
        $query = Contact::query()->latest();
        $this->applyAdminSearch($query, request('q'), ['name', 'email', 'subject']);
        $this->applyAdminReadFilter($query, request('read'));
        $contacts = $query->latest()->get();
        $unreadCount = Contact::where('is_read', false)->count();

        return view('admin.contacts.index', compact('contacts', 'unreadCount'));
    }

    /**
     * Download all contact enquiries as CSV (Excel-friendly UTF-8).
     */
    public function export(): StreamedResponse {
        $filename = 'enquiries-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['Sr.', 'Name', 'Email', 'Phone', 'Subject', 'Message', 'Client Mail Status', 'Admin Mail Status', 'Read', 'Created at']);

            $serial = 0;
            foreach (Contact::query()->orderByDesc('created_at')->cursor() as $contact) {
                $serial++;
                $message = (string) ($contact->message ?? '');
                $message = preg_replace('/\s+/u', ' ', trim(strip_tags($message)));

                fputcsv($handle, [
                    $serial,
                    $contact->name,
                    $contact->email,
                    $contact->phone ?? '',
                    $contact->subject ?? '',
                    $message,
                    $contact->client_mail_status ?? '',
                    $contact->admin_mail_status ?? '',
                    $contact->is_read ? 'Yes' : 'No',
                    $contact->created_at?->format('Y-m-d H:i:s') ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
    public function show(Contact $contact) {
        $contact->update(['is_read' => true]);
        $mailLogs = MailDeliveryLog::query()
            ->where('contact_id', $contact->id)
            ->latest()
            ->limit(20)
            ->get();

        $replyLogs = ContactReplyLog::query()
            ->where('contact_id', $contact->id)
            ->with('user:id,name')
            ->latest()
            ->limit(30)
            ->get();

        return view('admin.contacts.show', compact('contact', 'mailLogs', 'replyLogs'));
    }

    public function reply(Contact $contact) {
        $data = request()->validate([
            'reply_method' => 'required|string|in:email,phone,other',
            'reply_message' => 'required|string|max:2000',
        ]);

        $contact->update([
            'reply_method' => $data['reply_method'],
            'reply_message' => $data['reply_message'],
            'replied_at' => now(),
        ]);

        ContactReplyLog::create([
            'contact_id' => $contact->id,
            'user_id' => Auth::id(),
            'reply_method' => $data['reply_method'],
            'reply_message' => $data['reply_message'],
        ]);

        return back()->with('success', 'Reply action recorded successfully.');
    }

    public function destroy(Contact $contact) {
        $contact->delete();
        return back()->with('success', 'The enquiry has been removed.');
    }
}
