@extends('layouts.admin')
@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('tags'),'breadcrumbs'=>[__('tags')=>'#']])
    <section class="content bcg-white">
        <a href="{{route('tags.create')}}" role="button" class="btn btn-primary btn-flat pull-right">
            <i class="fa fa-plus"></i> {{ __('create') }}
        </a>
        <table class="table table-hover orderable">
            <thead>
            <tr class="capitalize">
                <th>{{__('ID')}}</th>
                <th>{{__('key')}}</th>
                <th>{{__('table')}}</th>
                <th>{{__('objects')}}</th>
                <th>{{__('active')}}</th>
                <th class="right-align">{{__('action')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>
                        <a href="{{route('tags.edit', $row->id)}}">
                            {{ $row->key }}
                        </a>
                    </td>
                    <td>@if($row->table)
                        {{ $row->table->key }}
                    @endif</td>
                    <td>
                        @forelse ($row->objects as $obj)
                            {{ $obj->name }},
                        @empty
                            Nie znaleziono żadnych obiektów
                        @endforelse
                    </td>
                    <td>
                        @if($row->active)
                            <span class="btn btn-success"><i class="fa fa-check"></i></span>
                        @else
                            <span class="btn btn-danger"><i class="fa fa-ban"></i></span>
                        @endif
                    </td>
                    <td>
                        <a href="{{route('tags.edit', $row->id)}}" class="btn btn-flat btn-default pull-right">
                            <i class="fa fa-lg fa-edit">
                                <span class="sr-only">{{__('edit')}}</span>
                            </i>
                        </a>
                        <a href="{{route('tags.show', $row->id)}}" role="button"
                           class="btn btn-flat btn-danger capitalize pull-right">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Nie znaleziono żadnych pozycji</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        {{ $rows->links() }}
    </section>
@endsection
