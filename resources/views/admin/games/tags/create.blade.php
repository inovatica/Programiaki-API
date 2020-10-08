@extends('layouts.admin')
@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('create').' '.__('tag'),'breadcrumbs'=>[__('tags') => route('tags.index'),__('create')=>'#']])
    <section class="content bcg-white">
        <form role="form" method="POST" action="{{route('tags.store')}}">
            <div class="box-body">
                <div class="form-group col-md-6 col-xs-12">
                    <div>
                        <label for="key" class="capitalize">{{__('key')}}</label>
                        <input type="text" class="form-control" id="key" required
                               value="{{ old('key') }}" name="key">
                    </div>
                    <div>
                        <label for="cbx" class="label-cbx">
                            <input id="cbx" name="active" value="1" type="checkbox" class="invisible"
                                   @if(old('active', false)) checked @endif>
                            <div class="cbxcheckbox">
                                <svg width="20px" height="20px" viewBox="0 0 20 20">
                                    <path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
                                    <polyline points="4 11 8 15 16 6"></polyline>
                                </svg>
                            </div>
                            <span class="capitalize">{{ __('is_active') }}</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="table_id" class="capitalize">{{__('table')}}</label>
                        <select class="form-control" id="table_id" name="table_id">
                            <option value="">{{__('table is not assigned')}}</option>
                            @foreach($tables as $table)
                                <option value="{{$table->id}}">{{$table->key}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-6 col-xs-12">
                    <label for="objects">{{ __('objects') }}</label>
                    <select multiple class="form-control" name="objects[]" size="20" id="objects">
                        @foreach($objects as $object)
                            <option value="{{$object->id}}">{{ $object->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="box-footer">
                <a href="{{route('tags.index')}}"
                   role="button"
                   class="btn btn-flat btn-default capitalize pull-left">{{__('back')}}</a>
                <button type="submit" class="btn btn-flat btn-primary capitalize pull-right">{{__('save')}}</button>
            </div>
            {{ csrf_field() }}
        </form>
    </section>
@endsection

@push('scripts-footer')
<script type="text/javascript">
    $(document).ready(function () {
        $('#objects').multiSelect({keepOrder: true});
    })
</script>
@endpush