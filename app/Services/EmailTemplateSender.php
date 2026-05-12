<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\MailDeliveryLog;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;

class EmailTemplateSender
{
    /**
     * Send active template and return detailed delivery meta.
     *
     * @return array{delivered:bool,status:string,reason:string}
     */
    public function sendWithMeta(
        string $templateType,
        ?string $toEmail,
        array $vars,
        ?callable $messageModifier = null,
        array $context = []
    ): array
    {
        if (! $toEmail || ! filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            $reason = 'Invalid or missing recipient email';
            $this->writeLog(
                null,
                $templateType,
                $toEmail,
                null,
                'skipped',
                null,
                $reason,
                $context
            );
            return ['delivered' => false, 'status' => 'skipped', 'reason' => $reason];
        }

        $tpl = EmailTemplate::query()
            ->where('template_type', $templateType)
            ->where('is_active', true)
            ->orderBy('id')
            ->first();

        if (! $tpl) {
            $reason = 'No active template found for this template type';
            $this->writeLog(
                null,
                $templateType,
                $toEmail,
                null,
                'skipped',
                null,
                $reason,
                $context
            );
            return ['delivered' => false, 'status' => 'skipped', 'reason' => $reason];
        }

        $vars = array_merge([
            'site_name' => trim((string) Setting::get('site_name', '')),
        ], $vars);

        $subject = $tpl->renderSubject($vars);
        $body = $tpl->renderBody($vars);

        try {
            Mail::html($body, function ($message) use ($toEmail, $subject, $messageModifier) {
                $message->to($toEmail)->subject($subject);
                if ($messageModifier) {
                    $messageModifier($message);
                }
            });

            $this->writeLog(
                $tpl->id,
                $templateType,
                $toEmail,
                $subject,
                'sent',
                'SMTP accepted message for delivery',
                null,
                $context
            );
            return ['delivered' => true, 'status' => 'sent', 'reason' => 'Sent successfully'];
        } catch (\Throwable $e) {
            $reason = $e->getMessage();
            $this->writeLog(
                $tpl->id,
                $templateType,
                $toEmail,
                $subject,
                'failed',
                null,
                $reason,
                $context
            );
            return ['delivered' => false, 'status' => 'failed', 'reason' => $reason];
        }
    }

    public function send(
        string $templateType,
        ?string $toEmail,
        array $vars,
        ?callable $messageModifier = null,
        array $context = []
    ): bool
    {
        return $this->sendWithMeta($templateType, $toEmail, $vars, $messageModifier, $context)['delivered'];
    }

    private function writeLog(
        ?int $templateId,
        ?string $templateType,
        ?string $toEmail,
        ?string $subject,
        string $status,
        ?string $smtpResponse,
        ?string $errorMessage,
        array $context = []
    ): void {
        try {
            MailDeliveryLog::create([
                'email_template_id' => $templateId,
                'contact_id' => $context['contact_id'] ?? null,
                'template_type' => $templateType,
                'to_email' => $toEmail,
                'subject' => $subject,
                'status' => $status,
                'smtp_response' => $smtpResponse,
                'error_message' => $errorMessage,
                'sent_at' => $status === 'sent' ? now() : null,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
