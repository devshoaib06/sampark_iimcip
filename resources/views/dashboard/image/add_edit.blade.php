@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
<style type="text/css">
li.arimg_box {
  float: left;
  padding: 8px;
  list-style: none;
  text-align: center;
  color: #a3a375;
  font-weight: 600;
}
</style>
@endpush

@section('content_header')
<section class="content-header">
  <h1>
    @if( isset($imgInfo) )
      Edit Media
    @else
      Add Media
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('file_all_imgs') }}">Media Gallery</a></li>
    <li>Media Details</li>
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
      <a href="{{ route('file_all_imgs') }}" class="btn btn-primary"> All Media</a>
      <!-- <input type="submit" class="btn btn-success" value="Add Images"> 
      <a href="javascript:void(0);" class="btn btn-danger" onClick="window.location.reload();">Cancel</a>-->
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Media Information</h3>

          <div class="box-tools pull-right">
            
          </div>
        </div>
        <div class="box-body">

          


          @if( isset($imgInfo) )
          <div class="row">
             <div class="col-md-8">
              @php
              $image_path_org = 'public/uploads/files/media_images/'. $imgInfo->media_filepath;
              $image_path_thumb = 'public/uploads/files/media_images/thumb/'. $imgInfo->media_filepath;
              
              @endphp
              <table class="table table-bordered table-striped">
                <!-- <tr>
                  <th style="width: 200px;">Name : </th>
                  <td style="text-align: left;">{{ $imgInfo->name }}</td>
                </tr>
                <tr>
                  <th style="width: 200px;">Size : </th>
                  <td style="text-align: left;">{{ sizeFilter($imgInfo->size) }}</td>
                </tr> -->
                <tr>
                  <th style="width: 200px;">Extension : </th>
                  <td style="text-align: left;">{{ $imgInfo->extension }}</td>
                </tr>
                @if( file_exists($image_path_thumb) )
                <tr>
                  <th style="width: 200px;">Type : </th>
                  <td style="text-align: left;">{{ File::mimeType( 'public/uploads/files/media_images/'. $imgInfo->media_filepath ) }}</td>
                </tr>
                @endif
                @if( isset($width_o) && isset($height_o) )
                <tr>
                  <th style="width: 200px;">Orininal Dimensions : </th>
                  <td style="text-align: left;">{{ $width_o }} x {{ $height_o }}</td>
                </tr>
                @endif
                @if( isset($width_t) && isset($height_t) )
                <tr>
                  <th style="width: 200px;">Thumb Dimensions : </th>
                  <td style="text-align: left;">{{ $width_t }} x {{ $height_t }}</td>
                </tr>
                @endif
                <tr>
                  <th style="width: 200px;">Uploaded Date : </th>
                  <td style="text-align: left;">{{ date('d F, Y', strtotime( $imgInfo->created_at) ) }}</td>
                </tr>
                @if( $imgInfo->updated_at != null )
                <tr>
                  <th style="width: 200px;">Modified Date : </th>
                  <td style="text-align: left;">{{ date('d F, Y', strtotime( $imgInfo->updated_at) ) }}</td>
                </tr>
                @endif
                <tr>
                  <th style="width: 200px;">Uploaded By : </th>
                  <td style="text-align: left;">
                    @if( isset($imgInfo->userInfo) )
                    {{ $imgInfo->userInfo->first_name }} {{ $imgInfo->userInfo->last_name }}
                    @endif
                  </td>
                </tr>
              </table>
             </div>
             <div class="col-md-4">
               <div class="form-group">


                @if($imgInfo->media_type == '1')
                  <img src="{{ asset('public/uploads/files/media_images/'. $imgInfo->media_filepath) }}" style="width: 60px;" class="img-thumbnail">
                  @endif
                  @if($imgInfo->media_type == '2')
                  
                  <video width="300" controls>
                    <source src="{{ asset('public/uploads/files/media_images/'. $imgInfo->media_filepath) }}" type="video/mp4">
                  </video>

                          
                  <!-- <iframe src="{{ asset('public/uploads/files/media_images/'. $imgInfo->media_filepath) }}" width="300" height="200"></iframe> -->
                  @endif
                <!-- <img src="{{ asset('public/uploads/files/media_images/'. $imgInfo->media_filepath) }}" class="img-thumbnail"> -->
               </div>
             </div>
          </div>
          <hr/>
          
          @endif

          
  <form name="frmImg" id="frmxImg" action="@if( isset($imgInfo) ){{ route('file_img_Upd', array('id' => $imgInfo->id)) }}@else{{ route('file_img_upload') }}@endif" method="post" enctype="multipart/form-data">
  {{ csrf_field() }}
          <div class="row">
            <div class="col-md-12">
            
             
              @if( isset($imgInfo) )
              <div class="increment hdtuto control-group lst col-md-6">
               <div class="form-group">
                 <label>Change Media : </label>
                 <input type="file" name="image" accept="image/*,video/*">
               </div>
               
              
              <input type="hidden" value="" name="alt_title" >
              <input name="title" type="hidden" value="">
            </div>

               @else
               <div class="increment hdtuto control-group lst col-md-6">
               <div class="input-group-btn"> 
                  <button class="btn btn-success" type="button"><i class="fldemo glyphicon glyphicon-plus"></i>Add More Image</button>
                </div>
                <!-- <div class="form-group">
                  <label>Media Type:</label>
                  <select name="media_type" class="form-control select2">
                    <option>Select Media Type</option>
                    <option value="1">Image</option>
                    <option value="2">Video</option>
                  </select>
                </div> -->
                <div class="form-group">
                  <label>Upload Media :</label>
                  <input type="file" name="images[]" multiple="multiple" accept="image/*,video/*" required="required">
                </div>
                
               <input type="hidden" value="" name="alt_title" >
              <input name="title" type="hidden" value="">
               </div>


            <div class="clone hide">
                <div class="hdtuto control-group lst col-md-6">
                    <div class="input-group-btn"> 
                    <button class="remove btn btn-danger" type="button"><i class="fldemo glyphicon glyphicon-remove"></i> Remove</button>
                  </div>
                    <!-- <div class="form-group">
                      <label>Media Type:</label>
                      <select name="media_type" class="form-control select2">
                        <option>Select Media Type</option>
                        <option value="1">Image</option>
                        <option value="2">Video</option>
                      </select>
                    </div> -->
                    <div class="form-group">
                      <label>Upload Media :</label>
                      <input type="file" name="images[]" multiple="multiple" accept="image/*,video/*" required="required">
                    </div>
                   
                   
                    <input type="hidden" value="" name="alt_title" >
                   
                    <input name="title" type="hidden" value="">
                  
                   
                </div>
              </div>
              @endif
              
              
            
            <div class="col-md-6">
               
              

              <div class="form-group">
                <label>Status :</label>
                <select name="status" class="form-control">
                  <option value="1" @if( isset($imgInfo) && $imgInfo->status == '1') selected="selected" @endif>Active</option>
                  <option value="2" @if( isset($imgInfo) && $imgInfo->status == '2') selected="selected" @endif>Inactive</option>
                </select>
              </div>
            
              <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Save Details">
              </div>
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

  $(document).ready(function() {
    $(".btn-success").click(function(){ 
          var lsthmtl = $(".clone").html();
          $(".increment").after(lsthmtl);
    });
    $("body").on("click",".remove",function(){ 
         $(this).parents('.hdtuto').remove();
   });
});


$('#frmxImg').validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
  rules: {
    'name[]': {
      required: true
    }
  },
  messages: {
    'name[]': {
      required: 'Please Enter Image Name.'
    },
  }
});
</script>
@endpush