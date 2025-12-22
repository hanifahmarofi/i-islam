<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; background-color: #f3f4f6; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; border-top: 5px solid #16a34a; }
        h1 { color: #166534; text-align: center; }
        p { color: #374151; line-height: 1.5; }
        .btn { display: block; width: 200px; margin: 20px auto; text-align: center; background: #16a34a; color: white; padding: 12px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .footer { text-align: center; font-size: 12px; color: #9ca3af; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌙 i-Islam Password Reset</h1>
        <p>Hello,</p>
        <p>We received a request to reset the password for your account associated with {{ $email }}.</p>
        <p>Click the button below to reset your password:</p>
        
        <a href="{{ url('reset-password/'.$token) }}" class="btn">Reset Password</a>
        
        <p>If you did not request a password reset, no further action is required.</p>
        
        <div class="footer">
            &copy; {{ date('Y') }} i-Islam. All rights reserved.
        </div>
    </div>
</body>
</html>