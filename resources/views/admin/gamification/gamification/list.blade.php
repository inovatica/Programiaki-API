@extends('layouts.admin')
@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('gamification'),'breadcrumbs'=>[__('gamification')=>'#']])
    <section class="content bcg-white">

        <table class="table table-hover orderable">
            <thead>
            <tr class="capitalize">
                <th>{{__('ID')}}</th>
                <th>{{__('child_login')}}</th>
                <th>{{__('group')}}</th>
                <th>{{__('babysitter')}}</th>
                <th>{{__('finished_levels')}}</th>
                <th class="right-align">{{__('action')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($result as $key=>$row)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $row['name'] }}</td>
                    <td>
                        @if (count($row['groups']) > 0)
                        <ul>
                        @foreach ($row['groups'] as $group)
                        <li>{{ $group->name }} ({{ $group->institution->name }})</li>
                        @endforeach
                        </ul>
                        @else
                        {{__('not_assigned')}}
                        @endif
                    </td>
                    <td>
                        @if (count($row['groups']) > 0)
                        <ul>
                        @foreach ($row['groups'] as $group)
                        <li>{{ $group->user->name }}</li>
                        @endforeach
                        </ul>
                        @else
                        {{__('not_assigned')}}
                        @endif
                    </td>
                    <td>
                        {{ $row['levels'] }}
                    </td>
                    <td>
                        <a href="{{route('gamification.show', $key)}}" role="button"
                           class="btn btn-flat btn-info capitalize pull-right">
                            <i class="fa fa-search"></i>
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
