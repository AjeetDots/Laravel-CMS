<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailDeliveryLog extends Model
{
    protected $fillable = [
        'email_template_id',
        'contact_id',
        'template_type',
        'to_email',
        'subject',
        'status',
        'smtp_response',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}

