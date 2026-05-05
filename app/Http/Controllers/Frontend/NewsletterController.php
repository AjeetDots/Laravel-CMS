<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\NewsletterSubscribeRequest;
use App\Models\EmailTemplate;
use App\Models\NewsletterSubscriber;
use App\Models\Setting;
use App\Services\EmailTemplateSender;

class NewsletterController extends Controller {
    public function subscribe(NewsletterSubscribeRequest $request) {
        $exists = NewsletterSubscriber::where('email', $request->email)->exists();
        if ($exists) {
            return redirect(url()->previous() . '#footer-newsletter')
                ->withErrors(['email' => 'You have already subscribed with this email address.'])
                ->withInput();
        }

        NewsletterSubscriber::create([
            'email' => $request->email,
            'name'  => $request->name ?? null,
        ]);
        $displayName = $request->name
            ?: (strstr($request->email, '@', true) ?: $request->email);
        try {
            $vars = [
                'email' => $request->email,
                'user_name' => $displayName,
                'name' => $displayName,
                'date' => now()->format('d M Y, H:i'),
            ];

            EmailTemplateSender::send(EmailTemplate::TYPE_NEWSLETTER_CLIENT, $request->email, $vars);

            $adminEmail = Setting::get('admin_notification_email') ?: Setting::get('site_email');
            if ($adminEmail) {
                EmailTemplateSender::send(EmailTemplate::TYPE_NEWSLETTER_ADMIN, (string) $adminEmail, $vars);
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('newsletter_success', 'Thank you for subscribing!');
    }
}
