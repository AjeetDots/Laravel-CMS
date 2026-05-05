<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContactController extends Controller {
    public function index() {
        $contacts = Contact::latest()->paginate(20);
        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * Download all contact enquiries as CSV (Excel-friendly UTF-8).
     */
    public function export(): StreamedResponse {
        $filename = 'enquiries-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['ID', 'Name', 'Email', 'Phone', 'Subject', 'Message', 'Read', 'Created at']);

            foreach (Contact::query()->orderByDesc('created_at')->cursor() as $contact) {
                $message = (string) ($contact->message ?? '');
                $message = preg_replace('/\s+/u', ' ', trim(strip_tags($message)));

                fputcsv($handle, [
                    $contact->id,
                    $contact->name,
                    $contact->email,
                    $contact->phone ?? '',
                    $contact->subject ?? '',
                    $message,
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
        return view('admin.contacts.show', compact('contact'));
    }
    public function destroy(Contact $contact) {
        $contact->delete();
        return back()->with('success', 'Message deleted.');
    }
}
