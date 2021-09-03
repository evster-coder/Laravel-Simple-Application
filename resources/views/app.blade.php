<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Admin App</title>

    <link href="{{ asset('css/common.css') }}" type="text/css" rel="stylesheet"/>
    <link href="{{ mix('css/app.css') }}" type="text/css" rel="stylesheet"/>

    </head>
    <body>
    @if (Auth::check() && Auth::user()->admin)
        <script>
            window.Laravel = {!!json_encode([
                'isLoggedin' => true,
                'user' => Auth::user()
            ])!!}
        </script>
    @else
        <script>
            window.Laravel = {!!json_encode([
                'isLoggedin' => false
            ])!!}
        </script>
    @endif
        <div id="app" class="content">
        </div>

        <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
    </body>
</html>
