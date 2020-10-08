@extends('layouts.admin')
@section('content')
@include('layouts.components.admin.contentheader', ['title' => __('user_gamification').' '.$user->name,'breadcrumbs'=>[__('gamification')=> route('gamification.list'),__('preview')=>'#']])
    <section class="content bcg-white">

        <table class="table table-hover orderable">
            <thead>
            <tr class="capitalize">
                <th>{{__('ID')}}</th>
                <th>{{__('game')}}</th>
                <th>{{__('level')}}</th>
                <th>{{__('date')}}</th>
                <th class="right-align">{{__('action')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($result as $key=>$row)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $row->game->name }}</td>
                    <td>{{ $row->gameLevel->name }}</td>
                    <td>{{ $row['updated_at'] }}</td>
                    <td>
                        <a href="{{route('gamification.destroy', $row['uuid'])}}" role="button"
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
        {{ $result->links() }}
        <div class="box-footer">
            <a href="{{route('gamification.list')}}"
               role="button"
               class="btn btn-flat btn-default capitalize pull-left">{{__('back')}}</a>
        </div>
    </section>
@endsection
