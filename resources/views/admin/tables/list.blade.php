@extends('layouts.admin')
@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('tables'),'breadcrumbs'=>[__('tables')=>'#']])
    <section class="content bcg-white">
        <a href="{{route('tables.add')}}" role="button" class="btn btn-primary btn-flat pull-right">
            <i class="fa fa-plus"></i> {{ __('create') }}
        </a>
        <table class="table table-hover orderable">
            <thead>
            <tr class="capitalize">
                <th>{{__('ID')}}</th>
                <th>{{__('key')}}</th>
                <th>{{__('active')}}</th>
                <th>{{__('institution')}}</th>
                <th class="right-align">{{__('action')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->key }}</td>
                    <td>
                        @if($row->active)
                            <span class="btn btn-success"><i class="fa fa-check"></i></span>
                        @else
                            <span class="btn btn-danger"><i class="fa fa-ban"></i></span>
                        @endif
                    </td>
                    <td>@if($row->institution)
                        {{ $row->institution->name }}
                    @endif</td>
                    <td>
                        <a href="{{route('tables.edit', $row->id)}}" class="btn btn-flat btn-default pull-right">
                            <i class="fa fa-lg fa-edit">
                                <span class="sr-only">{{__('edit')}}</span>
                            </i>
                        </a>
                        <a href="{{route('tables.show', $row->id)}}" role="button"
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
