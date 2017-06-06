<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Made with &#x2764;</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        @if (env('APP_ENV', 'production') === 'local')

        @else
          <link href="{{ cdn('app.css') }}" rel="stylesheet">
        @endif

        <!-- Styles -->
        <style>
            html, body {
                /*background-color: #fff;*/
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    Made with &#x2764;
                </div>
                <div id="app">
                  <welcome />
                </div>
            </div>
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
