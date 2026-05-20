<?php

namespace App\Contracts\Frontend;

use App\Models\Contact;

interface ContactSubmissionServiceInterface
{
    /**
     * @param array<string, mixed> $payload
     */
    public function submit(array $payload): Contact;
}
