<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{ $subject }}</title>
    <style type="text/css" rel="stylesheet" media="all">
        /* Media Queries */
        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; width: 100%; background-color: #F2F4F6;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="background-color: #F2F4F6;">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <!-- Logo -->
                    <tr>
                        <td style="padding: 25px 0; text-align: center;">
                            <a href="{{ url('/') }}" target="_blank" style="font-family: Arial, Helvetica, sans-serif; font-size: 20px; font-weight: bold; color: #2F3133; text-decoration: none;">
                                {{ config('app.name') }}
                            </a>
                        </td>
                    </tr>

                    <!-- Email Body -->
                    <tr>
                        <td align="center" style="background-color: #FFFFFF; padding: 35px; border-top: 1px solid #EDEFF2; border-bottom: 1px solid #EDEFF2;">
                            <table width="570" cellpadding="0" cellspacing="0" align="center" style="max-width: 570px;">
                                <tr>
                                    <td style="font-family: Arial, Helvetica, sans-serif; color: #2F3133;">
                                        <h1 style="font-size: 20px; font-weight: bold;">{{ $subject }}</h1>
                                        <p style="font-size: 16px; color: #74787E; line-height: 1.5;">
                                            {{ $messageContent }}
                                        </p>
                                        <p style="font-size: 16px; color: #74787E;">
                                            Regards,<br>
                                            {{ config('app.name') }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding: 35px; text-align: center; font-family: Arial, Helvetica, sans-serif; color: #74787E; font-size: 12px;">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
