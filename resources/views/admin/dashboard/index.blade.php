@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row mt-5">
            @foreach ($result as $item_key=>$item_val)
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-{{$item_val['color']}}">
                            <i class="fa {{$item_val['icon']}}"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text capitalize">{{ __($item_key) }}</span>
                            <span class="info-box-number">{{$item_val['count']}}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
