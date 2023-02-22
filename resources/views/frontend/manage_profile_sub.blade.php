@extends('frontend.layouts.app')
@section('content')
<div class="row">
    @if(Session::has('msg') && Session::has('msg_class'))
    <div class="col-sm-12">
        <div class="postCard">
            <div class="{{ Session::get('msg_class') }}" style="margin-bottom: 0;">
                {{ Session::get('msg') }}
            </div>
        </div>
    </div>
    @endif

 
    <div class="col-sm-12">
        <div class="postCard manage-wrap">
            <div class="postWrap">
                <div class="pwdbox">
                    <h3>Add User</h3>
                    <form name="frm_pfupd" id="frm_pfupd" action="{{ route('front.user.addusract') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                       
                        <div class="row">
                            
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>Name:</label>
                                    <input type="text" class="form-control" name="contact_name"  />
                                </div>
                            </div>

                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>Area of Expertise:</label>
                                    <!-- <input type="text" class="form-control" name="area_of_expertise"  /> -->

                                    <select class="form-control indusCatIds" name="area_of_expertise[]" id="indusCatIds" multiple="multiple"   style="width: 100%;">
                                            <option value="">Select Industry Expertise</option>
                                            @if(isset($industry_expertise) && count($industry_expertise))
                                                @foreach($industry_expertise as $v)
                                                    <option value="{{ $v->id }}" 
                                                    >
                                                            {{ $v->industry_expertise }}</option>
                                                @endforeach
                                            @endif
                                        </select>

                                </div>
                            </div>

                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>Role/Designation:</label>
                                    <input type="text" class="form-control" name="designation"  />
                                </div>
                            </div>
                            
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>Email:</label>
                                    <input type="email" class="form-control" name="email_id"  />
                                </div>
                            </div>

                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>Phone:</label>
                                    <input type="text" class="form-control" name="phone"  />
                                </div>
                            </div>

                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>Image:</label>
                                    <div class="custom-file">
                                        <input type="file" name="image" class="custom-file-input img_inp" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                        <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
										 <label>*image size should be 400x400</label>
                                    </div>
									
                                    <img id="img_thumb" src="/public/front_end/images/no-img.jpg" class="new_user_img">
                                </div>
                            </div>

                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>LinkedIn Profile Link:</label>
                                    <input type="text" class="form-control" name="linkedIn"  />
                                </div>
                            </div>
                           
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>Password:</label>
                                    <input type="password" class="form-control" name="password" id="password" />
                                </div>
                            </div>
                           
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>Confirm Password:</label>
                                    <input type="password" class="form-control" name="password_confirm" />
                                </div>
                            </div>
                            
                            </div>
                            
                        </div>
                        </div>
                       
                    </div>
                    <div class="mt-3">
                        <input type="submit" class="btn btn-primary" value="Add User">
                    </div>
                </div>
                        <div class="row">
                           
                        </div>
                    </form>
                </div>
            </div>
        </div>
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

</div>


<!--Question modal-->
@include('frontend.includes.add_question_modal')

@push('page_js')
<script>

$('#frm_pfupd').validate({
    errorElement: 'span',
    errorClass: 'roy-vali-error',
    rules: {
       
        contact_name: {
            required: true
        },
        password: {
            required: true
        },
        email_id:{
            required: true,
            email: true
        },
        password_confirm : {
                   
            equalTo : "#password"
        }
        
    },
    messages: {
        
        contact_name: {
            required: 'Please enter  name'
        },
        email_id:{
            required: 'Please enter email-id',
            email: 'Please enter valid email-id'
        },
        password:{
            required: 'Please enter password'
        },
        
    },
    errorPlacement: function(error, element) {
        if (element.hasClass('indusCatIds')) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element); 
        }
    }
});
</script>
<script type="text/javascript">
    function readURL(input) {
        let img_file = document.getElementById('inputGroupFile01').files[0];
    if (img_file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#img_thumb').attr('src', e.target.result);
        }
        
        reader.readAsDataURL(input.files[0]); 
        }
    }

    $(".img_inp").change(function() {
        readURL(this);
    });

    $(document).ready(function(){
        $('.img_inp').change(function(e){
            var img_file_name = e.target.files[0].name;
            $('.custom-file-label').text(img_file_name)
        });
    });
            
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".fancybox").fancybox();
        $i=1;
        $('.add-founder').click(function(){
            $('.founder').clone().appendTo('.founder-list').removeAttr('class','founder').addClass('founders'+$i).append('<div class="form-group"><a class="btn remove-founder btn-danger btn-remove" onclick="removeItem('+$i+')">Remove</a></div>');

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
              table_row += '<input type="text" name="buisness_video[]" id="buisness_video_' + new_id + '" class="form-control"  placeholder="Enter YouTube or Vimeo Video Link"/> ';                                 
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
@endsection

    