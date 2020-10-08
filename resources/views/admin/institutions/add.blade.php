@extends('layouts.admin')
@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('add').' '.__('institution'),'breadcrumbs'=>[__('institutions')=>'/admin/institutions',__('add')=>'#']])
    <section class="content bcg-white">
        <form role="form" method="POST">
            <div class="box-body">
                <div class="form-institution">
                    <label for="name" class="capitalize">{{__('name')}}</label>
                    <input type="text" class="form-control" id="name" required
                           value="" name="name">
                </div>
                <div class="form-institution">
                    <label for="city" class="capitalize">{{__('city')}}</label>
                    <input type="text" class="form-control" id="city" required
                           value="" name="city">
                </div>
                <div class="form-institution">
                    <label for="zip_code" class="capitalize">{{__('zip code')}}</label>
                    <input type="text" class="form-control" id="zip_code" required
                           value="" name="zip_code">
                </div>
                <div class="form-institution">
                    <label for="street" class="capitalize">{{__('street')}}</label>
                    <input type="text" class="form-control" id="street" required
                           value="" name="street">
                </div>
                <div class="form-institution">
                    <label for="street_number" class="capitalize">{{__('street number')}}</label>
                    <input type="text" class="form-control" id="street_number" required
                           value="" name="street_number">
                </div>
                <div class="form-institution">
                    <label for="phone" class="capitalize">{{__('phone')}}</label>
                    <input type="text" class="form-control" id="phone" required
                           value="" name="phone">
                </div>
            </div>
            <div class="box-footer">
                <a href="/admin/institutions" role="button" class="btn btn-flat btn-default capitalize pull-left">{{__('back')}}</a>
                <button type="submit" class="btn btn-flat btn-primary capitalize pull-right">{{__('save')}}</button>
            </div>
            {{ method_field('PUT') }}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
    </section>
@endsection
