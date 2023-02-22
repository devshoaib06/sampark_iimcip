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

    {{-- <div class="col-sm-12">
                    <form name="frmx_src" action="{{ route('front.user.company') }}" method="GET">
                            <div class="postWrap mt-3 search-alpha">
                            <strong>Search User: </strong><br>
                            <!-- <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-search"></i></div>
                            </div>
                            <input type="text" id="autocomplete" name="search" class="form-control" placeholder="Search StartUps" 
                                value="@if(isset($_GET['search'])){{ $_GET['search'] }}@endif"> -->
                               <div>
                                @php

                                $letters = "A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
                               $lettersarray=explode(",",$letters);

                                
                                $letters = array();
                                    for ($i = 0; $i < 26; $i++)
                                    {
                                       @endphp

                                       <a href="{{ route('front.user.company') }}?searr_id={{ $lettersarray[$i]}}@if(isset($_GET['industry_id']) && $_GET['industry_id'] != '')&industry_id={{ $_GET['industry_id'] }}@endif">{{$lettersarray[$i]}}&nbsp;</a>

                                       @php
                                    }
							@endphp

                                   <a href="{{ route('front.user.company') }}@if(isset($_GET['industry_id']) && $_GET['industry_id'] != '')?industry_id={{ $_GET['industry_id'] }}@endif" class="btn btn-primary" style="width:auto;"><b class="text-white"> All</b> </a>
                                    </div>
                                        <br>
										 <hr class="mt-0">
                                        <div class="d-flex align-items-center">
                                             <div class="mr-1"><p><strong>Filter by Industry:</strong></p></div>
                                            <div><select class="form-control" name="industry_id" onchange="if (this.value) window.location.href=this.value">
                                       
                                       <option value="{{ route('front.user.company') }}@if(isset($_GET['searr_id']) && $_GET['searr_id'] != '')?searr_id={{ $_GET['searr_id'] }}@endif">All</option>

                                        @if(isset($industry_category_show) && !empty($industry_category_show) && count($industry_category_show))
                                        @foreach($industry_category_show as $v)

										<option value="{{ route('front.user.company') }}?industry_id={{ $v->id}}@if(isset($_GET['searr_id']) && $_GET['searr_id'] != '')&searr_id={{ $_GET['searr_id'] }}@endif" @if(isset($_GET['industry_id']) && $_GET['industry_id']==$v->id) selected="true" @endif onchange="window.open (this.value)" >{{ $v->industry_category }}</option> 
										
									
                                          
                                        @endforeach

                                        @endif


                                   </select></div>
                                            
									</div>

                                     
                        </div>
                    </form>
                </div>--}}
    
    @if (isset($users) && !empty($users) && count($users))
        @foreach($users as $user)
            @php

                if (isset($user['memberInfo']) && !empty($user['memberInfo'])) {
                    $memberInfo = $user['memberInfo'];

                    $area_of_expertise=$user['area_of_expertise'];
                    //dd($memberInfo);
                }
            @endphp

            
            <div class="col-sm-12">
                <div class="postCard">
                    <div class="postWrap company-post">
                       
                        @php 
                            $profileImage = $defaultImage = $authUserImage = asset('public/front_end/images/profile-pic.png');
                            if(isset($memberInfo) && !empty($memberInfo) && $memberInfo->image != null) {
                                $profileImage = asset('public/uploads/user_images/thumb/'. $memberInfo->image);
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
$http_referer_link = str_replace($removeChar, "", $memberInfo->linkedIn);


@endphp
                        <div class="row allUser">
                           <div class="col-md-3 col-lg-2 order-1 order-lg-1">
                                <div class="alluserImg">
                                <a data-toggle="modal" data-target="#userInfo"  onclick="user_details('{{$memberInfo->contact_name}}', '{{$memberInfo->designation}}',{{$memberInfo->id}},'{{ $http_referer_link }}', '{{$profileImage}}','{{$memberInfo->member_company}}','{{$memberInfo->email_id}}','{{$area_of_expertise}}','{{$memberInfo->about_you}}')">
                        
                                    <img src="{{ $profileImage }}" class="img-fluid" />
                                    </a>
                                </div>
                            </div>
                           
                            <div class="col-md-12 col-lg-7 order-3 order-lg-2">
                                <div class="alluserInfo">
                                    <h3> 

                                  
                                        @if(isset($memberInfo) && !empty($memberInfo))
                                        <!-- <a href="{{ route('front.user.memberProfile', array('member_company' => $memberInfo->slug)) }}" target="_blank">{{$memberInfo->contact_name}}, {{$memberInfo->designation}}</a>  -->
                                        <a data-toggle="modal" data-target="#userInfo"  onclick="user_details('{{$memberInfo->contact_name}}', '{{$memberInfo->designation}}',{{$memberInfo->id}},'{{ $http_referer_link }}', '{{$profileImage}}','{{$memberInfo->member_company}}','{{$memberInfo->email_id}}','{{$area_of_expertise}}','{{$memberInfo->about_you}}')">{{$memberInfo->contact_name}}</a> 
                                        @endif
                                    </h3>
                                    <h4>
                                        
                                        
                                        @if($memberInfo->designation!=''){{$memberInfo->designation}}, @endif{{$memberInfo->member_company}}</h4>
                                <!-- @if(isset($memberInfo) && !empty($memberInfo)) 
                                    <h4><a href="{{ route('front.user.account') }}?search={{ $memberInfo->member_company }}">{{ $memberInfo->member_company }}</a></h4>
                                @endif -->
                                <div class="member-info" style="padding-left: 0; width: 100%;">
                                    <table width="100%" cellpadding="5">
                                        <tbody>
                                        
                                        <tr>
                                            <td valign="top" style="width: 150px;"><i class="fas fa-building text-primary"></i> <strong>Area of Expertise:</strong></td>
                                            <td>
 
                                                {{$area_of_expertise}}
                                            </td>
                                        </tr>
                                        
                                        <!-- <tr>
                                            <td valign="top" style="width: 150px;"><i class="fas fa-pen-nib text-primary"></i> <strong>Designation:</strong></td>
                                            <td><span>{{$memberInfo->designation}}</span></td>
                                        </tr> -->
                                       
                                        <tr>
                                            <td valign="top" style="width: 150px;"><i class="fas fa-at text-primary"></i> <strong>Email ID:</strong></td>
                                            <td><a href="mailto:{{ $memberInfo->email_id }}">{{ $memberInfo->email_id }}</a>
                                            @php
                                                //echo $_SERVER['SERVER_NAME'];
                                            @endphp
                                            </td>
                                        </tr>
                                        
                                        <!-- <tr>
                                            <td valign="top"><i class="fas fa-info-circle text-primary"></i> <strong>Mobile:</strong></td>
                                            <td><span>{{ $memberInfo->mobile }}</span></td>
                                        </tr> -->

                                                                                
                                            
                                        <tr>
                                            <td valign="top" style="width: 150px;"><i class="fab fa-linkedin text-primary"></i> <strong>Profile:</strong></td>
                                            <td><span><a href="//{{ $http_referer_link }}" target="_blank">{{ $memberInfo->linkedIn }}</a></span></td>
                                        </tr>
                                        
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                            </div>
                            <div class="col-md-9 col-lg-3 order-2 order-lg-3">
                                <!-- <div class="row">
                                    <div class="col-3 col-sm-6"><p><i class="fa fa-list-alt"></i> <strong class="text-primary">{{$user['number_of_post']}}</strong> Posts</p></div>
                                    <div class="col-9 col-sm-6"><p><i class="far fa-comment-alt"></i> <strong class="text-primary">{{$user['number_of_reply']}}</strong> Replies</p></div>
                                </div> -->
                                <div class="userReply d-flex">
                                    <a  data-toggle="modal" id="sendMessage{{$memberInfo->id}}" class="send-message" data-target="#myModalmail" onclick="receiver({{$memberInfo->id}})"><i class="fas fa-envelope"></i> Send Message</a>
                                    <ul>
                                        <li><i class="fa fa-list-alt"></i> <strong class="text-primary">{{$user['number_of_post']}}</strong> Posts</li>
                                        <li><i class="far fa-comment-alt"></i> <strong class="text-primary">{{$user['number_of_reply']}}</strong> Replies</li>
                                    </ul>
                                    <!-- <button type="button" data-toggle="modal" data-target="#userInfo">info</button> -->
                                </div>
                            </div>
                        </div>

                         <!-- <div class="postCount">

                            <p>No of Posts: <span class="btn btn-sm btn-primary">{{$user['number_of_post']}}</span>  No of Replies: <span class="btn btn-sm btn-primary">{{$user['number_of_reply']}}</span></p>
                        </div> -->
                    <!-- Comment Start -->
                    
                    <!-- Comment End -->
                </div> </div>
               
            </div>
        @endforeach
       
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
        <img src="http://sampark.iimcip.com//public/front_end/images/pop-up.jpg" class="img-fluid">
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <div class="user-info-img">
            <img src="http://sampark.iimcip.com//public/front_end/images/dummy-user.jpg" id="usr_img" class="img-fluid">
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

    