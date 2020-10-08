@extends('layouts.admin')
@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('remove').' '.__('child from institution').': '.$institution->name,'breadcrumbs'=>[__('institutions') => route('institutions.list'),__('childs')=>route('institutions.childs', $institution->id),__('remove')=>'#']])
    <section class="content bcg-white">
        <form role="form" method="POST" action="{{route('institutions.childs.destroy',[$institution->id, $child->id])}}">
            <div class="box-body">
                <h2 class="capitalize center-align">{{ __('are_you_sure_to_remove') }}</h2>
                <h2 class="capitalize center-align">"{{ $child->name }}"?</h2>
            </div>
            <div class="box-footer">
                <a href="{{route('institutions.childs', $institution->id)}}"
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