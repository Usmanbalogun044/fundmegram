<!DOCTYPE html>
<html lang="en" data-bs-theme="{{ request()->cookie('theme') ?? 'light' }}" id="theme-asset">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description_custom') {{ $settings->description }}">
    <meta name="keywords" content="{{ $settings->keywords }}">
    <link rel="shortcut icon" href="{{ asset('public/img/favicon.png') }}">
    
    <!-- PWA -->
    <meta name="theme-color" content="#6777ef">
    <link rel="apple-touch-icon" href="{{ asset('public/logo.png') }}">
    <link rel="manifest" href="{{ asset('public/manifest.json') }}">
    
    <title>@yield('title') @if(isset($settings->title)){{ $settings->title }}@endif</title>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @include('includes.css_general')

    @yield('css')

    <!-- CSRF Token for AJAX requests -->
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
</head>

<body>
    <div id="fb-root"></div>
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/{{ config('fb_app.lang') }}/sdk.js#xfbml=1&version=v2.8&appId={{ config('fb_app.id') }}";
            fjs.parentNode.insertBefore(js, fjs);
        })(document, 'script', 'facebook-jssdk');
    </script>

    <div class="popout font-default"></div>
    @include('includes.navbar')

    <main role="main" @if (request()->path() != '/') class="padding-top-78" @endif>
        @yield('content')

        @include('includes.footer')
    </main>

    @include('includes.javascript_general')

    @yield('javascript')

    <div id="bodyContainer"></div>

    <!-- Service Worker Registration -->
    <script src="{{ asset('public/sw.js') }}"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register("public/sw.js").then(
                (registration) => {
                    console.log("Service worker registration succeeded:", registration);
                },
                (error) => {
                    console.error("Service worker registration failed:", error);
                }
            );
        } else {
            console.error("Service workers are not supported.");
        }
    </script>

    <!-- Immediate PWA Installation Prompt on Page Load -->
    <script>
        let deferredPrompt;

        // Show SweetAlert prompt immediately upon page load
        Swal.fire({
            title: 'Install App',
            text: 'Do you want to install this app on your device?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#6777ef',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Install',
            cancelButtonText: 'Not now'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show native install prompt
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('User accepted the PWA install');
                        } else {
                            console.log('User dismissed the PWA install');
                        }
                        deferredPrompt = null;
                    });
                }
            }
        });

        // Capture the beforeinstallprompt event
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
        });

        // Check if PWA is installed
        window.addEventListener('appinstalled', () => {
            console.log('PWA was installed');
            Swal.fire({
                title: 'Installed!',
                text: 'The app has been installed successfully.',
                icon: 'success',
                confirmButtonColor: '#6777ef',
                confirmButtonText: 'Okay'
            });
        });
    </script>
</body>
</html>
