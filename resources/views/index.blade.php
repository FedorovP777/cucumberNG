<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="/css/app.css">
        <link rel="stylesheet" type="text/css" href="/css/main.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,700&amp;subset=cyrillic" rel="stylesheet">
        <title>Manage Nginx virtual hosts</title>
    </head>
    <body>
    <div id="app">

    </div>


    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <script src="/js/app.js"></script>
    </body>
</html>
