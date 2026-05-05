<?php

namespace App\Services\Frontend;

use App\Contracts\Frontend\ContactSubmissionServiceInterface;
use App\Models\Contact;
use App\Models\EmailTemplate;
use App\Models\Setting;
use App\Services\EmailTemplateSender;

class ContactSubmissionService implements ContactSubmissionServiceInterface
{
    public function __construct(
        private readonly EmailTemplateSender $emailTemplateSender
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function submit(array $payload): Contact
    {
        $contact = Contact::query()->create($payload);
        $variables = $this->contactVariables($contact);

        $clientDelivery = $this->emailTemplateSender->sendWithMeta(
            EmailTemplate::TYPE_CONTACT_CLIENT,
            $contact->email,
            $variables,
            null,
            ['contact_id' => $contact->id]
        );

        $adminDelivery = $this->sendAdminNotification($contact, $variables);

        $contact->update([
            'client_mail_status' => $clientDelivery['status'] ?? null,
            'client_mail_reason' => $clientDelivery['reason'] ?? null,
            'admin_mail_status' => $adminDelivery['status'] ?? null,
            'admin_mail_reason' => $adminDelivery['reason'] ?? null,
        ]);

        return $contact;
    }

    /**
     * @param array<string, string> $variables
     * @return array{status:string,reason:string}
     */
    private function sendAdminNotification(Contact $contact, array $variables): array
    {
        $adminEmail = Setting::get('admin_notification_email') ?: Setting::get('site_email');

        if (! $adminEmail) {
            return [
                'status' => 'skipped',
                'reason' => 'Admin notification email and site email are both empty in settings',
            ];
        }

        return $this->emailTemplateSender->sendWithMeta(
            EmailTemplate::TYPE_CONTACT_ADMIN,
            (string) $adminEmail,
            $variables,
            function ($message) use ($contact): void {
                $message->replyTo($contact->email, $contact->name);
            },
            ['contact_id' => $contact->id]
        );
    }

    /**
     * @return array<string, string>
     */
    private function contactVariables(Contact $contact): array
    {
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
