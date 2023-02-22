@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
  <h1>
    Edit Member
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('users_list') }}">Members List</a></li>
    <li class="active">Update Member</li>
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
    @can('admin-view')
      <a href="{{ route('users_list') }}" class="btn btn-primary"><i class="fa fa-users" aria-hidden="true"></i> All Members</a>
    @endcan
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Update Member</h3>

          <div class="box-tools pull-right">
            
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if(isset($user_data) && !empty($user_data)){{ route('upd_user', array('utid' => $user_data->timestamp_id)) }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}


           <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Company Name : <em>*</em></label>
                <input type="text" name="member_company" class="form-control" placeholder="Enter Company Name" value="@if(isset($user_data) && !empty($user_data)){{ $user_data->member_company }}@endif">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Contact Name : <em>*</em></label>
                <input type="text" name="contact_name" class="form-control" placeholder="Enter Contact Name" value="@if(isset($user_data) && !empty($user_data)){{ $user_data->contact_name }}@endif">
              </div>
            </div>
          </div>


          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Email-id : <em>*</em></label>
                <input type="email" name="email_id" class="form-control" placeholder="Enter Email-Id" value="@if(isset($user_data) && !empty($user_data)){{ $user_data->email_id }}@endif">
                @if($errors->has('email_id'))
                <span class="roy-vali-error"><small>{{$errors->first('email_id')}}</small></span>
                @endif
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Contact Number : </label>
                <input type="text" name="contact_no" class="form-control onlyNumber" placeholder="Enter Contact Number" value="@if(isset($user_data) && !empty($user_data)){{ $user_data->contact_no }}@endif">
              </div>
            </div>
          </div>



          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Mobile : <em>*</em></label>
                <input type="text" name="mobile" class="form-control" placeholder="Enter Mobile No."  autocomplete="off" value="@if(isset($user_data) && !empty($user_data)){{ $user_data->mobile }}@endif">
                
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Stage : </label>
                 <select name="stage_id" class="form-control">
                  <option>Select Startup Stage</option>
                  @if(!empty($stageList))
                   @foreach($stageList as $s)
                  <option value="{{ $s->id }}" @if( isset($user_data) && $user_data->stage_id == $s->id ) selected="selected" @endif>{{ $s->stage_name }}</option>
                  @endforeach
                  @endif
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Country : <em>*</em></label>
                <input type="text" name="country" class="form-control" placeholder="Enter Country"  autocomplete="off" value="@if(isset($user_data) && !empty($user_data)){{ $user_data->country }}@endif">
                
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>City : </label>
                <input type="text" name="city" class="form-control" placeholder="Enter City" autocomplete="off" value="@if(isset($user_data) && !empty($user_data)){{ $user_data->city }}@endif">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Website : <em>*</em></label>
                <input type="text" name="website" class="form-control" placeholder="Enter Website"  autocomplete="off" value="@if(isset($user_data) && !empty($user_data)){{ $user_data->website }}@endif">
                
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Legal status : </label>
                <select name="legal_status" class="form-control">
                  <option>Select Legal status</option>
                  <option value="pvt" @if( isset($user_data) && $user_data->legal_status =='pvt' ) selected="selected" @endif>Pvt Ltd</option>
                  <option value="partnership" @if( isset($user_data) && $user_data->legal_status == 'partnership' ) selected="selected" @endif>Partnership</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Profile Info : <em>*</em></label>
                <input type="text" name="profile_info" class="form-control" placeholder="Enter Profile Info"  autocomplete="off" value="@if(isset($user_data) && !empty($user_data)){{ $user_data->profile_info }}@endif">
                
              </div>
            </div>
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>User Image :</label>
                    <input type="file" name="image" id="user_image">
                    <span class="roy-vali-error" id="ar-user_image-err"></span>
                  </div>
                </div>
                <div class="col-md-6" style="text-align: right;">
                  <div class="form-group">
                    @if(isset($user_data) && !empty($user_data) && $user_data->image != '' && $user_data->image != null)
                      @php
                      $imageURL = asset('public/uploads/user_images/thumb/'.$user_data->image);
                      @endphp
                      <img src="{{ $imageURL }}" id="user_image_preview" class="ar_img_preview" data="{{ $imageURL }}">
                    @else
                      <img src="{{ asset('public/images/user-avatar.png') }}" id="user_image_preview" class="ar_img_preview" 
                      data="{{ asset('public/images/user-avatar.png') }}">
                    @endif
                    <i class="fa fa-times base-red libtn" id="user_image_rm"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Industry : <em>*</em></label>
                 <select name="industry_id[]" class="form-control" multiple="">
                    <!-- <option>Select Industry Category</option> -->
                    @if(!empty($industryList))
                     @foreach($industryList as $s)
                    <option value="{{ $s->id }}" @if( isset($memberBusiness) && in_array($s->id, $memberBusiness)) selected="selected" @endif>{{ $s->industry_category }}</option>
                    @endforeach
                    @endif
                  </select>
              </div>
            </div>
            <div class="col-md-6">
            </div> 
          </div>


          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Create User">
              </div>
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
<script type="text/javascript">
$( function() {
  $("body").on('keypress', '.onlyNumber', function(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;
    return true;
  });
  $('#role_ids').select2({
    placeholder: "Select a Role(s)"
  });
});
$.validator.addMethod('logosize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param)
}, 'File size must be less than 2mb.');
$('#frmx').validate({
    errorElement: 'span',
    errorClass : 'roy-vali-error',
    rules: {

      'role_ids[]': {
        required: true
      },
      first_name: {
        required: true,
        minlength: 3
      },
      last_name: {
        required: true,
        minlength: 2
      },
      email_id: {
        required: true,
        email: true
      },
      contact_no: {
        maxlength: 12,
        digits: true
        //number: true
      },
      status: {
        required: true
      },
      image: {
        extension: "jpg|jpeg|png|gif|svg",
        logosize: 2000000,
      }
    },
    messages: {

      'role_ids[]': {
        required: 'Please Select Role.'
      },
      first_name: {
        required: 'Please Enter First Name.'
      },
      last_name: {
        required: 'Please Enter Last Name.'
      },
      email_id: {
        required: 'Please Enter Email-id.',
        email: 'Please Enter Valid Email-id.'
      },
      image: {
        extension: 'Please upload any image file.'
      }
    }
});
$(function(){
    
$('.libtn').hide();
$("#user_image").change('click',function(){
    Ari_USER_IMAGE_Preview(this);
});
    
function Ari_USER_IMAGE_Preview(input_fileupload)
{
    if(input_fileupload.files && input_fileupload.files[0])
    {
        $('#user_image_rm').show();
        var fs=input_fileupload.files[0].size;
        if(fs<=2000000)
        {
            var fileName=input_fileupload.files[0].name;
            var ext = fileName.split('.').pop().toLowerCase();
            if(ext=="jpg" || ext=="png" || ext=="jpeg" || ext=="gif")
            {
                var reader=new FileReader();
                reader.onload = function (e) 
                {
                    $('#user_image_preview').attr('src', e.target.result);
                    $("#ar-user_image-err").html('');
                }
                
                reader.readAsDataURL(input_fileupload.files[0]);
            }
            else
            {
                //alert('Upload .jpg,.png Image only');
                $("#ar-user_image-err").html('Choose only jpg, png, gif image.');
            }
        }
        else
        {
            //alert('Upload Less Than 200KB Photo');
            $("#ar-user_image-err").html('Choose less than 2mb image size.');
        }
    }
}

$('#user_image_rm').on('click', function() {
  $('#user_image_preview').attr('src', $('#user_image_preview').attr('data'));
  $(this).hide();
  $("#ar-user_image-err").html('');
  $('#user_image').val('');
  $('#user_image-error').hide();
});
});
</script>
@endpush