<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model {
    use SoftDeletes;
    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message', 'is_read',
        'client_mail_status', 'admin_mail_status', 'client_mail_reason', 'admin_mail_reason',
        'reply_method', 'reply_message', 'replied_at',
    ];
    protected $casts = [
        'is_read' => 'boolean',
        'replied_at' => 'datetime',
    ];

    public function replyLogs()
    {
        return $this->hasMany(ContactReplyLog::class)->latest();
    }
}
