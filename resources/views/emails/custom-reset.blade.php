<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Reset</title>
</head>
<body>
    <h2>Hello Dreamer,</h2>
    <p>You requested a password reset. Click the button below to reset it:</p>
    <p>
        <a href="{{ $resetLink }}" style="background:#6366f1; color:white; padding:10px 20px; border-radius:6px; text-decoration:none;">
            Reset Password
        </a>
    </p>
    <p>If you didn’t request it, please ignore this email.</p>
</body>
</html>
