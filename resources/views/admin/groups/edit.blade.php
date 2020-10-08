@extends('layouts.admin')
@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('edit').' '.__('group'),'breadcrumbs'=>[__('groups')=>'/admin/groups',__('edit')=>'#']])
    <section class="content bcg-white">
        <form role="form" method="POST" action="{{route('groups.update',$group->id)}}">
            <div class="box-body">
                <div class="form-group">
                    <label for="name" class="capitalize">{{__('name')}}</label>
                    <input type="text" class="form-control" id="name" required
                           value="{{ $group->name }}" name="name">
                </div>
                <div class="form-group">
                    <label for="institution_id" class="capitalize">{{__('institution')}}</label>
                    <select class="form-control" id="institution_id" name="institution_id">
                        @foreach($institutions as $institution)
                            <option value="{{$institution->id}}" @if($group->institution_id == $institution->id) selected @endif>{{$institution->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="babysitter_id" class="capitalize">{{__('babysitter')}}</label>
                    <select class="form-control" id="babysitter_id" name="babysitter_id">
                        @foreach($babysitters as $babysitter)
                            <option value="{{$babysitter->uuid}}" @if($group->babysitter_id == $babysitter->uuid) selected @endif>{{$babysitter->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="box-footer">
                <a href="/admin/groups" role="button" class="btn btn-flat btn-default capitalize pull-left">{{__('back')}}</a>
                <button type="submit" class="btn btn-flat btn-primary capitalize pull-right">{{__('save')}}</button>
            </div>
            {{ method_field('PUT') }}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
    </section>
@endsection

@push('scripts-footer')
<script>
    $('#institution_id').change(function(){
        getBabysitters();
    })
    function getBabysitters() {
        if ($('#institution_id').val()) {
            axios({
              method: 'post',
              url: '/admin/institutions/babysitters',
              data: {
                institution: $('#institution_id').val()
              }
            }).then(function(response){
                $('#babysitter_id').empty();
                $.each(response.data, function(key,value) {
                  $('#babysitter_id').append($("<option></option>")
                     .attr("value", value.uuid).text(value.name));
                });
            });
        }
    };
</script>
@endpush
