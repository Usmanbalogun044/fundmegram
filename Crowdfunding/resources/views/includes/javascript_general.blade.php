<script src="{{ asset('public/js/core.min.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('public/css/bootstrap/bootstrap.min.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('public/js/jqueryTimeago_'.Lang::locale().'.js') }}"></script>
<script src="{{ asset('public/js/datepicker/bootstrap-datepicker.js')}}" type="text/javascript"></script>
<script src="{{ asset('public/js/app-functions.js') }}?v={{$settings->version}}"></script>
{{-- <script src="{{ asset('public/js/install-app.js') }}?v={{$settings->version}}"></script> --}}
<script src="{{ asset('public/js/switch-theme.js') }}?v={{$settings->version}}"></script>
<script src="https://js.stripe.com/v3/"></script>
{{-- <script>
  let deferredPrompt;

  window.addEventListener('beforeinstallprompt', (e) => {
    // Prevent the default browser prompt
    e.preventDefault();
    deferredPrompt = e;

    // Create the install button
    const installBtn = document.createElement('button');
    installBtn.textContent = 'Install App';
    installBtn.style.position = 'fixed';
    installBtn.style.bottom = '20px';
    installBtn.style.right = '20px';
    installBtn.style.padding = '10px 20px';
    installBtn.style.backgroundColor = '#6777ef';
    installBtn.style.color = '#fff';
    installBtn.style.border = 'none';
    installBtn.style.borderRadius = '5px';
    installBtn.style.cursor = 'pointer';
    installBtn.style.zIndex = '1000';
    installBtn.style.fontSize = '14px';
    
    document.body.appendChild(installBtn);

    // Show prompt when button is clicked
    installBtn.addEventListener('click', async () => {
      if (deferredPrompt) {
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        if (outcome === 'accepted') {
          console.log('User accepted the install prompt');
          installBtn.style.display = 'none';
        } else {
          console.log('User dismissed the install prompt');
        }
        deferredPrompt = null;
      }
    });
  });

  // Hide the button if app is installed
  window.addEventListener('appinstalled', () => {
    console.log('PWA was installed');
    if (installBtn) {
      installBtn.style.display = 'none';
    }
  });
</script> --}}
