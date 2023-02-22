@extends('dashboard.layouts.app')



@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($fileCat))
    Edit Startup Business Vertical
    @else
    Add Startup Business Vertical
    @endif
    <!--small>it all starts here</small-->
  </h1>
 <!--  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('allFlCats') }}">All Startup Business Verticals</a></li>
    @if(isset($fileCat))
    <li class="active">Edit Startup Business Verticals</li>
    @else
    <li class="active">Add Startup Business Verticals</li>
    @endif
  </ol> -->
</section>
@endsection

@section('content')

<form name="frm" id="frmx" action="@if( isset($fileCat) ){{ route('updIndustryCats', array('id' => $fileCat->id)) }}@else{{ route('sveIndustryCats') }}@endif" method="post" enctype="multipart/form-data">
{{ csrf_field() }}

<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div class="row">
    <div class="col-md-6">
      <a href="{{ route('allIndustryCats') }}" class="btn btn-primary"> All Startup Business Vertical</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <!-- <h3 class="box-title">@if(isset($fileCat)) Edit Industry Category @else Add Industry Category @endif</h3> -->

          <div class="box-tools pull-right">
            
          </div>
        </div>
        <div class="box-body">
          
          <!-- <div class="row">
            
          </div> -->
          <!-- <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Short Description : </label>
                <textarea name="description" class="form-control">@if( isset($fileCat) ){{ $fileCat->description }}@endif</textarea>
              </div>
            </div>
          </div> -->

          <!-- <div class="row">
          	<div class="col-md-10">
          		<div class="form-group">
          			<label>Select Parent Category :</label>
          			<select name="parent_category_id" class="form-control select2">
          				<option value="0">-SELECT CATEGORY-</option>
          				@if( isset($allFCats) )
          					@foreach( $allFCats as $fct )
          					<option value="{{ $fct->id }}" @if( isset($fileCat) && $fileCat->parent_category_id == $fct->id ) selected="selected" @endif>{{ $fct->name }}</option>
          					@endforeach
          				@endif
          			</select>
          		</div>		
          	</div>
          </div> -->
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Vertical : <em>*</em></label>
                <input type="text" name="industry_category" id="catName" class="form-control" placeholder="Enter Startup Business Vertical" value="@if( isset($fileCat) ){{ $fileCat->industry_category }}@endif">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Status : </label>
                <select name="status" class="form-control">
                  <option value="1" @if( isset($fileCat) && $fileCat->status == '1' ) selected="selected" @endif>Active</option>
                  <option value="2" @if( isset($fileCat) && $fileCat->status == '2' ) selected="selected" @endif>Inactive</option>
                </select>
              </div>
            </div>
          </div>
            <!-- <div class="col-md-2">
              <div class="form-group">
                <label>Display Order : </label>
                <input type="text" name="display_order" class="form-control onlyNumber" style="width: 100px;" @if( isset($fileCat) ) value="{{ $fileCat->display_order }}" @else value="0" @endif>
              </div>
            </div>
          </div> -->
          <div class="row">
            <div class="col-md-2">
              @if( isset($fileCat) )
              <input type="submit" class="btn btn-primary" value="Save Changes" style="width:100%;">
              @else
              <input type="submit" class="btn btn-primary" value="Add Vertical" style="width:100%;">
              @endif
            </div>
          </div>
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

</form>

@endsection

@push('page_js')
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
$('#frmx').validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
  normalizer: function( value ) {
    return $.trim( value );
  },
  rules: {

    industry_category: {
      required: true,
      minlength: 3
    }
  },
  messages: {

    industry_category: {
      required: 'Please Enter Category Name.'
    }
  },
  errorPlacement: function(error, element) {
    element.parent('.form-group').addClass('has-error');
    if (element.attr("data-error-container")) { 
      error.appendTo(element.attr("data-error-container"));
    } else {
      error.insertAfter(element); 
    }
  },
  success: function(label) {
    label.closest('.form-group').removeClass('has-error');
  }
});
</script>

@endpush