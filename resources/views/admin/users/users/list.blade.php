@extends('layouts.admin')
@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('users'),'breadcrumbs'=>[__('users')=>'#']])
    <section class="content bcg-white">
        <a href="{{route('users.create')}}" role="button" class="btn btn-primary btn-flat pull-right">
            <i class="fa fa-plus"></i> {{ __('create') }}
        </a>
        <table class="table table-hover orderable">
            <thead>
            <tr class="capitalize">
                <th>{{__('ID')}}</th>
                <th>{{__('name')}}</th>
                <th>{{__('email')}}</th>
                <th>{{__('active')}}</th>
                <th>{{__('role')}}</th>
                <th>{{__('avatar')}}</th>
                <th class="right-align">{{__('action')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>
                        <a href="{{route('users.edit', $row->id)}}">
                            {{ $row->name }}
                        </a>
                    </td>
                    <td>
                        {{ $row->email }}
                    </td>
                    <td>
                        @if($row->active)
                            <span class="btn btn-success disabled"><i class="fa fa-check"></i></span>
                        @else
                            <span class="btn btn-danger disabled"><i class="fa fa-close"></i></span>
                        @endif
                    </td>
                    <td>
                        {{$row->getRoleNames()}}
                    </td>
                    <td>
                        @if($row->avatar && $row->avatar->image)
                            <img class="img-sm" src="{{ $row->avatar->image->getFile() }}"/>
                        @else
                        - - -
                        @endif
                    </td>
                    <td>
                        <a href="{{route('users.edit', $row->id)}}" class="btn btn-flat btn-default pull-right">
                            <i class="fa fa-lg fa-edit">
                                <span class="sr-only">{{__('edit')}}</span>
                            </i>
                        </a>
                        <a href="{{route('users.destroy', $row->id)}}" role="button"
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
        {{ $rows->links() }}
    </section>
@endsection
