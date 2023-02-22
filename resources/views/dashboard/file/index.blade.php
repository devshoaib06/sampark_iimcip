@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        All Files
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('addFile') }}">Add New File</a></li>
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
      <a href="{{ route('addFile') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add New File</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div class="box-header with-border">
      <form action="" method="get">
      <div class="row">
        <div class="col-md-4">
          <select class="form-control select2" name="category_id">
            <option value="0">File Category</option>
            @if( isset($allFcats) )
              @foreach( $allFcats as $val )
              <option value="{{ $val->id }}" @if( isset($_GET['category_id']) && $_GET['category_id'] == $val->id ) selected="selected" @endif>{{ $val->name }}</option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="col-md-4">
          <input type="text" name="src_txt" class="form-control" placeholder="File Name, Title, Caption" value="@if( isset($_GET['src_txt']) ){{ $_GET['src_txt'] }}@endif">
        </div>
        <div class="col-md-2">
          <select class="form-control" name="status">
            <option value="0">Status</option>
            <option value="1" @if( isset($_GET['status']) && $_GET['status'] == '1' ) selected="selected" @endif>Active</option>
            <option value="2" @if( isset($_GET['status']) && $_GET['status'] == '2' ) selected="selected" @endif>Inactive</option>
          </select>
        </div>
        <div class="col-md-2" style="text-align: right;">
          <input type="submit" class="btn btn-primary" value="Find">
          <a href="{{ route('allFiles') }}" class="btn btn-danger">Cancel</a>
        </div>
      </div>
      </form>
    </div>
    <div class="box-body">
      <form name="frmx4" action="{{ route('media_file_multidel') }}" method="post">
      {{ csrf_field() }}
      <div class="row">
        <div class="col-md-3">
          <input type="submit" class="btn btn-xs btn-danger" value="Delete Selected" disabled="disabled" id="delAll">
        </div>
      </div>
      <div class="row"><div class="col-md-12">
      <table class="table table-bordered table-hover table-striped">
        <thead>
          <tr>
            <th><input type="checkbox" id="ckAll"></th>
            <th style="width: 100px;">Action</th>
            <th>Status</th>
            <th>Name</th>
            <th>Category</th>
            <th>Subcategory</th>
            <th>#</th>
          </tr>
        </thead>
        <tbody>
        @if(isset($allFiles))
          @php $sl = 1; @endphp
          @forelse($allFiles as $fl)
          <tr>
            <td>
              <input type="checkbox" name="fileIds[]" value="{{ $fl->id }}" class="ckbs">
              {{ $sl }}
            </td>
            <td>
              <a href="{{ route('edtFile', array('id' => $fl->id)) }}"><i class="fa fa-pencil-square-o fa-2x base-green"></i></a>
              <a href="{{ route('delFile', array('id' => $fl->id)) }}" onclick="return confirm('Sure To Delete This File ?');"><i class="fa fa-trash-o fa-2x base-red"></i></a>
            </td>
            <td>
              @if($fl->status == '1')
                <a href="{{ route('acInac') }}?id={{ $fl->id }}&val=2&tab=files_master"> 
                  <i class="fa fa-check-circle-o base-green fa-2x" aria-hidden="true"></i> 
                </a>
              @endif
              @if($fl->status == '2')
                <a href="{{ route('acInac') }}?id={{ $fl->id }}&val=1&tab=files_master"> 
                  <i class="fa fa-ban base-red fa-2x" aria-hidden="true"></i>
                </a> 
              @endif
            </td>
            <td>{{ $fl->name }}</td>
            <td>
              @if( isset($fl->getCatSubcat) && !empty($fl->getCatSubcat) )
                @if( isset($fl->getCatSubcat->categoryInfo) && !empty($fl->getCatSubcat->categoryInfo) )
                  {{ $fl->getCatSubcat->categoryInfo->name }}
                @endif
              @endif
            </td>
            <td>
              @if( isset($fl->getCatSubcat) && !empty($fl->getCatSubcat) )
                @if( isset($fl->getCatSubcat->categoryInfo) && !empty($fl->getCatSubcat->subcategoryInfo) )
                  {{ $fl->getCatSubcat->subcategoryInfo->name }}
                @endif
              @endif
            </td>
            <td>
              @if($fl->file != '' && $fl->file != null)
                <a href="{{ asset('public/uploads/files/media_files/' . $fl->file) }}" download><i class="fa fa-download" aria-hidden="true"></i></a>
              @endif
            </td>
          </tr>
          @php $sl++; @endphp
          @empty
          @endforelse
        @endif
        </tbody>
      </table>
      </div></div>
      </form>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      <div class="pull-right"> 
      @if(isset($allFiles) && !empty($allFiles)) {{ $allFiles->appends(request()->query())->links() }} @endif
      </div>
    </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->

    </section>
@endsection

@push('page_js')
<script type="text/javascript">
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
    $('#delAll').val('Delete Selected ('+ c +')');
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