@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        All Reply
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">All Reply</li>
      </ol>
    </section>
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div class="row">
    <div class="col-md-6">
    
    
    
    </div>
    <div class="col-md-6">
    </div>
  </div>

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
  <form name="frmx" action="{{ route('post_reply.blkAct') }}" method="reply">
  {{ csrf_field() }}
    <div class="box-header with-border">
      <h3 class="box-title">All Reply</h3>


    </div>
    <div class="box-body">
      <table class="table table-bordered table-hover table-striped display nowrap ar-datatable" style="width:100%">
        <thead>
          <tr>
            <th style="width:30px;"></th>


            <th>Post Title</th>
            <th style="width:100px;">Post</th>
            <th style="width:100px;">Reply</th>
            <th style="width:100px;">Reply By</th>
            <th style="width:100px;">Reply Of Reply</th>
            <th style="width:100px;">Created</th>
            <th style="width:100px;">Modified</th>
          </tr>
        </thead>
        <tbody>
        @if(isset($alldata))
          @php $sl = 1; @endphp
          
          
          @forelse($alldata as $v)
          <tr>
            <td>
              {{ $sl }}
              
            </td>
            
            
            <?php
            if($v->updated_at == null || $v->updated_at == '0000-00-00'){
                $updated_at = '';
            }else{
               $updated_at = date('m-d-Y', strtotime($v->updated_at));
            }

            
            ?>
            
            <td>{{ $v->post_title }}</td>
            <td>{{ $v->post_info }}</td>
            <td>{{ $v->reply_text }}</td>
            <td>{{ $v->first_name." ".$v->last_name }}</td>
            <td>{{ $v->reply_of_reply }}</td>
            <td>{{ date('m-d-Y', strtotime($v->created_at)) }}</td>
            <td>{{ $updated_at }}</td>
          </tr>
          @php $sl++; @endphp
          @empty
          @endforelse
        @endif
        </tbody>
      </table>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      
    </div>
    <!-- /.box-footer-->
  </form>
  </div>
  <!-- /.box -->

    </section>
@endsection

@push('page_js')
<script type="text/javascript">
$(function() {
  $('.ar-datatable').DataTable({
    "scrollX": true,
    "columnDefs": [ {
      "targets": [ 1, 2, 3 ],
      "orderable": false
    } ]
  });
});
$( function() {
  $("#ckAll").on('click',function(){
    var isCK = $(this).is(':checked');
    if(isCK == true){
      $('.ckbs').prop('checked', true);
      $('#delAll').removeAttr('disabled');
    }
    if(isCK == false){
      $('.ckbs').prop('checked', false);
      $('#delAll').attr('disabled', 'disabled');
    }
    colMark();
    $('#delAll').val('Delete Selected');
  });
  $(".ckbs").on('click', function(){
    var c = 0;
    $(".ckbs").each(function(){
      colMark();
      if($(this).is(':checked')){
        c++;
      }
    });
    if(c == 0){
      $("#ckAll").prop('checked', false);
      $('#delAll').attr('disabled', 'disabled');
    }
    if(c > 0){
      $("#ckAll").prop('checked',true);
      $('#delAll').removeAttr('disabled');
    }
    $('#delAll').val('Delete Selected ('+c+')');
  });
} );
function colMark() {
  $( '.ckbs' ).each(function() {
    if($(this).is(':checked')) {
      $(this).parents('tr').css('background-color', '#ffe6e6');
    } else {
      $(this).parents('tr').removeAttr('style');
    }
  });
}
</script>
@endpush