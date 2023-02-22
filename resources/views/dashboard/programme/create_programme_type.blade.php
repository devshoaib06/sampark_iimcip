@extends('dashboard.layouts.app')



@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($fileCat))
    Edit Programme
    @else
    Add Programme
    @endif
    <!--small>it all starts here</small-->
  </h1>
 <!--  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('allFlCats') }}">All Company Types</a></li>
    @if(isset($fileCat))
    <li class="active">Edit Company Types</li>
    @else
    <li class="active">Add Company Types</li>
    @endif
  </ol> -->
</section>
@endsection

@section('content')

<form name="frm" id="frmx" action="@if( isset($fileCat) ){{ route('updprogrammeType', array('id' => $fileCat->id)) }}@else{{ route('sveprogrammeType') }}@endif" method="post" enctype="multipart/form-data">
{{ csrf_field() }}

<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div class="row">
    <div class="col-md-6">
      <a href="{{ route('allprogrammeType') }}" class="btn btn-primary"> All Programme</a>
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
          
         
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Programme Name : <em>*</em></label>
                <input type="text" name="sponsor_name" id="catName" class="form-control" placeholder="Enter Programme Name" value="@if( isset($fileCat) ){{ $fileCat->name }}@endif">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Maximum Start up : </label>
                <input type="text" name="programme_max_startup" id="catName" class="form-control" placeholder="Enter Programme Max Startup" value="@if( isset($fileCat) ){{ $fileCat->programme_max_startup }}@endif">
              </div>
            </div>
      
            <div class="col-md-6">
              <div class="form-group">
                <label>Programme Application Start Date : </label>
                <input type="date" name="application_start_date" id="application_start_date" class="form-control" placeholder="Enter  Application Date" value="@if( isset($fileCat) ){{ $fileCat->programme_application_start_date }}@endif">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Programme Application End Date : </label>
                <input type="date" name="application_end_date" id="application_end_date" class="form-control" placeholder="Enter Application End Date" value="@if( isset($fileCat) ){{ $fileCat->programme_application_end_date }}@endif">
              </div>
            </div>
         
            <div class="col-md-12">
              <div class="form-group">
                <label>Programme Brief : </label>
                <input type="text" name="brief" id="brief" class="form-control" placeholder="Enter Programme Brief" value="@if( isset($fileCat) ){{ $fileCat->brief }}@endif">
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label>Programme Start Date : </label>
                <input type="date" name="programme_start_date" id="programme_start_date" class="form-control" placeholder="Enter Programme Start Date" value="@if( isset($fileCat) ){{ $fileCat->programme_start_date }}@endif">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Sponsor : </label>
                <select name="sponsor_id" class="form-control">
                  <option value="">Select</option>
                @if (isset($allActiveSponsor))
                @foreach ($allActiveSponsor as $item)
                   <option value="{{$item->id}}"   @if( isset($fileCat) &&  isset ($fileCat->programmsponsor) &&  $fileCat->programmsponsor->sponsor_id  == $item->id ) selected="selected" @endif  >{{$item->sponsor_name}}</option> 
                @endforeach

                    
                @endif
     
               </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Status : </label>
                <select name="status" class="form-control">
                  <option value="1" @if( isset($fileCat) && $fileCat->status == '1' ) selected="selected" @endif>Active</option>
                  <option value="0" @if( isset($fileCat) && $fileCat->status == '0' ) selected="selected" @endif>Inactive</option>
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
              <input type="submit" class="btn btn-primary" value="Add Programme" style="width:100%;">
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

    company_type: {
      required: true,
      minlength: 3
    }
  },
  messages: {

    company_type: {
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