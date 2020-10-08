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
                background: url(/images/background.jpg) no-repeat center center fixed;
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
    <h1 class="sr-only">404 Nie znaleziono</h1>
        <div class="container">
            <div class="content">
                <div class="title"><strong>404</strong> Nie znaleziono</div>
            </div>
        </div>
    </body>
</html>
