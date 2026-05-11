<?php

namespace App\Jobs;

use App\Models\Contact;
use App\Models\EmailTemplate;
use App\Models\Setting;
use App\Services\EmailTemplateSender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendContactSubmissionEmailsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    /**
     * @var array<int, int>
     */
    public array $backoff = [30, 120];

    public function __construct(
        public int $contactId
    ) {
    }

    public function handle(EmailTemplateSender $emailTemplateSender): void
    {
        $contact = Contact::query()->find($this->contactId);
        if (! $contact) {
            return;
        }

        $variables = $this->contactVariables($contact);

        $clientDelivery = $emailTemplateSender->sendWithMeta(
            EmailTemplate::TYPE_CONTACT_CLIENT,
            $contact->email,
            $variables,
            null,
            ['contact_id' => $contact->id]
        );

        $adminDelivery = $this->sendAdminNotification($emailTemplateSender, $contact, $variables);

        $contact->update([
            'client_mail_status' => $clientDelivery['status'] ?? null,
            'client_mail_reason' => $clientDelivery['reason'] ?? null,
            'admin_mail_status' => $adminDelivery['status'] ?? null,
            'admin_mail_reason' => $adminDelivery['reason'] ?? null,
        ]);
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

    /**
     * @param array<string, string> $variables
     * @return array{status:string,reason:string}
     */
    private function sendAdminNotification(
        EmailTemplateSender $emailTemplateSender,
        Contact $contact,
        array $variables
    ): array {
        $adminEmail = Setting::get('admin_notification_email') ?: Setting::get('site_email');

        if (! $adminEmail) {
            return [
                'status' => 'skipped',
                'reason' => 'Admin notification email and site email are both empty in settings',
            ];
        }

        return $emailTemplateSender->sendWithMeta(
            EmailTemplate::TYPE_CONTACT_ADMIN,
            (string) $adminEmail,
            $variables,
            function ($message) use ($contact): void {
                $message->replyTo($contact->email, $contact->name);
            },
            ['contact_id' => $contact->id]
        );
    }
}
