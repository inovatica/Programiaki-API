@extends('layouts.admin')
@section('content')
@include('layouts.components.admin.contentheader', ['title' => __('create').' '.__('user'),'breadcrumbs'=>[__('users') => route('users.index'),__('create')=>'#']])
<section class="content bcg-white">
    <form role="form" method="POST" action="{{route('users.store')}}" enctype="multipart/form-data">
        <div class="box-body">
            <div class="form-group col-xs-6">
                <label for="name" class="capitalize">{{__('name')}}</label>
                <input type="text" class="form-control" id="name" required
                       value="{{ old('name') }}" name="name">
            </div>
            <div class="form-group col-xs-6">
                <label for="email" class="capitalize">{{__('email')}}</label>
                <input type="text" class="form-control" id="email" required
                       value="{{ old('email') }}" name="email">
            </div>
            <div class="form-group col-xs-6">
                <label for="role" class="capitalize">{{__('role')}}</label>
                <select id="role" name="role" class="form-control" required>
                    <option value="">Wybierz rolę</option>
                    @foreach($roles as $role)
                        <option value="{{$role->name}}">{{$role->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6 col-xs-12">
                <label for="institutions">{{ __('institutions') }}</label>
                <select multiple class="form-control" name="institutions[]" size="20" id="institutions">
                    @foreach($institutions as $institution)
                        <option value="{{$institution->id}}">{{$institution->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-xs-6">
                <label for="cbx" class="label-cbx">
                    <input id="cbx" name="active" value="1" type="checkbox" class="invisible" checked>
                    <div class="cbxcheckbox">
                        <svg width="20px" height="20px" viewBox="0 0 20 20">
                            <path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
                            <polyline points="4 11 8 15 16 6"></polyline>
                        </svg>
                    </div>
                    <span class="capitalize">{{ __('is_active') }}</span>
                </label>
            </div>
            <div class="form-group col-xs-6 pull-left">
                <label for="image" class="capitalize">{{  __('choose_image') }}</label>
                <input type="file" name="image" id="image" accept="image/*">
                <p class="help-block image-preview"></p>
            </div>
            <div class="form-group col-lg-6 col-xs-12">
                <label for="avatar_id" class="capitalize">{{  __('avatar') }}
                    <input type="hidden" name="avatar_id" id="avatar_id">
                </label><br />
                    @foreach($avatars as $avatar)
                        @if($avatar->image)
                            <img class="img-lg select-avatar margin" src="{{$avatar->image->getFile()}}" data-id="{{$avatar->id}}" data-index="{{ $loop->index + 1 }}" />
                        @endif
                    @endforeach
            </div>
        </div>
        <div class="box-footer">
            <a href="{{route('users.index')}}"
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
        
        $('.select-avatar').click(function(){
            $('#avatar_id').val($(this).data('id'));
            $('.select-avatar').css('border', 'none');
            $(this).css('border', '2px dotted black');
        });
        
        $('#institutions').multiSelect({keepOrder: true});
    })
</script>
@endpush