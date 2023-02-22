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
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if( isset($fileInfo) ){{ route('updFile', array('id' => $fileInfo->id)) }}@else{{ route('uploadFile') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          
          @if( isset($fileInfo) )
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                @if( $fileInfo->extension == 'pdf' )
                <i class="fa fa-file-pdf-o base-red fa-3x" aria-hidden="true"></i>
                @elseif( $fileInfo->extension == 'doc' || $fileInfo->extension == 'docx' )
                <i class="fa fa-file-word-o base-blue fa-3x" aria-hidden="true"></i>
                @elseif( $fileInfo->extension == 'xls' || $fileInfo->extension == 'csv' || $fileInfo->extension == 'xlsx' )
                <i class="fa fa-file-excel-o base-green fa-3x" aria-hidden="true"></i>
                @elseif( $fileInfo->extension == 'ppt' || $fileInfo->extension == 'pptx' )
                <i class="fa fa-file-powerpoint-o base-red" aria-hidden="true"></i>
                @else
                <i class="fa fa-file-text fa-3x" aria-hidden="true"></i>
                @endif
                Extension: {{ $fileInfo->extension }} | Size : {{ sizeFilter($fileInfo->size) }}
                
              </div>
            </div>
          </div>
          @else
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label>Upload File :</label>
                <input type="file" name="file[]" multiple="multiple" accept=".pdf,application/pdf,.csv,text/csv,.doc,.docx,application/msword,.xls,.xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/vnd.msexcel,.ppt,application/vnd.ms-powerpoint,.pptx,application/vnd.openxmlformats-officedocument.presentationml.presentation" required="required">
              </div>
            </div>
          </div>
          @endif

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
                <select name="file_category_id" class="form-control select2">
                  <option value="0">-SELECT CATEGORY-</option>
                  @if( isset($allFlCats) )
                    @foreach( $allFlCats as $fc )
                    <option value="{{ $fc->id }}">{{ $fc->name }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
              <div class="form-group">
                <label>Select Subcategory :</label>
                <select name="file_subcategory_id" class="form-control select2" disabled="disabled">
                  <option value="0">-SELECT SUBCATEGORY-</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Status :</label>
                <select name="status" class="form-control">
                  <option value="1" @if( isset($fileInfo) && $fileInfo->status == '1') selected="selected" @endif>Active</option>
                  <option value="0" @if( isset($fileInfo) && $fileInfo->status == '0') selected="selected" @endif>Inactive</option>
                </select>
              </div>
              <div class="form-group">
                <label>Download Limit :</label>
                <input type="number" name="download_limit" class="form-control" maxlength="4" value="@if( isset($fileInfo) ){{ $fileInfo->download_limit }}@endif">
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
  $('#file_category_id').multiselect({
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
  });
});

</script>
@endpush