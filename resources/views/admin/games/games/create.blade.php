@extends('layouts.admin')
@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('create').' '.__('game'),'breadcrumbs'=>[__('games') => route('games.index'),__('create')=>'#']])
    <section class="content bcg-white">
        <form role="form" method="POST" action="{{route('games.store')}}">
            <div class="box-body">
                <div class="form-group col-xs-6">
                    <label for="name" class="capitalize">{{__('name')}}</label>
                    <input type="text" class="form-control" id="name" required
                           value="{{ old('name') }}" name="name">
                </div>
                <div class="form-group col-xs-6">
                    <label for="key" class="capitalize">{{__('key')}}</label>
                    <input type="text" class="form-control" id="key" required
                           value="{{ old('key') }}" name="key">
                </div>
                <div class="form-group col-xs-6">
                    <label for="cbx" class="label-cbx">
                        <input id="cbx" name="active" value="1" type="checkbox" class="invisible"
                               @if(old('active', false)) checked @endif>
                        <div class="cbxcheckbox">
                            <svg width="20px" height="20px" viewBox="0 0 20 20">
                                <path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
                                <polyline points="4 11 8 15 16 6"></polyline>
                            </svg>
                        </div>
                        <span class="capitalize">{{ __('is_active') }}</span>
                    </label>
                </div>
            </div>
            <div class="box-footer">
                <a href="{{route('games.index')}}"
                   role="button"
                   class="btn btn-flat btn-default capitalize pull-left">{{__('back')}}</a>
                <button type="submit" class="btn btn-flat btn-primary capitalize pull-right">{{__('save')}}</button>
            </div>
            {{ csrf_field() }}
        </form>
    </section>
@endsection