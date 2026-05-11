<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Contact Message</title>
</head>
<body style="margin:0;padding:0;background-color:#080808;font-family:Arial,Helvetica,sans-serif;">

  <!-- Outer wrapper -->
  <table width="100%" cellpadding="0" cellspacing="0" border="0"
         style="background-color:#080808;margin:0;padding:0;">
    <tr>
      <td align="center" style="padding:48px 16px;">

        <table width="600" cellpadding="0" cellspacing="0" border="0"
               style="max-width:600px;width:100%;">

          <!-- AMBIENT TOP GLOW -->
          <tr>
            <td align="center" style="padding-bottom:0;line-height:0;">
              <div style="width:320px;height:2px;background:linear-gradient(90deg,transparent,#E8621A 40%,#ff8c4b 60%,transparent);margin:0 auto;border-radius:100%;box-shadow:0 0 60px 20px rgba(232,98,26,0.35);"></div>
            </td>
          </tr>

          <!-- HEADER / LOGO BLOCK -->
          <tr>
            <td align="center"
                style="background:#111111;padding:40px 40px 32px 40px;border:1px solid rgba(232,98,26,0.6);border-bottom:none;border-radius:20px 20px 0 0;">

              <div style="display:inline-block;padding:4px;">
                <img src="data:image/png;base64,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"
                     alt="VOXURA"
                     width="250"
                     style="display:block;border-radius:8px;max-width:250px;height:auto;" />
              </div>

            </td>
          </tr>

          <!-- MAIN CARD -->
          <tr>
            <td style="background:#111111;border-left:1px solid rgba(232,98,26,0.6);border-right:1px solid rgba(232,98,26,0.6);padding:0 48px 40px 48px;">

              <!-- Heading -->
              <h1 style="margin:32px 0 10px 0;font-size:28px;font-weight:bold;color:#ffffff;letter-spacing:-0.5px;line-height:1.2;text-align:center;">
                New Contact Message
              </h1>

              <!-- Subheading -->
              <p style="margin:0 0 20px 0;font-size:16px;color:rgba(255,255,255,0.55);font-weight:normal;text-align:center;">
                Someone sent a message through the contact form
              </p>

              <!-- Divider -->
              <div style="height:1px;background:linear-gradient(90deg,transparent,rgba(232,98,26,0.25) 40%,rgba(232,98,26,0.25) 60%,transparent);margin-bottom:28px;"></div>

              <!-- Name row -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
                <tr>
                  <td width="90" style="font-size:13px;color:rgba(255,255,255,0.35);font-weight:bold;letter-spacing:0.5px;text-transform:uppercase;padding-top:2px;vertical-align:top;">Name</td>
                  <td style="font-size:15px;color:#ffffff;font-weight:bold;">{{ $name }}</td>
                </tr>
              </table>

              <!-- Email row -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
                <tr>
                  <td width="90" style="font-size:13px;color:rgba(255,255,255,0.35);font-weight:bold;letter-spacing:0.5px;text-transform:uppercase;padding-top:2px;vertical-align:top;">Email</td>
                  <td style="font-size:15px;"><a href="mailto:{{ $email }}" style="color:#E8621A;text-decoration:none;">{{ $email }}</a></td>
                </tr>
              </table>

              <!-- Divider -->
              <div style="height:1px;background:rgba(255,255,255,0.06);margin-bottom:20px;"></div>

              <!-- Message label -->
              <p style="margin:0 0 10px 0;font-size:13px;color:rgba(255,255,255,0.35);font-weight:bold;letter-spacing:0.5px;text-transform:uppercase;">Message</p>

              <!-- Message body -->
              <div style="background:#1a1a1a;border:1px solid rgba(232,98,26,0.20);border-radius:10px;padding:20px 22px;">
                <p style="margin:0;font-size:15px;line-height:1.75;color:rgba(255,255,255,0.75);white-space:pre-wrap;">{{ $userMessage }}</p>
              </div>

              <!-- Reply CTA -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td align="center" style="padding:32px 0 0 0;">
                    <a href="mailto:{{ $email }}?subject=Re: Your message to Voxura"
                       style="display:inline-block;background-color:#E8621A;color:#ffffff;text-decoration:none;font-weight:bold;font-size:15px;padding:14px 36px;border-radius:50px;border:none;font-family:Arial,Helvetica,sans-serif;letter-spacing:0.3px;box-shadow:0 0 24px 4px rgba(232,98,26,0.50),0 4px 16px rgba(232,98,26,0.30);">
                      Reply to {{ $name }}
                    </a>
                  </td>
                </tr>
              </table>

            </td>
          </tr>

          <!-- FOOTER CARD -->
          <tr>
            <td style="background:linear-gradient(180deg,#111111 0%,#0e0e0e 100%);border:1px solid rgba(232,98,26,0.6);border-top:1px solid rgba(255,255,255,0.08);border-radius:0 0 20px 20px;padding:28px 48px 32px 48px;">

              <p style="margin:0 0 16px 0;font-size:14px;color:rgba(255,255,255,0.55);">
                Regards,<br>
                <strong style="color:#ffffff;letter-spacing:0.5px;">The Voxura Team</strong>
              </p>

              <div style="height:1px;background:rgba(255,255,255,0.06);margin-bottom:16px;"></div>

              <p style="margin:0;font-size:12px;color:rgba(255,255,255,0.28);line-height:1.6;">
                This message was submitted via the contact form on Voxura. Reply directly to this email to respond to {{ $name }}.
              </p>

            </td>
          </tr>

          <!-- BOTTOM GLOW + COPYRIGHT -->
          <tr>
            <td align="center" style="padding-top:32px;">
              <div style="width:200px;height:1px;background:linear-gradient(90deg,transparent,rgba(232,98,26,0.20),transparent);margin:0 auto 16px auto;"></div>
              <p style="margin:0;font-size:11px;color:rgba(255,255,255,0.18);letter-spacing:1px;text-transform:uppercase;">
                &copy; {{ date('Y') }} Voxura. All rights reserved.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</body>
</html>
