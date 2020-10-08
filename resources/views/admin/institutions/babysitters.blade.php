@extends('layouts.admin')
@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('babysitters in institution').': '.$institution->name,'breadcrumbs'=>[__('institutions')=>'/admin/institutions',__('babysitters')=>'#']])
    <section class="content bcg-white">
        <a href="{{route('institutions.babysitters.add', $institution->id)}}" role="button" class="btn btn-primary btn-flat pull-right">
            <i class="fa fa-plus"></i> {{ __('create') }}
        </a>
        <table class="table table-hover orderable">
            <thead>
            <tr class="capitalize">
                <th>{{__('ID')}}</th>
                <th>{{__('name')}}</th>
                <th class="right-align">{{__('action')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($institution->babysitters() as $babysitter)
                <tr>
                    <td>{{ $babysitter->uuid }}</td>
                    <td>{{ $babysitter->name }}</td>
                    <td>
                        <a href="{{route('institutions.babysitters.show', [$institution->id, $babysitter->id])}}" role="button"
                           class="btn btn-flat btn-danger capitalize pull-right">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Nie znaleziono żadnych pozycji</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="box-footer">
            <a href="{{route('institutions.list')}}" role="button" class="btn btn-flat btn-default capitalize pull-left">{{__('back')}}</a>
        </div>
    </section>
@endsection
