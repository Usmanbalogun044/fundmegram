<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Donation Removed - {{ $title_site }}</title>
    <style>
        @keyframes fadeOut {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(0.9); opacity: 0.5; }
            100% { transform: scale(0); opacity: 0; }
        }
    </style>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); text-align: center;">

        <!-- Animated Cross Mark -->
        <div style="margin-top: 20px;">
            <svg style="animation: fadeOut 0.8s ease-out;" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </div>

        <h2 style="color: #333; margin-top: 15px;">Donation not approved ❌</h2>
        <p>Dear <strong>{{ $username }}</strong>,</p>
        <p>We regret to inform you that your donation of <strong>${{ number_format($amount, 2) }}</strong> to <strong>{{ $campaign }}</strong> is not <span style="color: #ef4444; font-weight: bold;">approved</span>.</p>

        <div style="margin: 20px auto; background: #f3f4f6; padding: 10px; border-radius: 8px; display: inline-block;">
            <p><strong>Donation Details:</strong></p>
            <p><strong>Amount:</strong> ${{ number_format($amount, 2) }}</p>
            <p><strong>Campaign:</strong> {{ $campaign }}</p>
            <p><strong>Email:</strong> {{ $email }}</p>
        </div>

        <p>If this was a mistake or if you have any concerns, please contact our support team.</p>

        <!-- CTA Button -->
        <a href="{{ url('/') }}" style="display: inline-block; margin-top: 15px; padding: 10px 20px; background-color: #ef4444; color: #fff; text-decoration: none; border-radius: 5px;">Contact Support</a>

        <p style="margin-top: 20px; font-size: 12px; color: #777;">
            This is an automated message from {{ $title_site }}. Please do not reply.
        </p>
    </div>
</body>
</html>
