@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        All Posts
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">All Posts</li>
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
    
      <a href="{{ route('main_post.add') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add New Post</a>
    
    </div>
    <div class="col-md-6">
    </div>
  </div>

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
  <form name="frmx" action="{{ route('main_post.blkAct') }}" method="post">
  {{ csrf_field() }}
    <div class="box-header with-border">
      <h3 class="box-title">All Posts</h3>


    </div>
    <div class="box-body">
      <table class="table table-bordered table-hover table-striped display nowrap ar-datatable" style="width:100%">
        <thead>
          <tr>
            <th style="width:30px;"></th>
            <th style="width:90px;">Action</th>
            <th style="width:45px;">Status</th>

            <th>Post Title</th>
            <th style="width:100px;">Post Info</th>
            <th style="width:100px;">Posted By</th>
            <th style="width:100px;">Post Type</th>
            <th style="width:100px;">Private Member</th>
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
            <td>
                
                <a title="Reply" href="{{ route('post_reply.reply', array('id' => $v->id)) }}"><i class="fa fa-reply fa-2x base-blue"></i> </a>
              
                <a title="Edit" href="{{ route('main_post.edit', array('id' => $v->id)) }}"><i class="fa fa-pencil-square-o fa-2x base-green"></i></a>
              
              
                <a title="Delete" href="{{ route('main_post.delete', array('id' => $v->id)) }}" onclick="return confirm('Are you sure want to Delete ?');"><i class="fa fa-2x fa-trash-o base-red"></i></a>
              
            </td>
            <td>
            
              @if($v->status == '1')
                <a href="{{ route('acInac') }}?id={{ $v->id }}&val=2&tab=main_posts"> 
                  <i class="fa fa-check-circle-o base-green fa-2x" aria-hidden="true"></i> 
                </a>
              @endif
              @if($v->status == '2')
                <a href="{{ route('acInac') }}?id={{ $v->id }}&val=1&tab=main_posts"> 
                  <i class="fa fa-ban base-red fa-2x" aria-hidden="true"></i>
                </a> 
              @endif
            
            </td>
            
            <?php
            if($v->updated_at == null || $v->updated_at == '0000-00-00'){
                $updated_at = '';
            }else{
               $updated_at = date('m-d-Y', strtotime($v->updated_at));
            }
            
            if($v->post_type=='1'){
                $post_type = 'Public';
            }elseif($v->post_type=='2'){
                $post_type = 'Private';
            }
            
            ?>
            
            <td>{{ $v->post_title }}</td>
            <td>{{ $v->post_info }}</td>
            <td>{{ $v->first_name." ".$v->last_name }}</td>
            <td>{{ $post_type }}</td>
            <td>{{ $v->p_first_name." ".$v->p_last_name }}</td>
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