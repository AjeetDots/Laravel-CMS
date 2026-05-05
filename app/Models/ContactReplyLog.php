<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactReplyLog extends Model
{
    protected $fillable = [
        'contact_id',
        'user_id',
        'reply_method',
        'reply_message',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

