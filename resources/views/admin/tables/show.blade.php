@extends('layouts.admin')
@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('remove').' '.__('table'),'breadcrumbs'=>[__('tables') => route('tables.list'),__('remove')=>'#']])
    <section class="content bcg-white">
        <form role="form" method="POST" action="{{route('tables.destroy',$table->id)}}">
            <div class="box-body">
                <h2 class="capitalize center-align">{{ __('are_you_sure_to_remove') }}</h2>
                <h2 class="capitalize center-align">"{{ $table->key }}"?</h2>
            </div>
            <div class="box-footer">
                <a href="{{route('tables.list')}}"
                   role="button"
                   class="btn btn-flat btn-default capitalize pull-left">{{__('no')}},{{__('back')}}</a>
                <button type="submit" class="btn btn-flat btn-danger capitalize pull-right">
                    <i class="fa fa-trash"></i>&nbsp;{{__('yes')}}, {{__('remove')}}
                </button>
            </div>
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
        </form>
    </section>
@endsection