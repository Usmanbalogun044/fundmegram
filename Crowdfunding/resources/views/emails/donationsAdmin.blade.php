<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Donation for {{ $campaign }} - {{ $title_site }}</title>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-700">New Donation Received</h2>
        <p class="text-gray-600 mt-2">Hello Admin,</p>
        <p class="text-gray-600">A new donation has been made for the campaign <strong class="text-gray-800">{{ $campaign->title }}</strong> on <strong>{{ $title_site }}</strong>.</p>

        <h3 class="mt-4 text-lg font-semibold text-gray-700">Donor Details:</h3>
        <ul class="mt-2 space-y-2">
            <li class="text-gray-700"><strong>Name:</strong> {{ $username }}</li>
            <li class="text-gray-700"><strong>Email:</strong> {{ $email }}</li>
            <li class="text-gray-700"><strong>Amount:</strong> ${{ number_format($amount, 2) }}</li>
        </ul>

        <p class="mt-4 text-gray-600">Thank you for your continued support in managing the campaign.</p>

        <p class="mt-6 text-xs text-gray-500 border-t pt-4">
            This is an automated message from <strong>{{ $title_site }}</strong>. Please do not reply.
        </p>
    </div>
</body>
</html>
