@extends('layouts.admin')

@section('content')
    @include('layouts.components.admin.contentheader', ['title' => __('update').' '.__('game'),'breadcrumbs'=>[__('games') => route('games.index'),__('update')=>'#']])
    <section class="content bcg-white">
        <form role="form" method="POST" action="{{route('games.update',$game->id)}}">
            <div class="box-body">
                <div class="form-group col-xs-6">
                    <label for="name" class="capitalize">{{__('name')}}</label>
                    <input type="text" class="form-control" id="name" required
                           value="{{ $game->name }}" name="name">
                </div>
                <div class="form-group col-xs-6">
                    <label for="key" class="capitalize">{{__('key')}}</label>
                    <input type="text" class="form-control" id="key" required
                           value="{{ $game->key }}" name="key">
                </div>
                <div class="form-group col-xs-6">
                    <label for="cbx" class="label-cbx">
                        <input id="cbx" name="active" value="1" type="checkbox" class="invisible"
                               @if($game->active) checked @endif>
                        <div class="cbxcheckbox">
                            <svg width="20px" height="20px" viewBox="0 0 20 20">
                                <path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
                                <polyline points="4 11 8 15 16 6"></polyline>
                            </svg>
                        </div>
                        <span class="capitalize">{{ __('is_active') }}</span>
                    </label>
                </div>
            </div>
            <div class="box-footer">
                <a href="{{route('games.index')}}"
                   role="button"
                   class="btn btn-flat btn-default capitalize pull-left">{{__('back')}}</a>
                <a href="{{route('games.show', $game->id)}}"
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
                    <i class="fa fa-code-fork"></i>
                    <h3 class="box-title capitalize">{{ __('levels') }}</h3>
                    <a href="{{route('levels.create')}}" role="button" class="btn btn-primary btn-xs btn-flat pull-right">
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
                <div class="box-body">
                    <table id="table" class="table table-hover orderable">
                        <thead>
                        <tr class="capitalize">
                            <th>#</th>
                            <th>{{ __('ID') }}</th>
                            <th>{{__('name')}}</th>
                            <th>{{__('key')}}</th>
                            <th>{{__('active')}}</th>
                            <th class="right-align">{{__('action')}}</th>
                        </tr>
                        </thead>
                        <tbody id="tablecontents">
                        @forelse ($game->levels as $row)
                            <tr class="row1" data-id="{{ $row->id }}">
                                <td>
                                  <div style="color:rgb(124,77,255); padding-left: 10px; float: left; font-size: 20px; cursor: pointer;" title="change display order">
                                  <i class="fa fa-ellipsis-v"></i>
                                  <i class="fa fa-ellipsis-v"></i>
                                  </div>
                                </td>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>
                                    <a href="{{route('levels.edit', $row->id)}}">
                                        {{ $row->name }}
                                    </a>
                                </td>
                                <td>{{ $row->key }}</td>
                                <td>
                                    @if($row->active)
                                        <span class="btn btn-success"><i class="fa fa-check"></i></span>
                                    @else
                                        <span class="btn btn-danger"><i class="fa fa-ban"></i></span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('levels.edit', $row->id)}}"
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
@stop

@push('scripts-footer')

  <!-- jQuery UI -->
  <script type="text/javascript" src="//code.jquery.com/ui/1.12.1/jquery-ui.js" ></script>
 
  <!-- Datatables Js-->
  <script type="text/javascript" src="//cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>

  <script type="text/javascript">
  $(function () {
    $("#table").DataTable();

    $( "#tablecontents" ).sortable({
      items: "tr",
      cursor: 'move',
      opacity: 0.6,
      update: function() {
          sendOrderToServer();
      }
    });

    function sendOrderToServer() {

      var order = [];
      $('tr.row1').each(function(index,element) {

        order.push({
          id: $(this).attr('data-id'),
          position: index+1
        });
      });

      $.ajax({
        type: "POST", 
        dataType: "json", 
        url: "{{ url('admin/levelOrder') }}",
        data: {
          order:order,
          _token: '{{csrf_token()}}'
        },
        success: function(response) {
            if (response.status == "success") {
              console.log(response);
            } else {
              console.log(response);
            }
        }
      });

    }
  });

</script>

@endpush