<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\StoreContactRequest;
use App\Models\Contact;
use App\Models\EmailTemplate;
use App\Models\Setting;
use App\Services\EmailTemplateSender;

class ContactController extends Controller {
    public function index() {
        return view('frontend.contact');
    }
    public function store(StoreContactRequest $request) {
        $contact = Contact::create($request->validated());
        $vars = self::contactVariables($contact);

        $clientDelivery = EmailTemplateSender::sendWithMeta(
            EmailTemplate::TYPE_CONTACT_CLIENT,
            $contact->email,
            $vars,
            null,
            ['contact_id' => $contact->id]
        );

        $adminEmail = Setting::get('admin_notification_email') ?: Setting::get('site_email');
        if ($adminEmail) {
            $adminDelivery = EmailTemplateSender::sendWithMeta(
                EmailTemplate::TYPE_CONTACT_ADMIN,
                (string) $adminEmail,
                $vars,
                function ($message) use ($contact) {
                    $message->replyTo($contact->email, $contact->name);
                },
                ['contact_id' => $contact->id]
            );
        } else {
            $adminDelivery = [
                'status' => 'skipped',
                'reason' => 'Admin notification email and site email are both empty in settings',
            ];
        }

        $contact->update([
            'client_mail_status' => $clientDelivery['status'] ?? null,
            'client_mail_reason' => $clientDelivery['reason'] ?? null,
            'admin_mail_status' => $adminDelivery['status'] ?? null,
            'admin_mail_reason' => $adminDelivery['reason'] ?? null,
        ]);

        return back()->with('success', 'Thank you! Your message has been sent successfully.');
    }

    /** @return array<string, string> */
    private static function contactVariables(Contact $contact): array {
        return [
            'user_name' => $contact->name,
            'name' => $contact->name,
            'email' => $contact->email,
            'phone' => $contact->phone ?? '',
            'contact_no' => $contact->phone ?? '',
            'subject' => $contact->subject ?? '',
            'message' => $contact->message,
            'msg' => $contact->message,
            'date' => now()->format('d M Y, H:i'),
        ];
    }
}
