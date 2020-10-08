@extends('layouts.admin')
@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('add').' '.__('babysitter to institution').': '.$institution->name,'breadcrumbs'=>[__('institutions')=>'/admin/institutions',__('babysitters')=>route('institutions.babysitters', $institution->id),__('add')=>'#']])
    <section class="content bcg-white">
        <form role="form" method="POST">
            <div class="box-body">
                <div class="form-group">
                    <label for="user_id" class="capitalize">{{__('babysitter')}}</label>
                    <select class="form-control" id="user_id" name="user_id">
                        @foreach($babysitters as $babysitter)
                            <option value="{{$babysitter->uuid}}">{{$babysitter->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="box-footer">
                <a href="{{route('institutions.babysitters', $institution->id)}}" role="button" class="btn btn-flat btn-default capitalize pull-left">{{__('back')}}</a>
                <button type="submit" class="btn btn-flat btn-primary capitalize pull-right">{{__('save')}}</button>
            </div>
            {{ method_field('PUT') }}
            <input type="hidden" name="institution_id" value="{{ $institution->id }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
    </section>
@endsection
