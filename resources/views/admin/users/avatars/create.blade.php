@extends('layouts.admin')
@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('create').' '.__('avatar'),'breadcrumbs'=>[__('avatars') => route('avatars.index'),__('create')=>'#']])
    <section class="content bcg-white">
        <form role="form" method="POST" action="{{route('avatars.store')}}" enctype="multipart/form-data">
            <div class="box-body">
                <div class="form-group col-xs-6">
                    <label for="name" class="capitalize">{{__('name')}}</label>
                    <input type="text" class="form-control" id="name" required
                           value="{{ old('name') }}" name="name">
                </div>
                <div class="form-group col-xs-6">
                    <div>
                        <label for="image" class="capitalize">{{  __('choose_image') }}</label>
                        <input type="file" name="image" id="image" accept="image/*">
                        <p class="help-block image-preview"></p>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <a href="{{route('avatars.index')}}"
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
                        console.log(span)
                        document.getElementsByClassName('image-preview')[0].innerHTML = span.innerHTML;
                    };
                })(f);
                reader.readAsDataURL(f);
            }
        }

        document.getElementById('image').addEventListener('change', handleFileImage, false);
    })
</script>
@endpush