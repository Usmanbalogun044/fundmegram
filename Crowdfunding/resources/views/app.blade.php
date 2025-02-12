<!DOCTYPE html>
<html lang="en" data-bs-theme="{{ !is_null(request()->cookie('theme')) ? request()->cookie('theme') : 'light' }}" id="theme-asset">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="description" content="@yield('description_custom'){{$settings->description}}">
		<meta name="keywords" content="{{ $settings->keywords }}" />
		<link rel="shortcut icon" href="{{ asset('public/img/favicon.png') }}" />
<!-- PWA  -->
<meta name="theme-color" content="#6777ef"/>
<link rel="apple-touch-icon" href="{{ asset('public/logo.png') }}">
<link rel="manifest" href="{{ asset('public/manifest.json') }}">
		<title>@section('title')@show @if(isset($settings->title)){{$settings->title}}@endif</title>

		@include('includes.css_general')

		@if ($settings->status_pwa)
			@laravelPWA
		@endif

		@yield('css')

	<!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>

<body>
	<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/{{config('fb_app.lang')}}/sdk.js#xfbml=1&version=v2.8&appId={{config('fb_app.id')}}";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

	<div class="popout font-default"></div>
		@include('includes.navbar')

<main role="main" @if (request()->path() != '/')class="padding-top-78"@endif>
		@yield('content')

			@include('includes.footer')
</main>

		@include('includes.javascript_general')

	@yield('javascript')

<div id="bodyContainer"></div>
<script src="{{ asset('public/sw.js') }}"></script>
<script>
   if ("serviceWorker" in navigator) {
      // Register a service worker hosted at the root of the
      // site using the default scope.
      navigator.serviceWorker.register("public/sw.js").then(
      (registration) => {
         console.log("Service worker registration succeeded:", registration);
      },
      (error) => {
         console.error(`Service worker registration failed: ${error}`);
      },
    );
  } else {
     console.error("Service workers are not supported.");
  }
</script>
</body>
</html>
