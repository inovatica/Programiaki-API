<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <title>Page not found!</title>
    <link rel="stylesheet" href="/css/app.css"/>
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            color: #ffffff;
            background: #4f85bb;
            background: -webkit-linear-gradient(top, #4f85bb 0%, #4f85bb 100%);
            background: linear-gradient(to bottom, #4f85bb 0%, #4f85bb 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#4f85bb', endColorstr='#4f85bb', GradientType=0);
            display: table;
            font-weight: 300;
            font-family: 'Lato', sans-serif;
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 72px;
            margin-bottom: 40px;
            background-color: #fff;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="sr-only">Be right back. We are in maintaince mode</h1>
    <div class="content">
        <img src="404.png">
        <div class="title">Be right back. We are in maintaince mode</div>
    </div>
</div>
</body>
</html>