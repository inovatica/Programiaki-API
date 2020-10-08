@extends('layouts.admin')
@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('edit').' '.__('setting'),'breadcrumbs'=>[__('settings')=>'/admin/settings',__('edit')=>'#']])
    <section class="content bcg-white">
        <form role="form" method="POST">
            <div class="box-body">
                <div class="form-group">
                    <label for="name" class="capitalize">{{__('name')}}</label>
                    <input type="text" class="form-control" id="name" required
                           value="{{ $setting->name }}" name="name" disabled>
                </div>
                <div class="form-group">
                    <label for="key" class="capitalize">{{__('key')}}</label>
                    <input type="text" class="form-control" id="key" value="{{ $setting->key }}"
                           disabled>
                </div>
                <div class="form-group">
                    <label for="type" class="capitalize">{{__('date_type')}}</label>
                    <select class="form-control" id="type" name="type" disabled>
                        @foreach($availableTypes as $type)
                            <option @if($setting->type == $type) selected @endif >{{$type}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="value" class="capitalize">{{__('value')}}</label>
                    <input type="text" class="form-control" id="value"
                           value="{{ $setting->value }}" required name="value">
                </div>
            </div>
            <div class="box-footer">
                <a href="/admin/settings" role="button" class="btn btn-flat btn-default capitalize pull-left">{{__('back')}}</a>
                <button type="submit" class="btn btn-flat btn-primary capitalize pull-right">{{__('save')}}</button>
            </div>
            {{ method_field('PUT') }}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
    </section>
@endsection
