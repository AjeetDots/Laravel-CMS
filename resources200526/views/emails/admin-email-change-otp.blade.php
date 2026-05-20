<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email change verification</title>
</head>
<body style="font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif; line-height: 1.5; color: #1e293b; max-width: 560px; margin: 0 auto; padding: 24px;">
    <p>Hello {{ $user->name }},</p>
    <p>Someone requested to change the <strong>login email</strong> for your admin account on <strong>{{ config('app.name') }}</strong>.</p>
    <p><strong>New email after confirmation:</strong> {{ $newEmail }}</p>
    <p style="font-size: 1.5rem; letter-spacing: 0.25em; font-weight: 700; margin: 20px 0;">{{ $otpPlain }}</p>
    <p>Enter this 6-digit code on the Account page to finish the change. The code expires in <strong>{{ $expiresInMinutes }} minutes</strong> and can be used only once.</p>
    <p>If you did not request this, ignore this message and your login email will stay <strong>{{ $user->email }}</strong> (this message was sent to that address).</p>
    <p style="color: #64748b; font-size: 0.875rem; margin-top: 32px;">This is an automated message.</p>
</body>
</html>
