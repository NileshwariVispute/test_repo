<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <style type="text/css">
            /* CLIENT-SPECIFIC STYLES */
            body,
            table,
            td,
            a {
                -webkit-text-size-adjust: 100%;
                -ms-text-size-adjust: 100%;
            }
            table,
            td {
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
            }
            img {
                -ms-interpolation-mode: bicubic;
            }

            /* RESET STYLES */
            img {
                border: 0;
                height: auto;
                line-height: 100%;
                outline: none;
                text-decoration: none;
            }
            table {
                border-collapse: collapse !important;
            }
            body {
                height: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }

            /* iOS BLUE LINKS */
            a[x-apple-data-detectors] {
                color: inherit !important;
                text-decoration: none !important;
                font-size: inherit !important;
                font-family: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
            }

            /* MOBILE STYLES */
            @media screen and (max-width: 600px) {
                h1 {
                    font-size: 32px !important;
                    line-height: 32px !important;
                }
            }

            /* ANDROID CENTER FIX */
            div[style*="margin: 16px 0;"] {
                margin: 0 !important;
            }

            .email_code {
                display: block;
                background-color: #ddd;
                border-radius: 40px;
                padding: 20px;
                text-align: center;
                font-size: 36px;
                font-family: "Open Sans";
                letter-spacing: 10px;
                box-shadow: 0px 7px 22px 0px rgba(0, 0, 0, 0.1);
            }
        </style>
    </head>
    <body style="background-color: #edf2f7; margin: 0 !important; padding: 0 !important;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <!-- LOGO -->
            <!-- <tr>
                <td bgcolor="#edf2f7" align="center">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                        <tr>
                            <td align="center" valign="top" style="padding: 40px 10px 40px 10px;">
                                <img
                                    alt="Logo"
                                    src="{{ url('/images/brightwing_logo.png') }}"
                                    width="169"
                                    height="40"
                                    style="display: block; height: 75px; width: 75px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; font-size: 16px;"
                                    border="0"
                                />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr> -->
            <!-- COPY BLOCK -->
            <tr>
                <td bgcolor="#edf2f7" align="center" style="padding: 40px 10px 40px 10px;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                        <!-- COPY -->
                        <tr>
                            <td
                                bgcolor="#ffffff"
                                align="left"
                                style="
                                    padding: 20px 30px 20px 30px;
                                    color: #3d4852;
                                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
                                    font-size: 18px;
                                    font-weight: bold;
                                    line-height: 25px;" >
                                <p style="margin: 0;">Hello, {{ $name }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 25px;">
                                <p style="margin: 0;">Wowwee! Thanks for registering an account with Brightwing!</p>
                            </td>
                        </tr>
                        <tr>
                            <td
                                bgcolor="#ffffff"
                                align="left"
                                style="
                                    padding: 0px 30px 0px 30px;
                                    color: #666666;
                                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
                                    font-size: 16px;
                                    font-weight: 400;
                                    line-height: 25px;" >
                                <p>Before we get started, we'll need to verify your email. Just enter below verification code in field:
</p>
                            </td>
                        </tr>
                        <!-- BULLETPROOF BUTTON -->
                        <tr>
                            <td bgcolor="#ffffff" align="left">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td bgcolor="#ffffff" align="center" style="padding: 20px 30px 20px 30px;">
                                            <table border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td align="center" style="border-radius: 3px;" bgcolor="#3d4852">
                                                        <div class="c-email__code">
                                                            <span class="email_code">{{ $email_verification_otp }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <!-- COPY -->
                        <tr>
                            <td
                                bgcolor="#ffffff"
                                align="left"
                                style="
                                    padding: 0px 30px 0px 30px;
                                    color: #666666;
                                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
                                    font-size: 16px;
                                    font-weight: 400;
                                    line-height: 25px;" >
                                <p>This verification code will expire in 60 minutes.</p>
                            </td>
                        </tr>
                        <tr>
                            <td
                                bgcolor="#ffffff"
                                align="left"
                                style="
                                    padding: 0px 30px 0px 30px;
                                    color: #666666;
                                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
                                    font-size: 16px;
                                    font-weight: 400;
                                    line-height: 25px;" >
                                <p>If you didn't attempt to verify your email address with Brightwing, please delete this email.</p>
                            </td>
                        </tr>
                        <!-- COPY -->
                        <tr>
                            <td
                                bgcolor="#ffffff"
                                align="left"
                                style="
                                    padding: 0px 30px 20px 30px;
                                    color: #666666;
                                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
                                    font-size: 16px;
                                    font-weight: 400;
                                    line-height: 25px;" >
                                <p>
                                    Regards,<br/>
                                    Brightwing
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- FOOTER -->
            <tr>
                <td bgcolor="#edf2f7" align="center" style="padding: 0px 10px 0px 10px;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                        <!-- ADDRESS -->
                        <tr>
                            <td bgcolor="#edf2f7" align="left" style="padding: 32px; color: #666666; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;">
                                <p style="text-align: center; line-height: 1.5em; margin-top: 0; color: #b0adc5; font-size: 12px;">&copy; {{ date('Y') }} Brightwing. All rights reserved.</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
