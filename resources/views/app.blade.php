<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

    <title>@yield('title')</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/all.css') }}" rel="stylesheet"> --}}
    
    <script type="text/javascript">
        window.AppUser = @if(isset($user)) @json($user) @else null @endif;
        window.AppIntroVideo = @json($intro_video);
        window.AppNextBroadcast = @json($next_broadcast);
    </script>
</head>
<body>
    <div id="app"></div>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="https://player.vimeo.com/api/player.js"></script>
</body>
</html>