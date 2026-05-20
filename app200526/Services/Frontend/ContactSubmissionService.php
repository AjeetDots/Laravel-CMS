<?php

namespace App\Services\Frontend;

use App\Contracts\Frontend\ContactSubmissionServiceInterface;
use App\Jobs\SendContactSubmissionEmailsJob;
use App\Models\Contact;

class ContactSubmissionService implements ContactSubmissionServiceInterface
{
    /**
     * @param array<string, mixed> $payload
     */
    public function submit(array $payload): Contact
    {
        $contact = Contact::query()->create(array_merge($payload, [
            'client_mail_status' => 'queued',
            'admin_mail_status' => 'queued',
            'client_mail_reason' => null,
            'admin_mail_reason' => null,
        ]));

        SendContactSubmissionEmailsJob::dispatch($contact->id)->afterCommit();

        return $contact;
    }
}
