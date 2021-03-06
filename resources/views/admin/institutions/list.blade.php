@extends('layouts.admin')
@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('institutions'),'breadcrumbs'=>[__('institutions')=>'#']])
    <section class="content bcg-white">
        <a href="{{route('institutions.add')}}" role="button" class="btn btn-primary btn-flat pull-right">
            <i class="fa fa-plus"></i> {{ __('create') }}
        </a>
        <table class="table table-hover orderable">
            <thead>
            <tr class="capitalize">
                <th>{{__('ID')}}</th>
                <th>{{__('name')}}</th>
                <th>{{__('address')}}</th>
                <th class="right-align">{{__('action')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->street }} {{ $row->street_number }}<br />{{ $row->zip_code }} {{ $row->city }}</td>
                    <td>
                        <a href="{{route('institutions.childs', $row->id)}}" class="btn btn-flat btn-default pull-right">
                            <i class="fa fa-lg fa-group">
                                <span class="sr-only">{{__('childs')}}</span>
                            </i>
                        </a>
                        <a href="{{route('institutions.babysitters', $row->id)}}" class="btn btn-flat btn-default pull-right">
                            <i class="fa fa-lg fa-user">
                                <span class="sr-only">{{__('babysitters')}}</span>
                            </i>
                        </a>
                        <a href="{{route('institutions.edit', $row->id)}}" class="btn btn-flat btn-default pull-right">
                            <i class="fa fa-lg fa-edit">
                                <span class="sr-only">{{__('edit')}}</span>
                            </i>
                        </a>
                        <a href="{{route('institutions.show', $row->id)}}" role="button"
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
    </section>
@endsection
