<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <title>500 Server Error!</title>
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
            background: url(/images/login-background.jpg) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
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
    <h1 class="sr-only"> 500 Wystąpił błąd aplikacji.</h1>
    <div class="content">
        <div class="title"><strong>500</strong> Wystąpił błąd aplikacji.</div>
    </div>
</div>
</body>
</html>




