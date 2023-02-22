@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
  <h1>
    Edit Invitations
    <!--small>it all starts here</small-->
  </h1>
  
</section>  
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div class="row">
    <div class="col-md-6">
   
      <a href="{{ route('add_invitations')}}" class="btn btn-primary"><i class="fa fa-users" aria-hidden="true"></i>Back To List</a>
   
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <!-- <h3 class="box-title">Update Member</h3> -->

          <div class="box-tools pull-right">
            
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if(isset($user_data) && !empty($user_data)){{ route('upd_invitation', array('id' => $user_data->id)) }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}

           <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Startup Name : <em>*</em></label>
                <input type="text" name="startup_name" class="form-control" placeholder="Enter Company Name" value="@if(isset($user_data) && !empty($user_data)){{ $user_data->startup_name }}@endif">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Startup Email-id : <em>*</em></label>
                <input type="email" name="startup_email" class="form-control" placeholder="Enter Email-Id" value="@if(isset($user_data) && !empty($user_data)){{ $user_data->startup_email }}@endif">
                @if($errors->has('startup_email'))
                <span class="roy-vali-error"><small>{{$errors->first('startup_email')}}</small></span>
                @endif
              </div>
            </div>


            <div class="col-md-4">
              <div class="form-group">
                <label>Startup Code : <em>*</em></label>
                <input readonly type="text" name="code" class="form-control" placeholder="Enter Code" value="@if(isset($user_data) && !empty($user_data)){{ $user_data->code }}@endif">
                
              </div>
            </div>

            <!-- <div class="col-md-3">
              <div class="form-group">
                <label>Contact Number : </label>
                <input type="text" name="contact_no" class="form-control onlyNumber" placeholder="Enter Contact Number" value="@if(isset($user_data) && !empty($user_data)){{ $user_data->contact_no }}@endif">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Mobile : <em>*</em></label>
                <input type="text" name="mobile" class="form-control" placeholder="Enter Mobile No."  autocomplete="off" value="@if(isset($user_data) && !empty($user_data)){{ $user_data->mobile }}@endif">
              </div>
            </div> -->
            
          </div>
           
         
          

        

         


          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Save and Update">
              </div>
            </div>
          </div>
          </form>
        </div>
         <!-- Modal -->
        <!-- Modal HTML -->
        <div id="myModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <!-- <h4 class="modal-title">Video</h4> -->
                    </div>
                    <div class="modal-body">
                        <iframe id="cartoonVideo" width="560" height="315" src="//www.youtube.com/embed/YE7VzlLtp-4" frameborder="0" allowfullscreen></iframe>
                    </div>
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
      member_company: {
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
<script type="text/javascript">
    $(document).ready(function() {
      $(".fancybox").fancybox();
        $i=1;
        $('.add-founder').click(function(){
            $('.founder').clone().appendTo('.founder-list').removeAttr('class','founder').addClass('founders'+$i).append('<div class="form-group"><a class="btn remove-founder btn-danger" onclick="removeItem('+$i+')">Remove</a></div>');

            $i++;
        });

        //alert($("#is_raised_invest").val());

        if($("#raise_check").val()==1)
        {
            $("#show_raise").show();
        }
        else
        {
            $("#show_raise").hide();
        }

        

    });

    function removeItem($j){
           //alert($j);
           $(".founders"+$j).remove();
        }
        function clone_answer() {  

              var id = $("#answer_row_count").val();
              var new_id = parseInt(id) + 1;
              $("#answer_row_count").val(new_id);
              var table_row = "";

              table_row += '<div id="answer_sec_' + new_id + '">';
              table_row += '<div class="row">';
              table_row += '<div class="col-md-12">';
              table_row += '<div class="form-group">';
              table_row += '<label>Name : </label>';
              table_row += '<input type="text" name="founder_name[]" id="founder_name_' + new_id + '" class="form-control" value="" /> ';                                 
              table_row += '</div>';
              table_row += '</div>';
              table_row += '<div class="col-md-12">';
              table_row += '<div class="form-group">';
              table_row += '<label>Profile :</label>';
              table_row += '<textarea class="form-control" name="founder_profile[]" id="founder_profile_' + new_id + '"></textarea>';

              table_row += '</div>';
              table_row += '</div>';
              table_row += '<div class="col-md-12">';
              table_row += '<div class="form-group">';
              table_row += '<label>Image :</label>';
              table_row += ' <div class="custom-file"><input type="file" name="founder_img[]" class="custom-file-input" id="founder_img' + new_id + '" aria-describedby="inputGroupFileAddon02"><label class="custom-file-label" for="inputGroupFile02">Choose file</label></div>';



              table_row += '</div>';
              table_row += '</div>';
              table_row += '<div class="col-md-12">';             
              table_row += '<a id="rm-answer-' + new_id + '"  class="btn btn-danger btn-remove" onClick="remove_answer(\'' + new_id + '\')">';
              table_row += 'Remove';      
              table_row += '</div>';
              table_row += '</div>';
              table_row += '</div>';    


              $("#answer_sec").append(table_row);

    }
    function remove_answer(id) {

      
        $("#answer_sec_" + id).remove();
      

    }
    function clone_service() {  

              var id = $("#answer_row_count").val();
              var new_id = parseInt(id) + 1;
              $("#answer_row_count").val(new_id);
              var table_row = "";

              table_row += '<div id="service_sec_' + new_id + '">';
              table_row += '<div class="row">';
              table_row += '<div class="col-md-12">';
              table_row += '<div class="form-group">';
              table_row += '<label>Image :</label>';
              table_row += ' <div class="custom-file"><input type="file" name="buisness_img[]" class="custom-file-input" id="buisness_img_' + new_id + '" aria-describedby="inputGroupFileAddon02"><label class="custom-file-label" for="inputGroupFile02">Choose file</label></div>';



              table_row += '</div>';
              table_row += '</div>';
              table_row += '<div class="col-md-12">';
              table_row += '<div class="form-group">';
              table_row += '<label>Caption : </label>';
              table_row += '<input type="text" name="buisness_caption[]" id="buisness_caption_' + new_id + '" class="form-control" value="" /> ';                                 
              table_row += '</div>';
              table_row += '</div>';
              table_row += '<div class="col-md-12">';
              table_row += '<div class="form-group">';
              table_row += '<label>Video : </label>';
              table_row += '<input type="text" name="buisness_video[]" id="buisness_video_' + new_id + '" class="form-control" placeholder="Enter YouTube or Vimeo Video Link"  value="" /> ';                                 
              table_row += '</div>';
              table_row += '</div>';
              /*table_row += '<div class="col-md-12">';
              table_row += '<div class="form-group">';
              table_row += '<label>Website :</label>';
              table_row += '<textarea class="form-control" name="buisness_website[]" id="buisness_website_' + new_id + '"></textarea>';



              table_row += '</div>';
              table_row += '</div>';*/
              
              table_row += '<div class="col-md-12">';             
              table_row += '<a id="rm-answer-' + new_id + '"  class="btn btn-danger btn-remove" onClick="remove_service(\'' + new_id + '\')">';
              table_row += 'Remove';      
              table_row += '</div>';
              table_row += '</div>';
              table_row += '</div>';    


              $("#service_sec").append(table_row);

    }

    function remove_service(id) {

      
        $("#service_sec_" + id).remove();
      

    }

    function clone_video() {  

        

              var id = $("#answer_row_count").val();
              var new_id = parseInt(id) + 1;
              $("#answer_row_count").val(new_id);
              var table_row = "";

              table_row += '<div id="video_sec_' + new_id + '">';
              table_row += '<div class="row">';
               table_row += '<div class="col-md-12">';
              table_row += '<div class="form-group">';
              table_row += '<label>Link : </label>';
              table_row += '<input type="text" name="company_video[]" id="company_video_' + new_id + '" class="form-control"  placeholder="Enter YouTube or Vimeo Video Link"/> ';                                 
              table_row += '</div>';
              table_row += '</div>';
              /*table_row += '<div class="col-md-12">';
              table_row += '<div class="form-group">';
              table_row += '<label>Website :</label>';
              table_row += '<textarea class="form-control" name="buisness_website[]" id="buisness_website_' + new_id + '"></textarea>';



              table_row += '</div>';
              table_row += '</div>';*/
              
              table_row += '<div class="col-md-12">';             
              table_row += '<a id="rm-answer-' + new_id + '"  class="btn btn-danger btn-remove" onClick="remove_video(\'' + new_id + '\')">';
              table_row += 'Remove';      
              table_row += '</div>';
              table_row += '</div>';
              table_row += '</div>';    

              $("#video_sec").append(table_row);

    }

    function remove_video(id) {

      
        $("#video_sec_" + id).remove();
      

    }

    function change_hidden_answer_type_value(id){
    
    if(id == 1){
        $("#show_raise").show();        
    }else{
        $("#show_raise").hide();        
    }



    

}  

 function openVideoModal(video_url)

    {
         
        $("#cartoonVideo").attr('src', video_url);
            //alert(video_url);
        $("#myModal").modal('show');
    } 
</script>
@endpush