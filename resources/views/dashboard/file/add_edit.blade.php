@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
@endpush


@section('content_header')
<section class="content-header">
  <h1>
    @if( isset($fileInfo) )
      Edit File
    @else
      Add New File
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('allFiles') }}">All Files</a></li>
    <li>Add Files</li>
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
      <a href="{{ route('allFiles') }}" class="btn btn-primary"> All Files</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">File Details</h3>

          <div class="box-tools pull-right">
            
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if( isset($fileInfo) ){{ route('updFile', array('id' => $fileInfo->id)) }}@else{{ route('uploadFile') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}

          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label>File Name : <em>*</em></label>
                <input type="text" name="name" class="form-control" placeholder="Enter File Name" value="@if( isset($fileInfo) ){{ $fileInfo->name }}@endif">
              </div>
              <div class="form-group">
                <label>File Title : <em>*</em></label>
                <input type="text" name="title" class="form-control" placeholder="Enter File Title" value="@if( isset($fileInfo) ){{ $fileInfo->title }}@endif">
              </div>
              <div class="form-group">
                <label>File Caption : </label>
                <textarea name="caption" class="form-control" placeholder="Enter Caption" style="height: 100px;">@if( isset($fileInfo) ){{ $fileInfo->caption }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>File Details : </label>
                <textarea name="details" class="form-control" placeholder="Enter Details" style="height: 100px;">@if( isset($fileInfo) ){{ $fileInfo->details }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Select Category :</label>
                <select name="file_category_id" id="file_category_id" class="form-control select2">
                  <option value="0">-SELECT CATEGORY-</option>
                  @if( isset($allFlCats) )
                    @foreach( $allFlCats as $fc )
                    <option value="{{ $fc->id }}" @if(isset($fileInfo) && isset($fileInfo->getCatSubcat) && !empty($fileInfo->getCatSubcat) && $fileInfo->getCatSubcat->file_category_id == $fc->id) selected="selected" @endif>{{ $fc->name }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
              <div class="form-group">
                <label>Select Subcategory :</label>
                <select name="file_subcategory_id" id="file_subcategory_id" class="form-control select2">
                  <option value="0">-SELECT SUBCATEGORY-</option>
                  @if(isset($fileInfo) && isset($fileInfo->getCatSubcat))
                    @if( isset($seleSubCats) )
                      @foreach( $seleSubCats as $fc )
                        <option value="{{ $fc->id }}" @if( $fc->id == $fileInfo->getCatSubcat->file_subcategory_id ) selected="selected" @endif>{{ $fc->name }}</option>
                      @endforeach
                    @endif
                  @endif
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Status :</label>
                <select name="status" class="form-control">
                  <option value="1" @if( isset($fileInfo) && $fileInfo->status == '1') selected="selected" @endif>Active</option>
                  <option value="2" @if( isset($fileInfo) && $fileInfo->status == '2') selected="selected" @endif>Inactive</option>
                </select>
              </div>
              <div class="form-group">
                <label>upload File : <em>*</em></label>
                <input type="file" name="main_file" @if(!isset($fileInfo)) required="required" @endif>
                @if( isset($fileInfo) && $fileInfo->file != '' )
                <a href="{{ asset('public/uploads/files/media_files/'. $fileInfo->file) }}" download>
                Download File <i class="fa fa-download" aria-hidden="true"></i></a>
                | <a href="{{ route('flDD') }}?id={{ $fileInfo->id }}&field=file" class="delfiledata" onclick="return confirm('Are you sure to delete this file ?')"><i class="fa fa-times base-red" aria-hidden="true"></i></a>
                @endif
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <input type="submit" class="btn btn-primary" value="Save All">
            </div>
          </div>
          </form>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
    </div>
  </div>

</section>

@endsection

@push('page_js')
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.js') }}"></script>
<script type="text/javascript">
$('#frmx').validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  rules: {
    "name": {
      required: true
    },
    "title": {
      required: true
    }
  },
  messages: {
    "name": {
      required: 'Please Enter File Name.'
    },
    "title": {
      required: 'Please Enter File Title.'
    }
  }
});

$( function() {
  
  /*$('#file_category_id').multiselect({
    buttonWidth : '100%',
    includeSelectAllOption : true,
    nonSelectedText: 'Select Categories',
    enableFiltering: true,
    filterPlaceholder: 'Search Categories..',
    enableCaseInsensitiveFiltering: true,
    //enableClickableOptGroups: true,
    //enableCollapsibleOptGroups: true,
    selectAllText: 'All Categories',
    maxHeight: 300
  });*/

  $('#file_category_id').on('change', function() {
    $.ajax({
      type : "POST",
      url : "{{ route('ajxMediaLdFlSCats') }}",
      data : {
        "cat_id" : $(this).val(),
        "_token" : "{{ csrf_token() }}"
      },
      beforeSend: function() {
        $('#file_category_id').attr('disabled', 'disabled');
        $('#file_subcategory_id').attr('disabled', 'disabled');
      },
      success: function(scatJson) {
        $('#file_category_id').removeAttr('disabled', 'disabled');
        $('#file_subcategory_id').removeAttr('disabled', 'disabled');
        var datArr = JSON.parse(scatJson);
        var datArrLen = datArr.length;
        if( datArrLen > 0 ) {
          var optHTML = '<option value="0">SELECT SUBCATEGORY</option>';
          for( var i = 0; i < datArrLen; i++ ) {
            optHTML += '<option value="'+ datArr[i].id +'">'+ datArr[i].name +'</option>';
          }
          $('#file_subcategory_id').html( optHTML );
        } else {
          $('#file_subcategory_id').html( '<option value="0">SELECT SUBCATEGORY</option>' );
        }
      }
    });
  } );
});

</script>
@endpush