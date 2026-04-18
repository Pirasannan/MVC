<?php
/**
 * Mailer — Central PHPMailer wrapper for MediLink
 *
 * Usage:
 *   Mailer::sendOtp($toEmail, $toName, $otp);
 *   Mailer::send($toEmail, $toName, $subject, $htmlBody);
 *
 * All credentials come from config.php constants:
 *   MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_FROM, MAIL_FROM_NAME
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';

class Mailer
{
    /**
     * Core send method. All other methods call this.
     *
     * @param string $toEmail   Recipient email
     * @param string $toName    Recipient display name
     * @param string $subject   Email subject
     * @param string $htmlBody  HTML email body
     * @return bool             true on success, false on failure
     */
    public static function send(string $toEmail, string $toName, string $subject, string $htmlBody): bool
    {
        $mail = new PHPMailer(true); // true = throw exceptions

        try {
            // ── Server Settings ──────────────────────────────────────────
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USERNAME;
            $mail->Password   = MAIL_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = MAIL_PORT;

            // ── From ─────────────────────────────────────────────────────
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addReplyTo(MAIL_FROM, MAIL_FROM_NAME);

            // ── Recipient ────────────────────────────────────────────────
            $mail->addAddress($toEmail, $toName);

            // ── Content ──────────────────────────────────────────────────
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $htmlBody));

            $mail->send();
            return true;

        } catch (Exception $e) {
            // Log error silently — never expose SMTP details to users
            error_log('[Mailer Error] ' . $mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Send OTP email for Forgot Password or Registration.
     *
     * @param string $toEmail  Recipient email
     * @param string $toName   Recipient name
     * @param string $otp      6-digit code
     * @param string $type     'reset' or 'register'
     * @return bool
     */
    public static function sendOtp(string $toEmail, string $toName, string $otp, string $type = 'reset'): bool
    {
        $subject = ($type === 'register') ? 'Verify Your MediLink Account' : 'Your MediLink Password Reset Code';
        $htmlBody = self::otpEmailTemplate($toName, $otp, $type);

        return self::send($toEmail, $toName, $subject, $htmlBody);
    }

    /**
     * Send a general system notification email.
     * Use this for appointments, verifications, etc.
     *
     * @param string $toEmail   Recipient email
     * @param string $toName    Recipient name
     * @param string $subject   Email subject line
     * @param string $message   Plain-text or short HTML message (will be wrapped in template)
     * @return bool
     */
    public static function sendNotification(string $toEmail, string $toName, string $subject, string $message): bool
    {
        $htmlBody = self::notificationEmailTemplate($toName, $subject, $message);
        return self::send($toEmail, $toName, $subject, $htmlBody);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // E-MAIL TEMPLATES
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * OTP Email Template — styled, professional HTML email.
     */
    private static function otpEmailTemplate(string $name, string $otp, string $type = 'reset'): string
    {
        $year = date('Y');
        $title = ($type === 'register') ? 'Account Verification' : 'Password Reset Request';
        $mainText = ($type === 'register') 
            ? 'Thank you for choosing MediLink. Use the verification code below to complete your registration and activate your account.'
            : 'We received a request to reset your MediLink password. Use the verification code below to proceed with the reset.';

        return <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>{$title}</title>
        </head>
        <body style="margin:0;padding:0;background-color:#f5f7fa;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f5f7fa;padding:40px 20px;">
                <tr>
                    <td align="center">
                        <table width="560" cellpadding="0" cellspacing="0" border="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
                            
                            <!-- Header -->
                            <tr>
                                <td style="background:linear-gradient(135deg,#109CF1,#0b64f3);padding:32px 40px;text-align:center;">
                                    <h1 style="margin:0;color:#ffffff;font-size:26px;font-weight:700;letter-spacing:-0.5px;">MediLink</h1>
                                    <p style="margin:6px 0 0;color:rgba(255,255,255,0.85);font-size:14px;">{$title}</p>
                                </td>
                            </tr>

                            <!-- Body -->
                            <tr>
                                <td style="padding:40px;">
                                    <p style="margin:0 0 16px;font-size:16px;color:#1a1a2e;">Hi <strong>{$name}</strong>,</p>
                                    <p style="margin:0 0 28px;font-size:15px;color:#555;line-height:1.6;">
                                        {$mainText} This code is valid for <strong>15 minutes</strong>.
                                    </p>

                                    <!-- OTP Box -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td align="center" style="padding:0 0 32px;">
                                                <div style="display:inline-block;background:#f0f7ff;border:2px dashed #109CF1;border-radius:12px;padding:20px 48px;">
                                                    <p style="margin:0 0 4px;font-size:12px;color:#109CF1;font-weight:600;text-transform:uppercase;letter-spacing:1px;">Your OTP Code</p>
                                                    <p style="margin:0;font-size:40px;font-weight:800;color:#109CF1;letter-spacing:10px;">{$otp}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>

                                    <p style="margin:0 0 16px;font-size:14px;color:#777;line-height:1.6;">
                                        If you didn't request this, you can safely ignore this email.
                                    </p>
                                    <p style="margin:0;font-size:14px;color:#777;">
                                        — The MediLink Team
                                    </p>
                                </td>
                            </tr>

                            <!-- Footer -->
                            <tr>
                                <td style="background:#f8fafc;padding:20px 40px;border-top:1px solid #e8edf3;text-align:center;">
                                    <p style="margin:0;font-size:12px;color:#aaa;">© {$year} MediLink. All rights reserved.</p>
                                    <p style="margin:4px 0 0;font-size:12px;color:#ccc;">This is an automated message, please do not reply.</p>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        HTML;
    }

    /**
     * General notification email template.
     * Used for appointment updates, verification status, etc.
     */
    private static function notificationEmailTemplate(string $name, string $subject, string $message): string
    {
        $year = date('Y');
        return <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>{$subject}</title>
        </head>
        <body style="margin:0;padding:0;background-color:#f5f7fa;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f5f7fa;padding:40px 20px;">
                <tr>
                    <td align="center">
                        <table width="560" cellpadding="0" cellspacing="0" border="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
                            
                            <!-- Header -->
                            <tr>
                                <td style="background:linear-gradient(135deg,#109CF1,#0b64f3);padding:28px 40px;text-align:center;">
                                    <h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:700;">MediLink</h1>
                                </td>
                            </tr>

                            <!-- Body -->
                            <tr>
                                <td style="padding:36px 40px;">
                                    <p style="margin:0 0 14px;font-size:16px;color:#1a1a2e;">Hi <strong>{$name}</strong>,</p>
                                    <div style="font-size:15px;color:#444;line-height:1.7;">
                                        {$message}
                                    </div>
                                    <p style="margin:28px 0 0;font-size:14px;color:#888;">— The MediLink Team</p>
                                </td>
                            </tr>

                            <!-- Footer -->
                            <tr>
                                <td style="background:#f8fafc;padding:18px 40px;border-top:1px solid #e8edf3;text-align:center;">
                                    <p style="margin:0;font-size:12px;color:#aaa;">© {$year} MediLink. All rights reserved.</p>
                                    <p style="margin:4px 0 0;font-size:12px;color:#ccc;">This is an automated message, please do not reply.</p>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        HTML;
    }
}
