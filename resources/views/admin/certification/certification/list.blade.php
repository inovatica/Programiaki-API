@extends('layouts.admin')
@section('content')
@include('layouts.components.admin.contentheader', ['title' => __('issued_certificates'),'breadcrumbs'=>[__('certification')=>'#']])
<section class="content bcg-white">

    <table class="table table-hover orderable">
        <thead>
            <tr class="capitalize">
                <th>{{__('ID')}}</th>
                <th>{{__('child_login')}}</th>
                <th>{{__('active')}}</th>
                <th>{{__('issued_at')}}</th>
                <th>{{__('updated_at')}}</th>
                <th class="right-align">{{__('action')}}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $key=>$row)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $row->user->name }}</td>
                <td>
                    <a href="{{route('certification.toggleActive', $row['uuid'])}}" role="button">
                    @if($row->active)
                    <span class="btn btn-success" title='{{__('deactivate')}}'><i class="fa fa-check"></i></span>
                    @else
                    <span class="btn btn-danger" title='{{__('activate')}}'><i class="fa fa-close"></i></span>
                    @endif
                    </a>
                </td>
                <td>{{ $row->issued_at }}</td>
                <td>{{ $row->updated_at }}</td>
                <td>
                    <a href="{{route('certification.destroy', $row['uuid'])}}" role="button"
                       class="btn btn-flat btn-danger capitalize pull-right" title='{{__('remove')}}'>
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