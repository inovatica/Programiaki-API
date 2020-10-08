<section class="content-header mb-3">
    @isset($title)
    <h1 class="capitalize">
        {{$title}}
        @isset($smalltitle)
        <small>{{$smalltitle}}</small>
        @endisset
    </h1>
    @endisset
    @isset($breadcrumbs)
    <ol class="breadcrumb">
        <li>
            <a href="/admin">
                <i class="fa fa-home"></i> <span class="sr-only">{{__('dashboard')}}</span>
            </a>
        </li>
        @foreach ($breadcrumbs as $k => $row)
            <li @if($loop->last) class="active" @endif>
                <a href="{{$row}}" class="capitalize">{{$k}}</a>
            </li>
        @endforeach
    </ol>
    @endisset
</section>