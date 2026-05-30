<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
        style="max-width: 520px; margin: 40px auto; background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); overflow: hidden;">

        {{-- Header --}}
        <tr>
            <td style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #2563eb 100%); padding: 32px 40px; text-align: center;">
                <h1 style="margin: 0; color: #ffffff; font-size: 22px; font-weight: 700; letter-spacing: 0.5px;">
                    DMW HRIS
                </h1>
                <p style="margin: 6px 0 0; color: rgba(255,255,255,0.85); font-size: 13px; font-weight: 400;">
                    Email Verification
                </p>
            </td>
        </tr>

        {{-- Body --}}
        <tr>
            <td style="padding: 36px 40px 20px;">
                <p style="margin: 0 0 16px; color: #374151; font-size: 15px; line-height: 1.6;">
                    You are registering for a <strong>DMW HRIS</strong> account. Please use the verification code below to complete your registration:
                </p>

                {{-- OTP Code --}}
                <div style="margin: 24px 0; text-align: center;">
                    <div style="display: inline-block; background: linear-gradient(135deg, #eff6ff, #dbeafe); border: 2px solid #93c5fd; border-radius: 12px; padding: 16px 36px;">
                        <span style="font-size: 36px; font-weight: 800; letter-spacing: 10px; color: #1e3a8a; font-family: 'Courier New', monospace;">
                            {{ $otp }}
                        </span>
                    </div>
                </div>

                <p style="margin: 20px 0 0; color: #6b7280; font-size: 13px; line-height: 1.6; text-align: center;">
                    This code will expire in <strong style="color: #dc2626;">10 minutes</strong>.
                    <br>Do not share this code with anyone.
                </p>
            </td>
        </tr>

        {{-- Divider --}}
        <tr>
            <td style="padding: 0 40px;">
                <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 0;">
            </td>
        </tr>

        {{-- Footer --}}
        <tr>
            <td style="padding: 20px 40px 28px; text-align: center;">
                <p style="margin: 0; color: #9ca3af; font-size: 12px; line-height: 1.5;">
                    If you did not request this code, you can safely ignore this email.
                    <br>&copy; {{ date('Y') }} Department of Migrant Workers — HRIS
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
