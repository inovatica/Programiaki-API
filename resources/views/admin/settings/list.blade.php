@extends('layouts.admin')
@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('settings'),'breadcrumbs'=>[__('settings')=>'#']])
    <section class="content bcg-white">
        <table class="table table-hover orderable">
            <thead>
            <tr class="capitalize">
                <th>{{__('ID')}}</th>
                <th>{{__('name')}}</th>
                <th>{{__('key')}}</th>
                <th>{{__('type')}}</th>
                <th>{{__('value')}}</th>
                <th class="right-align">{{__('action')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->key }}</td>
                    <td>{{ $row->type }}</td>
                    <td>{{ $row->value }}</td>
                    <td>
                        <a href="{{route('settings.edit', $row->id)}}" class="btn btn-flat btn-default pull-right">
                            <i class="fa fa-lg fa-edit">
                                <span class="sr-only">{{__('edit')}}</span>
                            </i>
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
