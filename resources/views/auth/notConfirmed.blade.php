@extends('layouts.session')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>

                <div class="panel-body">
                    Twoje konto nie jest jeszcze aktywne.<br/>
                    Kliknij link aktywacyjny, który otrzymałeś ma maila
                </div>
                <div class="panel-footer text-right">
                    <a class="btn btn-primary" href="{{ route('register.resend.email') }}">
                        Wyślij ponownie link aktywacyjny
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
