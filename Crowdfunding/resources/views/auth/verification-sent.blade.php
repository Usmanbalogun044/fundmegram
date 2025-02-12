@extends('app')

@section('title') {{ trans('auth.verification-sent') }} - @endsection

@section('content')
    <!-- Main Container -->
    <div class="container flex flex-col items-center justify-center min-h-screen px-4">
        <!-- Shooting Star Effect -->
        <div class="shooting-star"></div>
        <div class="bg-white p-6 w-full max-w-lg shadow-md rounded-b-lg text-center">
            <h1 class="text-3xl font-bold mb-4">Almost there! 🌟</h1>
            <p class="text-lg mb-6" aria-live="polite">
                We've sent a verification link to your email. Please check your inbox (and your spam folder) and click the link to verify your email address!
            </p>
            <p class="text-lg font-semibold text-gray-800">Don't forget to check your email!</p>
            {{-- <p class="text-md text-gray-500 mb-6">
                If you don't see the email, check your spam folder or click the button below to request a new verification link.
            </p> --}}
            {{-- <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition-all duration-300">
                Resend Verification Link
            </button> --}}
        </div>
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                If you didn’t create an account, please ignore this email.
            </p>
        </div>
    </div>
@endsection
