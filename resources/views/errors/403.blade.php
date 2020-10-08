<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <title>403 Unauthorized!</title>
        <link rel="stylesheet" href="/css/app.css"/>
        <link href="https://fonts.googleapis.com/css?family=Cairo:200,400,700&amp;subset=latin-ext" rel="stylesheet" type="text/css">
        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                background: url(/images/background.png) no-repeat center center fixed;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
                display: table;
                font-weight: 300;
                font-family: 'Cairo', sans-serif;
            }

            .container-a {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content-a {
                text-align: center;
                display: inline-block;
                min-width: 980px;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
                background-color: #fff;
            }
        </style>
    </head>
    <body>
        <h1 class="sr-only">403 {{ __('unauthorized') }}</h1>
        <div class="container-a">
            <div class="content-a">
                <div class="title"><strong>403</strong> {{ __('unauthorized') }}</div>

                <a href="{{route('login')}}" role="button" class="btn btn-lg btn-flat btn-default"> <i class="fa fa-unlock"></i>
                    Logowanie</a>

                @if(env('APP_DEBUG') === true)
                <div style="margin-top: 18px; text-align: left !important;">
                    {{dump($exception)}}
                </div>
                @endif
            </div>

        </div>

    </body>
</html>
