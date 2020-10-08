@extends('layouts.admin')
@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('childs in institution').': '.$institution->name,'breadcrumbs'=>[__('institutions')=>'/admin/institutions',__('childs')=>'#']])
    <section class="content bcg-white">
        <a href="{{route('institutions.childs.add', $institution->id)}}" role="button" class="btn btn-primary btn-flat pull-right">
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
            @forelse ($institution->childs() as $child)
                <tr>
                    <td>{{ $child->uuid }}</td>
                    <td>{{ $child->name }}</td>
                    <td>
                        <a href="{{route('institutions.childs.show', [$institution->id, $child->id])}}" role="button"
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
