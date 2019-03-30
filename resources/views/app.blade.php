<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

    <title>Second Online</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-1725903-8"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-1725903-8');
    </script>

    <script type="text/javascript">
        window.AppUser = @if(isset($user)) @json($user) @else null @endif;
        window.AppIntroVideo = @json($intro_video);
        window.AppNextBroadcast = @json($next_broadcast);
    </script>
</head>
<body>
    <div id="app"></div>
    <script src="{{ mix('js/main.js') }}"></script>
    <script src="https://player.vimeo.com/api/player.js"></script>
</body>
</html>