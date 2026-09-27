<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Verification Code</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f3f6f8;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #1a202c;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #f3f6f8;
            padding: 40px 16px;
        }
        .container {
            max-width: 560px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #0c505d 0%, #083c46 100%);
            padding: 32px 24px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            color: #ffffff;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .content {
            padding: 36px 32px;
            line-height: 1.6;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #0c505d;
            margin-bottom: 12px;
        }
        .message {
            font-size: 15px;
            color: #4a5568;
            margin-bottom: 24px;
        }
        .otp-box {
            background: #f7fafc;
            border: 2px dashed #0c505d;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            margin: 28px 0;
        }
        .otp-code {
            font-size: 34px;
            font-weight: 800;
            letter-spacing: 0.28em;
            color: #0c505d;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, Courier, monospace;
            padding-left: 0.28em;
        }
        .otp-expiry {
            font-size: 13px;
            color: #718096;
            margin-top: 10px;
        }
        .warning-box {
            background-color: #fffaf0;
            border-left: 4px solid #dd6b20;
            padding: 14px 16px;
            border-radius: 4px;
            margin-top: 24px;
            font-size: 13px;
            color: #7b341e;
        }
        .footer {
            background-color: #f7fafc;
            padding: 24px 32px;
            text-align: center;
            border-top: 1px solid #edf2f7;
            font-size: 12px;
            color: #a0aec0;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>King Lotus Group</h1>
            </div>
            <div class="content">
                <div class="greeting">Hello,</div>
                <div class="message">
                    We received a request to reset your password for the King Lotus Group administration portal. Use the verification code below to proceed with resetting your password:
                </div>

                <div class="otp-box">
                    <div class="otp-code">{{ $otp }}</div>
                    <div class="otp-expiry">This code will expire in {{ $expiryMinutes }} minutes.</div>
                </div>

                <div class="message">
                    Enter this code on the verification screen to set up a new password for your account.
                </div>

                <div class="warning-box">
                    <strong>Security Notice:</strong> If you did not request a password reset, please ignore this email or contact support immediately. Never share this code with anyone.
                </div>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} King Lotus Group. All rights reserved.<br>
                This is an automated security message. Please do not reply directly to this email.
            </div>
        </div>
    </div>
</body>
</html>
