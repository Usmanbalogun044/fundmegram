<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Email</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-3xl bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">📩 Send Email</h2>

            <!-- Notification Section -->
            @if(session('success'))
                <div id="success-notification" class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded-md shadow-md">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div id="error-notification" class="fixed top-5 right-5 bg-red-500 text-white px-4 py-2 rounded-md shadow-md">
                    ❌ {{ session('error') }}
                </div>
            @endif

            <!-- Specific User Form -->
            <div class="mb-6 border border-blue-200 rounded-md bg-blue-50 p-4">
                <h3 class="text-lg font-semibold text-blue-700 mb-3">Send to a Specific User</h3>
                <form action="{{ route('admin.sendEmailToUser') }}" method="POST">
                    @csrf
                    <input type="email" name="email" class="w-full p-2 border rounded mb-3" placeholder="Recipient's email" required>
                    <input type="text" name="subject" class="w-full p-2 border rounded mb-3" placeholder="Email subject" required>
                    <textarea name="message" class="w-full p-2 border rounded mb-3" placeholder="Message..." rows="4" id="emi"required></textarea>
                    <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 rounded hover:bg-blue-600">📧 Send Email</button>
                </form>
            </div>

            <!-- All Users Form -->
            <div class="border border-purple-200 rounded-md bg-purple-50 p-4">
                <h3 class="text-lg font-semibold text-purple-700 mb-3">Send to All Users</h3>
                <form action="{{ route('admin.sendEmailToAllUsers') }}" method="POST">
                    @csrf
                    <input type="text" name="subject" class="w-full p-2 border rounded mb-3" placeholder="Email subject" required>
                    <textarea id="editor" name="message" class="w-full p-2 border rounded mb-3" placeholder="Message..." rows="4" required></textarea>
                    <button type="submit" class="w-full bg-purple-500 text-white font-bold py-2 rounded hover:bg-purple-600">📢 Send to All Users</button>
                </form>
            </div>
        </div>
    </div>

    <!-- CKEditor Initialization -->
    <script>
        ClassicEditor.create(document.querySelector('#editor')).catch(error => console.error(error));
         ClassicEditor.create(document.querySelector('#emi')).catch(error => console.error(error));
    </script>

    <!-- Auto Close Notification -->
    <script>
        setTimeout(() => {
            document.getElementById('success-notification')?.remove();
            document.getElementById('error-notification')?.remove();
        }, 5000);
    </script>
    
</body>
</html>
