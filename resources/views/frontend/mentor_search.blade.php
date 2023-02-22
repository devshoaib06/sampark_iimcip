@extends('frontend.layouts.app')
@section('content')

	@if (isset($mentor) && !empty($mentor))
	<div class="col-sm-12">
        <div class="postCard">
            <div class="postWrap company-post">
				  @php 
                            $profileImage = $defaultImage = $authUserImage = asset('public/front_end/images/profile-pic.png');
                            if(isset($mentor) && !empty($mentor) && $mentor->image != null) {
                                $profileImage = asset('public/uploads/user_images/thumb/'. $mentor->image);
                            }
                            if(Auth::user()->image != null) {
                                $authUserImage = asset('public/uploads/user_images/thumb/'. Auth::user()->image);
                            }
                            else
                            {
                                $authUserImage = asset('public/uploads/user_images/thumb/entreprenaur_photo/noimage.png');
                            }

                            //echo $profileImage;die;
                        @endphp

                        @php

						$removeChar = ["https://", "http://"];
						$http_referer_link = str_replace($removeChar, "", $mentor->linkedIn);


						@endphp
						
						 <div class="row allUser">
                           <div class="col-md-4 col-lg-3 order-1 order-lg-1">
                                <div class="alluserImg">
                                <a data-toggle="modal" data-target="#userInfo"  onclick="user_details('{{$mentor->contact_name}}', '{{$mentor->designation}}',{{$mentor->id}},'{{ $http_referer_link }}', '{{$profileImage}}','{{$mentor->member_company}}','{{$mentor->email_id}}','{{$mentor->area_of_expertise}}','{{$mentor->about_you}}')">
                        
                                    <img src="{{ $profileImage }}" class="img-fluid" />
                                </a>
                                </div>
                            </div>
							
							 <div class="col-md-12 col-lg-6 order-3 order-lg-2">
                                <div class="alluserInfo">
                                    <h3> 

                                  
                                        @if(isset($mentor) && !empty($mentor))
                                      
                                        <a data-toggle="modal" data-target="#userInfo"  onclick="user_details('{{$mentor->contact_name}}', '{{$mentor->designation}}',{{$mentor->id}},'{{ $http_referer_link }}', '{{$profileImage}}','{{$mentor->member_company}}','{{$mentor->email_id}}','{{$mentor->area_of_expertise}}','{{$mentor->about_you}}')">{{$mentor->contact_name}}</a> 
                                        @endif
                                    </h3>
                                    <h4>
                                        
                                        
                                        @if($mentor->designation!=''){{$mentor->designation}}, @endif{{$mentor->member_company}}</h4>
                             
                                <div class="member-info" style="padding-left: 0; width: 100%;">
                                    <table width="100%" cellpadding="5">
                                        <tbody>
                                        
                                        <tr>
                                            <td valign="top" style="width: 150px;"><i class="fas fa-building text-primary"></i> <strong>Area of Expertise:</strong></td>
                                            <td>
												{{$mentor->area_of_expertise}}
                                                
                                            </td>
                                        </tr>
                                        
                                     
                                       
                                        <tr>
                                            <td valign="top" style="width: 150px;"><i class="fas fa-at text-primary"></i> <strong>Email ID:</strong></td>
                                            <td><a href="mailto:{{ $mentor->email_id }}">{{ $mentor->email_id }}</a></td>
                                        </tr>
                                        
                                      
                                                                                
                                            
                                        <tr>
                                            <td valign="top" style="width: 150px;"><i class="fab fa-linkedin text-primary"></i> <strong>Profile:</strong></td>
                                            <td><span><a href="//{{ $http_referer_link }}" target="_blank">{{ $mentor->linkedIn }}</a></span></td>
                                        </tr>
                                        
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                            </div>
							
							


			</div>
		</div>
	</div>
    @else
    <div class="col-sm-12">
        <div class="postCard">
            <div class="postWrap">
                <div class="postTop">
                    <p>Sorry! No Company User found.</p>
                </div>
            </div>
        </div>
    </div>
    @endif
	
	<!-- Modal -->
        <!-- Modal HTML -->
        <div id="myModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <iframe id="cartoonVideo" width="560" height="315" src="//www.youtube.com/embed/YE7VzlLtp-4" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
</div>

<div id="myModalmail" class="modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered">


  <form name="frm_pfupd" id="frm_pfupd" action="{{ route('front.user.sendMessages') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Message</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
<!--                <label>Message:</label>-->
                <textarea placeholder="Type your message here..." class="txt-mesg form-control" name="message" required="required"></textarea>
                <input type="hidden" class="form-control" name="receiver_id" id="receiver_id" required="required"  value="">
        </div>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>-->
        <button type="submit" class="btn btn-primary">Send Message</button>
      </div>
    </div>
</form>
  </div>
</div>


<!---user info modal-->

 

<div class="modal fade" role="dialog" tabindex="-1" id="userInfo">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header user-info-head">
        <img src="/public/front_end/images/pop-up.jpg" class="img-fluid">
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <div class="user-info-img">
            <img src="/public/front_end/images/dummy-user.jpg" id="usr_img" class="img-fluid">
        </div>
        <div class="user-info-details">
            <h2 id="user_name">Subhrangshu Sanyal</h2>
            <div align="center">
           <b> <span id="designation">Finance, Asset Management</span><span></span> <span id="company_name"></span></b>
           <br>
           <span id="area_of_expertise"></span> 
<!--            <span id="email_id"></span>-->
            </div>
            <input type="hidden" class="form-control" name="r_id" id="r_id" required="required"  >
            
            <p id="about_me"></p>
             
            <div style="display: flex;margin-left: 170px;">
            <a class="user-messege" style="margin: 2px;" href="#" onclick="messagePop()">Message</a>  <a  style="margin: 2px;" id="ahref" class="user-messege" target="_blank" >Profile</a>
                
                 
            </div>
              
        </div>
      </div>
    </div>
  </div>
</div>



<!--Question modal-->
@include('frontend.includes.add_question_modal')

@endsection



@push('page_js')
<script>
 
function receiver(id){
    $("#receiver_id").val(id);
}

function user_details(user_name,designation,r_id,hrefval,userimage,company_name,email_id,area_of_expertise,about_me){
    
//    if(designation!=''){
//        designation=designation+', ';
//    }
 
    $("#user_name").html(user_name);
    $("#designation").html(designation);
    $("#r_id").val(r_id);
    $("#company_name").html(company_name);
    $("#email_id").html(email_id);
    $("#area_of_expertise").html(area_of_expertise);
    $("#about_me").html(about_me);
    
    
    
      
    $("#ahref").attr("href", "//"+hrefval);

    $('#usr_img').attr('src', userimage);
 
}

function messagePop( ){

    $("#userInfo").modal('hide');

    r_id = $("#r_id").val();
    $("#sendMessage"+r_id).click();
 
}
 
$(document).ready(function() { 

    $(".fancybox").fancybox();
    $('body').on('click', '.addCommentBTN', function() { 
        var postID = $.trim($(this).data('postid'));
        var parentID = $.trim($(this).data('postpid'));
        var commentTxt = $('#post_comment_' + postID + '-' + parentID).val();

        var videoText = $('#post_video_' + postID + '-' + parentID).val();

        var formData = new FormData;
        formData.append('_token', "{{ csrf_token() }}");

        var image_id ='post_image_' + postID + '-' + parentID;


        var image_files =$("input#"+image_id)[0].files;
        //console.log(files.length);

        if(image_files.length >4)
        {
            var err_image ='error_image_' + postID + '-' + parentID;
            $('#'+err_image).text("Maximum 4 Images Allowed");
            return false;
        }

        for(var i=0;i<image_files.length;i++){
            formData.append("images[]", image_files[i], image_files[i]['name']);

        }

        /*var video_id ='post_video_' + postID + '-' + parentID;

        var video_files =$("input#"+video_id)[0].files;
       

       
        for(var i=0;i<video_files.length;i++){
            formData.append("video", video_files[i], video_files[i]['name']);

        }*/


        formData.append("post_id",postID);
        formData.append("reply_text",commentTxt);
        formData.append("replied_on",parentID);
        formData.append("video_url",videoText);

        if (postID != '' && parentID != '' && commentTxt != '') {
            //alert("entert");return false;
            $.ajax({
                url: "{{ route('front.user.addComment') }}",
                method: 'POST',
                data: formData,
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                
                beforeSend: function() {
                    $('#addCommentBTN_' + postID + '-' + parentID).attr('disabled', 'disabled');
                    $('#addCommentBTN_' + postID + '-' + parentID).css({
                        'background-color': '#cecece'
                    });
                },
                success: function(data) {
                    if (data.status == 'ok') {
                        var ctxt = '';
                        var rtxt = '';
                        $('#addCommentBox_' + postID + '-' + parentID).prepend(data.repComtHtml);
                        $('#post_comment_' + postID + '-' + parentID).val('');
                         $('#post_image_' + postID + '-' + parentID).val('');
                          $('#post_video_' + postID + '-' + parentID).val('');
                        if (data.commentCount > 1) {
                            ctxt = 'Comments';
                        } else {
                            ctxt = 'Comment';
                        }
                        $('#commCount_' + postID).text(data.commentCount + ' ' + ctxt);
                        if (data.replyCount > 1) {
                            rtxt = 'Replies';
                        } else {
                            rtxt = 'Reply';
                        }
                        $('#replyCount_' + postID + '-' + parentID).text(data.replyCount + ' ' + rtxt);
                        $('#addCommentBTN_' + postID + '-' + parentID).removeAttr('disabled');
                        $('#addCommentBTN_' + postID + '-' + parentID).css({
                            'background-color': '#2d75a1'
                        });
                        if (parentID > 0) {
                            $('#replyList_' + postID + '-' + parentID).show();
                        }
                        //console.log(data);
                    }
                }
            });
        }
    } );
    $('.redm').on('click', function() { 
        var _thisID = $(this).attr('id');
        var _conID = _thisID.split('_')[1];
        $('#mincontent_' + _conID).hide();
        $('#fullcontent_' + _conID).show();
    });

    // video preview in browser
    var videoPreview = function(input, placeVideoPreview) {

        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    var videonode = $($.parseHTML('<video><source src=""></video>'))
                    $(videonode).find('source').attr('src', event.target.result).appendTo(placeVideoPreview);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }
    };

    $('.add-video').on('change', function() {
        videoPreview(this, 'video.vid-preview');
        $('.video-preview').css('display','block');
    });
} ); 

    function getComboA(selectObject,id)
    {
        
        imagesPreview(selectObject, 'div.img_preview_'+id);
        document.getElementById("img_preview_"+id).innerHTML = "";
    }

     // Multiple images preview in browser
    function imagesPreview(input, placeToInsertImagePreview) {

        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }
    };

    function openVideoModal(video_url)

    {
         
        $("#cartoonVideo").attr('src', video_url);
            //alert(video_url);
        $("#myModal").modal('show');
    }


    function addFavourate(post_id,user_id)
    {
        var formData = new FormData;

        var status =$("#post_hidden_"+post_id).val();

        

        formData.append('_token', "{{ csrf_token() }}");
        formData.append("post_id",post_id);
        formData.append("user_id",user_id);
        formData.append("status",status);
       

        $.ajax({
                url: "{{ route('front.user.addFavourate') }}",
                method: 'POST',
                data: formData,
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                
                beforeSend: function() {
                    
                },
                success: function(data) {
                    if (data.status == 'ok') {


                        var src1 ="{{asset('public/front_end/images/heart_two.jpg')}}";

                        var src2 ="{{asset('public/front_end/images/heart_one.png')}}";


                        if(status==1)
                        {
                            $('.post_fav_'+post_id+' img').attr("src", src1);

                            $("#post_hidden_"+post_id).val(0);
                        }
                        else
                        {
                            $('.post_fav_'+post_id+' img').attr("src", src2);

                            $("#post_hidden_"+post_id).val(1);
                        }
                        
                        
                    }
                }
            });
    }




</script>
@endpush
