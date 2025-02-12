<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
    <div class="w-full max-w-2xl bg-white shadow-lg rounded-xl p-6 space-y-6">
        <div class="flex items-center space-x-4">
            <img src="{{ asset('public/avatar/'.$user->avatar) }}" alt="Avatar" class="w-20 h-20 rounded-full object-cover border-2 border-blue-500">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">{{ $user->name }}</h1>
                <p class="text-gray-600">{{ $user->email }}</p>
                <span class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded-lg">{{ ucfirst($user->role) }}</span>
            </div>
        </div>

        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-500 text-sm">User ID</p>
                    <p class="text-gray-800 font-medium">{{ $user->id }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Home Address</p>
                    <p class="text-gray-800 font-medium">{{ $user->homeaddress }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Street Address</p>
                    <p class="text-gray-800 font-medium">{{ $user->streetaddress }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">City</p>
                    <p class="text-gray-800 font-medium">{{ $user->city }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">State</p>
                    <p class="text-gray-800 font-medium">{{ $user->state }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Zip Code</p>
                    <p class="text-gray-800 font-medium">{{ $user->zipcode }}</p>
                </div>
            </div>
        </div>
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Payment Details</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-500 text-sm">Payment Gateway</p>
                        <p class="text-gray-800 font-medium">{{ ucfirst($user->payment_gateway) }}</p>
                    </div>

                    @if($user->payment_gateway==='Zelle')
                        <div>
                            <p class="text-gray-500 text-sm">Zelle Name</p>
                            <p class="text-gray-800 font-medium">{{ json_decode($user->zelle)->zelle_name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Zelle Contact</p>
                            <p class="text-gray-800 font-medium">{{ json_decode($user->zelle)->zelle_contact }}</p>
                        </div>
                    @elseif($user->payment_gateway==='Venmo')
                        <div>
                            <p class="text-gray-500 text-sm">Venmo Username</p>
                            <p class="text-gray-800 font-medium">{{json_decode($user->venmo)->name}}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Venmo phone</p>
                            <p class="text-gray-800 font-medium">{{json_decode($user->venmo)->phone}}</p>
                        </div>
                    @elseif($user->payment_gateway==='Applepay')
                        <div>
                            <p class="text-gray-500 text-sm">Apple Pay ID</p>
                            <p class="text-gray-800 font-medium">{{ json_decode($user->apple_pay)->apple_name}}</p>
                        </div>
                        @elseif($user->payment_gateway==='Paypal')
                        <div>
                            <p class="text-gray-500 text-sm">paypal</p>
                            <p class="text-gray-800 font-medium">{{$user->paypal_account}}</p>
                        </div>
                        @elseif($user->payment_gateway==='Bank')
                        <div>
                            <p class="text-gray-500 text-sm">bank</p>
                            <p class="text-gray-800 font-medium">{{$user->bank}}</p>
                        </div>
                        @elseif($user->payment_gateway==='International_bank')
                        <div>
                            <p class="text-gray-500 text-sm">internationalbank</p>
                            <p class="text-gray-800 font-medium">{{$user->international_bank}}</p>
                        </div>
                        @elseif($user->payment_gateway==='Crypto')
                        <div>
                            <p class="text-gray-500 text-sm">crypto type</p>
                            <p class="text-gray-800 font-medium">{{json_decode($user->crypto_wallet)->crypto_type}}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">crypto Network</p>
                            <p class="text-gray-800 font-medium">{{json_decode($user->crypto_wallet)->crypto_network}}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">crypto wallet</p>
                            <p class="text-gray-800 font-medium">{{json_decode($user->crypto_wallet)->crypto_wallet}}</p>
                        </div>
                    @endif
                </div>
            </div>


        <div class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">Uploaded Documents</h2>
            <div class="grid grid-cols-2 gap-4">
                @foreach(['national_id_back' => 'National ID Back', 'national_id_front' => 'National ID Front', 'passport_front' => 'Passport'] as $field => $label)
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 text-center">
                        <p class="text-gray-600 text-sm">{{ $label }}</p>
                        @if($user->$field)
                            <img src="{{ asset('public/'.$user->$field) }}" alt="{{ $label }}" class="w-24 h-16 mx-auto rounded-md">
                            <a href="{{ asset('public/'.$user->$field) }}" download class="text-blue-500 text-sm mt-2 inline-block">Download</a>
                        @else
                            <p class="text-gray-400 text-sm">Not uploaded</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ url('panel/admin/members') }}" class="text-blue-600 hover:text-blue-800 font-medium">&larr; Back</a>
        </div>
    </div>
</body>
</html>
