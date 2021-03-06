@extends('layouts.admin')
@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('avatars'),'breadcrumbs'=>[__('avatars')=>'#']])
    <section class="content bcg-white">
        <a href="{{route('avatars.create')}}" role="button" class="btn btn-primary btn-flat pull-right">
            <i class="fa fa-plus"></i> {{ __('create') }}
        </a>
        <table class="table table-hover orderable">
            <thead>
            <tr class="capitalize">
                <th>{{__('ID')}}</th>
                <th>{{__('name')}}</th>
                <th>{{__('avatar')}}</th>
                <th class="right-align">{{__('action')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>
                        <a href="{{route('avatars.edit', $row->id)}}">
                            {{ $row->name }}
                        </a>
                    </td>
                    <td>
                        @if($row->image)
                            <img class="img-md" src="{{ $row->image->getFile() }}"/>
                        @endif
                    </td>
                    <td>
                        <a href="{{route('avatars.edit', $row->id)}}" class="btn btn-flat btn-default pull-right">
                            <i class="fa fa-lg fa-edit">
                                <span class="sr-only">{{__('edit')}}</span>
                            </i>
                        </a>
                        <a href="{{route('avatars.show', $row->id)}}" role="button"
                           class="btn btn-flat btn-danger capitalize pull-right">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Nie znaleziono żadnych pozycji</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        {{ $rows->links() }}
    </section>
@endsection
