@extends('layouts.admin')
@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('update').' '.__('object'),'breadcrumbs'=>[__('objects') => route('objects.index'),__('update')=>'#']])
    <section class="content bcg-white">
        <form role="form" method="POST" action="{{route('objects.update',$object->id)}}" enctype="multipart/form-data">
            <div class="box-body">
                <div class="form-group col-xs-6">
                    <label for="name" class="capitalize">{{__('name')}}</label>
                    <input type="text" class="form-control" id="name" required
                           value="{{ $object->name }}" name="name">
                </div>
                <div class="form-group col-xs-6">
                    <label for="key" class="capitalize">{{__('key')}}</label>
                    <input type="text" class="form-control" id="key" required
                           value="{{ $object->key }}" name="key">
                </div>
                <div class="form-group col-xs-6">
                    <div>
                        <label for="type_id" class="capitalize">{{__('type')}}</label>
                        <select id="type_id" name="type_id" class="form-control" required>
                            <option value="">Wybierz typ</option>
                            @foreach($types as $type)
                                <option @if( $object->type_id == $type->id) selected
                                        @endif value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-2">
                        <label for="audio" class="capitalize">{{ __('choose_sound') }}</label>
                        <input type="file" name="audio" id="audio" accept="audio/*">
                        <p class="help-block audio-preview">
                            @if($object->audio_id)
                                <p class="help-block uppercase">{{ __('current_file') }}:</p>
                                <audio id="sound" controls src="{{ $object->audio->getFile() }}"></audio>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="form-group col-xs-6">
                    <label for="image" class="capitalize">{{  __('choose_image') }}</label>
                    <input type="file" name="image" id="image" accept="image/*">
                    <p class="help-block image-preview">
                        @if($object->image_id)
                            <p class="help-block uppercase">{{ __('current_file') }}:</p>
                            <img class="img-responsive" src="{{ $object->image->getFile() }}"/>
                        @endif
                    </p>
                </div>
            </div>
            <div class="box-footer">
                <a href="{{route('objects.index')}}"
                   role="button"
                   class="btn btn-flat btn-default capitalize pull-left">{{__('back')}}</a>
                <a href="{{route('objects.show', $object->id)}}"
                   role="button"
                   class="btn btn-flat btn-danger capitalize pull-left ml-5"><i class="fa fa-trash"></i></a>
                <button type="submit" class="btn btn-flat btn-primary capitalize pull-right">{{__('save')}}</button>
            </div>
            {{ csrf_field() }}
            {{ method_field('PUT') }}
        </form>
    </section>
    <section class="row mt-3">
        <div class="col-md-6 col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-tags"></i>
                    <h3 class="box-title capitalize">{{ __('tags') }}</h3>
                    <a href="{{route('tags.create')}}" role="button" class="btn btn-primary btn-xs btn-flat pull-right">
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
                <div class="box-body">
                    <table class="table table-hover orderable">
                        <thead>
                        <tr class="capitalize">
                            <th>{{ __('ID') }}</th>
                            <th>{{__('key')}}</th>
                            <th>{{__('active')}}</th>
                            <th class="right-align">{{__('action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($object->tags as $row)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>
                                    <a href="{{route('tags.edit', $row->id)}}">
                                        {{ $row->key }}
                                    </a>
                                </td>
                                <td>
                                    @if($row->active)
                                        <span class="btn btn-success btn-xs"><i class="fa fa-check"></i></span>
                                    @else
                                        <span class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('tags.edit', $row->id)}}"
                                       class="btn btn-flat btn-default btn-xs pull-right">
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
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts-footer')
<script type="text/javascript">
    $(document).ready(function () {
        function handleFileImage(evt) {
            var files = evt.target.files;
            for (var i = 0, f; f = files[i]; i++) {
                if (!f.type.match('image.*')) {
                    continue;
                }

                var reader = new FileReader();
                reader.onload = (function (theFile) {
                    return function (e) {
                        var span = document.createElement('span');
                        span.innerHTML = ['<img class="img-responsive" src="', e.target.result,
                            '" title="', escape(theFile.name), '"/>'].join('');
                        document.getElementsByClassName('image-preview')[0].innerHTML = span.innerHTML;
                    };
                })(f);
                reader.readAsDataURL(f);
            }
        }

        document.getElementById('image').addEventListener('change', handleFileImage, false);

        function handleFileAudio(evt) {
            var files = evt.target.files;
            for (var i = 0, f; f = files[i]; i++) {
                if (!f.type.match('audio.*')) {
                    continue;
                }
                var span = document.createElement('span');
                span.innerHTML = '<audio id="sound" controls></audio>';
                document.getElementsByClassName('audio-preview')[0].innerHTML = span.innerHTML;
                document.getElementById('sound').src = URL.createObjectURL(f)
            }
        }

        document.getElementById('audio').addEventListener('change', handleFileAudio, false);
    })
</script>
@endpush