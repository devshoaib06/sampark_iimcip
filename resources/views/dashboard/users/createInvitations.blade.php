@extends('dashboard.layouts.app')


@section('content_header')
<section class="content-header">
  <h1>
    Add Invitations
    <!--small>it all starts here</small-->
  </h1>
  <!-- <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('users_list') }}">Members List</a></li>
    <li class="active">Create Member</li>
  </ol> -->
</section>
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div class="row">
    <div class="col-md-6">
      <a href="{{ route('add_invitations') }}" class="btn btn-primary"><i class="fa fa-users" aria-hidden="true"></i> All Invitations</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <!-- <h3 class="box-title">Create Member</h3> -->

          <div class="box-tools pull-right">
            
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="{{ route('save_invitations') }}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-sm-12 col-md-4">
              <div class="form-group">
                <label>Startup Name : <em>*</em></label>
                <input type="text" name="name" class="form-control" required placeholder="Enter Startup Name">
              </div>
            </div>
             
            <div class="col-md-4">
              <div class="form-group">
                <label>Startup Email-id : <em>*</em></label>
                <input type="email" name="email_id" required class="form-control" placeholder="Enter Email-Id" value="{{ old('email_id') }}" autocomplete="off">
                @if($errors->has('email_id'))
                <span class="roy-vali-error"><small>{{$errors->first('email_id')}}</small></span>
                @endif
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Code : <em>*</em></label>
                <input type="text" name="code" required readonly class="form-control " placeholder="Enter Code" autocomplete="off"  value="{{$code}}">
              </div>
            </div>
               
             </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Send Invitation">
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
function AutoGeneratePassword() 
{
  var length = 8,
      charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
      retVal = "";
  for (var i = 0, n = charset.length; i < length; ++i) {
      retVal += charset.charAt(Math.floor(Math.random() * n));
  }
  return retVal;
}
$( function() {
  $("body").on('keypress', '.onlyNumber', function(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;
    return true;
  });
  $('#pwdToggle').on('click', function() {
    if($('#pwd').attr('type') == 'password') {
      $('#pwd').attr('type', 'text');
      $(this).find('.fa').addClass('fa-eye-slash').removeClass('fa-eye');
    } else {
      $('#pwd').attr('type', 'password');
      $(this).find('.fa').addClass('fa-eye').removeClass('fa-eye-slash');
    }
  });
  $('#pwdToggle2').on('click', function() {
    if($('#pwd2').attr('type') == 'password') {
      $('#pwd2').attr('type', 'text');
      $(this).find('.fa').addClass('fa-eye-slash').removeClass('fa-eye');
    } else {
      $('#pwd2').attr('type', 'password');
      $(this).find('.fa').addClass('fa-eye').removeClass('fa-eye-slash');
    }
  });
  $('#autoPWD').on('click', function() {
    var pw = AutoGeneratePassword();
    $('#pwd').val(pw);
    $('#pwd2').val(pw);
  });
  $('#role_ids').select2({
    placeholder: "Select a Role(s)"
  });
});
$('#frmx').validate({
    errorElement: 'span',
    errorClass : 'roy-vali-error',
    rules: {

      
      email_id: {
        required: true,
        email: true
      },
      contact_name: {
        required: true,
      },
      password: {
        required: true,
        minlength: 6
      },
      password2: {
        required: true,
        minlength: 6
      },
      contact_no: {
        maxlength: 12,
        digits: true
        //number: true
      },
      stage_id: {
        required: true,
      },

    },
    messages: {

      email_id: {
        required: 'Please Enter Email-id.',
        email: 'Please Enter Valid Email-id.'
      },
      password: {
        required: 'Please Enter Password.'
      },
      stage_id: {
        required: 'Stagename is required..'
      },
      contact_name: {
        required: 'Primary Contact is required..'
      }

    },
    errorPlacement: function(error, element) {
      element.parent('.form-group').addClass('has-error');
      if (element.attr("data-error-container")) { 
        error.appendTo(element.attr("data-error-container"));
      } else if (element.hasClass('select2')) {
        error.insertAfter(element.next('span'));
      } else {
        error.insertAfter(element); 
      }
    }
});
$(function () { 
  $('.moduleAll').on('change', function() {
    if($(this).is(':checked')) {
      $('.' + $(this).attr('id')).attr('checked', 'checked');
      $('.' + $(this).attr('id')).prop('checked', true);
    } else {
      $('.' + $(this).attr('id')).removeAttr('checked');
      $('.' + $(this).attr('id')).prop('checked', false);
    }
  });
  $('#allAccess').on('click', function() {
    $('.all-privileges').attr('checked', 'checked');
    $('.moduleAll').attr('checked', 'checked');
    $('.all-privileges').prop('checked', true);
    $('.moduleAll').prop('checked', true);
  });
  $('#rmAllAccess').on('click', function() {
    $('.all-privileges').removeAttr('checked');
    $('.moduleAll').removeAttr('checked');
    $('.all-privileges').prop('checked', false);
    $('.moduleAll').prop('checked', false);
  });
});
</script>
<script type="text/javascript">
    $(document).ready(function() {
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
              table_row += '<label>Image :</label>';
              table_row += ' <div class="custom-file"><input type="file" name="founder_img[]" class="custom-file-input" id="founder_img' + new_id + '" aria-describedby="inputGroupFileAddon02"><label class="custom-file-label" for="inputGroupFile02">Choose file</label></div>';

              table_row += '</div>';
              table_row += '</div>';
              table_row += '<div class="col-md-12">';
              table_row += '<div class="form-group">';
              table_row += '<label>Profile :</label>';
              table_row +=  '<textarea class="form-control custom-textarea" name="founder_profile[]" id="founder_profile_' + new_id + '"></textarea>';

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
              table_row += '<input type="text" name="buisness_video[]" id="buisness_video' + new_id + '" class="form-control" placeholder="Enter YouTube or Vimeo Video Link" value="" /> ';                                 
              table_row += '</div>';
              table_row += '</div>';
              
              table_row += '<div class="col-md-12">';             
              table_row += '<a id="rm-answer-' + new_id + '"  class="btn btn-danger btn-remove-services" onClick="remove_service(\'' + new_id + '\')">';
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
</script>
@endpush