@extends('admin.layout')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100 p-6">
    <div class="w-full max-w-3xl bg-white shadow-2xl rounded-2xl p-8 relative overflow-hidden transform transition-all hover:scale-105">
        <h2 class="text-4xl font-extrabold text-center text-gray-800 mb-8">📩 Send Email</h2>

        <!-- Notification Section -->
        @if(session('success'))
            <div id="success-notification" class="fixed top-5 right-5 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg animate-slide-in">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div id="error-notification" class="fixed top-5 right-5 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg animate-slide-in">
                ❌ {{ session('error') }}
            </div>
        @endif

        <!-- Send to a specific user -->
        <div class="mb-8 p-6 border border-blue-300 rounded-2xl bg-gradient-to-r from-blue-50 to-blue-100 shadow-lg">
            <h3 class="text-lg font-semibold text-blue-700 mb-4">Send to a Specific User</h3>
            <form action="{{ route('admin.sendEmailToUser') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Recipient Email</label>
                    <input type="email" name="email" class="w-full mt-1 p-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Enter recipient's email" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Subject</label>
                    <input type="text" name="subject" class="w-full mt-1 p-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Enter email subject" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Message</label>
                    <textarea name="message" class="w-full mt-1 p-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Enter your message..." rows="4" required></textarea>
                </div>

                <button type="submit" class="w-full bg-blue-300 hover:bg-blue-700 text-blue font-bold py-3 rounded-xl shadow-md transition-all transform hover:scale-110">📧 Send Email</button>
            </form>
        </div>

        <!-- Send to all users -->
        <div class="p-6 border border-purple-300 rounded-2xl bg-gradient-to-r from-purple-50 to-purple-100 shadow-lg">
            <h3 class="text-lg font-semibold text-purple-700 mb-4">Send to All Users</h3>
            <form action="{{ route('admin.sendEmailToAllUsers') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Subject</label>
                    <input type="text" name="subject" class="w-full mt-1 p-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-400" placeholder="Enter email subject" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Message</label>
                    <textarea name="message" class="w-full mt-1 p-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-400" placeholder="Enter your message..." rows="4" required></textarea>
                </div>

                <button type="submit" class="w-full bg-purple-300 hover:bg-purple-300 text-blue font-bold py-3 rounded-xl shadow-md transition-all transform hover:scale-110">📢 Send to All Users</button>
            </form>
        </div>
    </div>
</div>

<!-- Notification Auto Close Script -->
<script>
    setTimeout(() => {
        document.getElementById('success-notification')?.remove();
        document.getElementById('error-notification')?.remove();
    }, 5000);
</script>

<style>
    @keyframes slideIn {
        from { transform: translateX(100px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .animate-slide-in {
        animation: slideIn 0.5s ease-out;
    }
</style>
@endsection
