@if(session('error'))
    <div class="alert alert-danger">
        {{session('error')}}
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success">
        {{session('success')}}
    </div>
@endif
@if(session('flash_message'))
    <div class="alert alert-info">
        {{session('flash_message')}}
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
