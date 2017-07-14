<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Made with &#x2764;</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">

  @if (env('APP_ENV', 'production') === 'local')

  @else
    <link href="{{ cdn('app.css') }}" rel="stylesheet">
  @endif
</head>
<body>
  <div id="app">
    <app></app>
    {{-- <div class="content"> --}}
      {{-- <logo></logo> --}}
      {{-- <div> --}}
        {{-- <welcome /> --}}
        {{-- <router-view keep-alive name="default"></router-view> --}}
      {{-- </div> --}}
    {{-- </div> --}}
  </div>
  @if (env('APP_ENV', 'production') === 'local')
    <script src="http://localhost:3000/js/vendor.bundle.js"></script>
    <script src="http://localhost:3000/js/app.bundle.js"></script>
  @else
    {{-- <script src="{{ cdn('/js/manifest.js') }}"></script> --}}
    <script src="{{ cdn('vendor.js') }}"></script>
    <script src="{{ cdn('app.js') }}"></script>
  @endif
</body>
</html>
