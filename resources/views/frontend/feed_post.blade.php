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
    
    @if (isset($myPosts) && !empty($myPosts) && count($myPosts))
        @foreach($myPosts as $post)
            @php
                if (isset($post->memberInfo) && !empty($post->memberInfo)) {
                    $memberInfo = $post->memberInfo;
                }
            @endphp
            <div class="col-sm-12">
                <div class="postCard">
                    <div class="postWrap">
                        <div class="postTop">
						 <div class="postTopLeft">
                                {{--@if(isset($post->postIndistries) && !empty($post->postIndistries) && count($post->postIndistries))
                                <ul >
                                    @foreach($post->postIndistries as $pi)
                                        @if(isset($pi->industryInfo) && !empty($pi->industryInfo) && $pi->industryInfo->industry_category != '')
                                            <li>
                                                <a href="{{ route('front.user.account') }}?industry={{ base64_encode($pi->industryInfo->id) }}">{{ $pi->industryInfo->industry_category }}</a>
                                            </li>
                                        @endif
                                    @endforeach
                                
                                @endif
                                @if(isset($post->postCategories) && !empty($post->postCategories) && count($post->postCategories))
                                
                                    @foreach($post->postCategories as $pc)
                                        @if(isset($pc->categoryInfo) && !empty($pc->categoryInfo) && $pc->categoryInfo->name != '')
                                            <li>
                                                <a href="{{ route('front.user.account') }}?category={{ base64_encode($pc->categoryInfo->id) }}">{{ $pc->categoryInfo->name }}</a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                                @endif --}}
						</div> 

                           
                            <div class="postTopRight">
                                @php

                                 


                                    $resultFav =DB::table('post_favourates')->where('post_id', '=', $post->id)->where('member_id', '=', Auth::user()->id)->count();





                                    if($resultFav>0)
                                    {
                                        $favourate =1;
                                    }
                                    else
                                    {
                                        $favourate =0;
                                    }



                                  @endphp


                                  <input type="hidden" name="post_hidden_{{$post->id}}" id="post_hidden_{{$post->id}}" value="{{$favourate}}">
                               
                                    <span class="post_fav_{{$post->id}}"><a onclick="addFavourate({{$post->id}},{{Auth::user()->id}})"> 
                                        @if($favourate==0)
                                        <img src="{{asset('public/front_end/images/heart_two.jpg')}}" width="13px">
                                        @else
                                        <img src="{{asset('public/front_end/images/heart_one.png')}}" width="13px">
                                        @endif

                                    </a></span>
                                @if($post->member_id == Auth::user()->id)
                                    <a href="{{ route('front.user.editpost', array('post_id' => $post->id)) }}"><i class="fas fa-edit"></i></a>
                                @endif
                            </div>
                        </div>
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
                        @endphp
                        <div class="postUser">
                            <div class="userImg">
                                <img src="{{ $profileImage }}" class="img-fluid" />
								
								@if(isset($post->postCategories) && !empty($post->postCategories) && count($post->postCategories))
                                
                                    @foreach($post->postCategories as $pc)
                                        @if(isset($pc->categoryInfo) && !empty($pc->categoryInfo) && $pc->categoryInfo->name != '')
                                            <p>
                                                <a href="{{ route('front.user.account') }}?category={{ base64_encode($pc->categoryInfo->id) }}">{{ $pc->categoryInfo->name }}</a>
                                            </p>
                                        @endif
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                            <div class="userInfo">
                                <h3>
                                    @if(isset($memberInfo) && !empty($memberInfo))

										@if($memberInfo->contact_name=='admin')

										<a href="#">Administrator</a> 
																			
										@else
										<a href="{{ route('front.user.memberProfile', array('member_company' => $memberInfo->slug)) }}">{{ $memberInfo->contact_name }}</a> 
																			

										@endif


                                    @endif
                                    <span>{{ date('F d, Y', strtotime($post->created_at)) }}</span>
                                </h3>
                                @if(isset($memberInfo) && !empty($memberInfo)) 
                                    <h4><a href="{{ route('front.user.account') }}?search={{ $memberInfo->member_company }}">{{ $memberInfo->member_company }}</a></h4>
                                @endif
                            </div>
                        </div>
                        <div class="postTitle">

                           

                             
                            <h2>@if($post->is_bookmarked==1)
                                        <img src="{{asset('public/front_end/images/bookmark.png')}}" width="30px">
                                        @endif{{ $post->post_title }}</h2>
                        </div>
                        <div class="postDetails">
                            @php
                           
                                $phtml = html_entity_decode($post->post_info, ENT_QUOTES);
                                $pnohtml = strip_tags($phtml);
                            
                              
                            $url = '@(http)?(s)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
$pnohtml = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $pnohtml);
 
                            
                            @endphp
                            @if(strlen($pnohtml) > 450)
                                <div id="mincontent_{{ $post->id }}">
                                    <p>
                                        {!! str_limit($pnohtml, '450', '...') !!}
                                        <a href="javascript:void(0);" class="redm" id="redm_{{ $post->id }}">View Details</a>
                                    </p>
                                </div>
                                <div id="fullcontent_{{ $post->id }}" style="display: none;">
                                    <p>
                                        {!! html_entity_decode($post->post_info, ENT_QUOTES) !!}
                                    </p>
                                </div>
                            @else
                                <p>{!! html_entity_decode($post->post_info, ENT_QUOTES) !!}</p>
                            @endif

                            @if(!empty($post->video_link))

                                 @php

                                    $video_url = $post->video_link;


                                    $url =videoType($video_url);

                                    if($url=='youtube')
                                    {
                                        $video_id = extractVideoID($video_url);

                                    
                                        $thumbnail = getYouTubeThumbnailImage($video_id);
                                    }
                                    else if($url=='vimeo')
                                    {
                                        $video_id = getVimeoId($video_url);

                                    
                                        $thumbnail = getVimeoThumb($video_id);
                                    }

                                    $post->video_link =getYoutubeEmbedUrl($post->video_link);

                                @endphp

                                <a onclick ="openVideoModal('{{$post->video_link}}')"><img src="{{$thumbnail}}" class="pitch"></a>

                            @endif
                        </div>
                        <div class="postFooter">
                            <ul>
                                <li class="commentLink">
                                    <a href="#"><i class="far fa-comment-alt"></i> 
                                    <span id="commCount_{{ $post->id }}">
                                        @if(isset($post->totalPostComment)){{ count($post->totalPostComment) }}@endif
                                        @if(count($post->totalPostComment) > 1)
                                            Comments
                                        @else
                                            Comment
                                        @endif
                                    </span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Comment Start -->
                    @if(isset($post->postReplies))
                    <div class="commentOuter">
                        <div class="postCommentWrap">
                            <div class="postComment"> <!-- COMMENT :: Level one add comment -->
                              <div class="post-comment-area">
                                <img src="{{ $authUserImage }}" class="img-fluid">
                                <input type="text" id="post_comment_{{ $post->id }}-0" class="form-control input-comment" placeholder="Add a comment...">
                                <input type="button" id="addCommentBTN_{{ $post->id }}-0" data-postid="{{ $post->id }}" data-postpid="0" class="addCommentBTN btn-comment" value="Add comment">               </div>
                                
                                <div class="comment-attachment">
                                 <label for="post_image_{{ $post->id }}-0">Add Images</label>
                                 <input type="file" id="post_image_{{ $post->id }}-0"  class="form-control input-comment add-img" onchange="getComboA(this,'{{ $post->id }}-0')" accept="image/x-png,image/gif,image/jpeg" multiple="true">
                                 <p id="error_image_{{ $post->id }}-0" class="err-msg" style="color: red;"></p>
                                 <label for="post_video_{{ $post->id }}-0">Add Video</label>
                                 <input type="text" id="post_video_{{ $post->id }}-0"  class="form-control input-comment input-url" placeholder="Enter Youtube or Vimeo link" >

                                 

                                
                            </div>
                            
                            <div class="preview-wrap">
                                    <div class="img_preview_{{ $post->id }}-0" id="img_preview_{{ $post->id }}-0">
                                    </div>
                                    <!-- <div class="video-preview">
                                        <video class="vid-preview" width="80" height="40"></video>
                                    </div> -->
                                </div>
                            </div>
                            <div class="commentList">
                                @if(!empty($post->postReplies) && count($post->postReplies))
                                <ul>
                                    <li><div id="addCommentBox_{{ $post->id }}-0"></div></li>
                                    @foreach($post->postReplies as $fpr)
                                    <li>
                                        <div class="commentWrapOuter">
                                            <div class="commentWrap">
                                                <div class="userImg">
                                                    @if(isset($fpr->memberInfo) && !empty($fpr->memberInfo) && $fpr->memberInfo->image != null)
                                                    <a href="{{ route('front.user.memberProfile', array('member_company' => $fpr->memberInfo->slug)) }}">
                                                        <img src="{{ asset('public/uploads/user_images/thumb/'. $fpr->memberInfo->image) }}" class="img-fluid" />
                                                    </a>
                                                    @else
                                                    <a href="@if(isset($fpr->memberInfo) && !empty($fpr->memberInfo)){{ route('front.user.memberProfile', array('member_company' => $fpr->memberInfo->slug)) }}@endif">
                                                        <img src="{{ $defaultImage }}" class="img-fluid" />
                                                    </a>
                                                    @endif
                                                </div>
                                                <div class="userCommemt">
                                                    <h4>
                                                        @if(isset($fpr->memberInfo) && !empty($fpr->memberInfo))
                                                            <a href="{{ route('front.user.memberProfile', array('member_company' => $fpr->memberInfo->slug)) }}">
                                                                {{ $fpr->memberInfo->contact_name }}
                                                            </a>
                                                        @endif
                                                        <small class="ml-2">@if($fpr->created_at != null){{ date('d F, Y', strtotime($fpr->created_at)) }}@endif</small>
                                                    </h4>
                                                    <p>{{ $fpr->reply_text }}</p>
                                                    @if(!empty($fpr->postImages) && count($fpr->postImages))

                                                       <div class="attached-img-wrap">
                                                             <label>Images &amp; Video</label>
                                                             <div class="attached-img">

                                                        @foreach($fpr->postImages as $fpi)

                                                            @php
                                                            if (isset($fpi->media_path) && !empty($fpi->media_path)) {
                                                                $postImage = asset('public/uploads/posts/images/'. $fpi->media_path);
                                                            }
                                                            
                                                            @endphp

                                                             
                                                                <a class="fancybox" rel="group" href="{{ $postImage}}"><img src="{{ $postImage }}" class="pitch"  class="hover-shadow" /></a>
                                                            

                                                        

                                                        @endforeach
                                                        </div>
                                                            </div>
                                                    @endif
                                                     @if(!empty($fpr->video_url))

                                                            @php

                                                                $video_url = $fpr->video_url;


                                                            $url =videoType($video_url);

                                                            if($url=='youtube')
                                                            {
                                                                $video_id = extractVideoID($video_url);

                                                            
                                                                $thumbnail1 = getYouTubeThumbnailImage($video_id);
                                                            }
                                                            else if($url=='vimeo')
                                                            {
                                                                $video_id = getVimeoId($video_url);

                                                            
                                                                $thumbnail1 = getVimeoThumb($video_id);
                                                            }

                                                                
                                                            $fpr->video_url =getYoutubeEmbedUrl($fpr->video_url);



                                                                

                                                                

                                                            @endphp


                                                            @if(isset($thumbnail1))

                                                             <a onclick ="openVideoModal('{{$fpr->video_url}}')"><img src="{{$thumbnail1}}" class="pitch"></a>

                                                             @endif

                                                    @endif
                                                    <ul>
                                                        @if(isset($fpr->childReplies) && count($fpr->childReplies))
                                                            <li class="replyLink"><a href="#">
                                                                {{ count($fpr->childReplies) }} 
                                                                @if(count($fpr->childReplies) > 1)
                                                                    Replies
                                                                @else
                                                                    Reply
                                                                @endif
                                                            </a></li>
                                                        @endif
                                                        <!-- Self comment reply off -->
                                                        <li class="addCommentLink"><a href="#">Add Reply</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="addCommentWrap"> <!-- REPLY :: Level two add comment -->
                                                <div class="addComment">
                                                    <img src="{{ $authUserImage }}" class="img-fluid">
                                                    <input type="text" id="post_comment_{{ $post->id }}-{{ $fpr->id }}" class="form-control input-comment" placeholder="Add a comment...">
                                                    <input type="button" id="addCommentBTN_{{ $post->id }}-{{ $fpr->id }}" data-postid="{{ $post->id }}" data-postpid="{{ $fpr->id }}" class="addCommentBTN add-comment" value="Add comment">
                                                </div>
                                                <div class="comment-attachment">
                                                    <label for="post_image_{{ $post->id }}-{{ $fpr->id }}">Add Images</label>
                                                    <input type="file" id="post_image_{{ $post->id }}-{{ $fpr->id }}"  class="form-control input-comment add-img" onchange="getComboA(this,'{{ $post->id }}-{{ $fpr->id }}')" accept="image/x-png,image/gif,image/jpeg" multiple="true" onchange="getComboA(this)">

                                                    <p id="error_image_{{ $post->id }}-{{ $fpr->id }}" style="color: red;"></p>

                                                    <label for="post_video_{{ $post->id }}-{{ $fpr->id }}">Add Video</label>
                                                    <input type="text" id="post_video_{{ $post->id }}-{{ $fpr->id }}"  class="form-control input-comment input-url" placeholder="Enter Youtube or Vimeo link" >
                                                    
                                                </div>

                                                <div class="preview-wrap">
                                                    <div class="img_preview_{{ $post->id }}-{{ $fpr->id }}" id="img_preview_{{ $post->id }}-{{ $fpr->id }}">
                                                    </div>
                                                    <!-- <div class="video-preview">
                                                        <video class="vid-preview" width="80" height="40"></video>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- For n number of reply , Arindam -> need to check this block -->
                                        @if(isset($fpr->childReplies) && count($fpr->childReplies))
                                        <ul class="replyList">
                                            <li><div id="addCommentBox_{{ $post->id }}-{{ $fpr->id }}"></div></li>
                                            @foreach($fpr->childReplies as $ch_reply)
                                            <li>
                                                <div class="commentWrapOuter">
                                                    <div class="commentWrap">
                                                        <div class="userImg">
                                                            @if(isset($ch_reply->memberInfo) && !empty($ch_reply->memberInfo) && $ch_reply->memberInfo->image != null)
                                                            <a href="{{ route('front.user.memberProfile', array('member_company' => $ch_reply->memberInfo->slug))}}">
                                                                <img src="{{ asset('public/uploads/user_images/thumb/'. $ch_reply->memberInfo->image) }}" class="img-fluid" />
                                                            </a>
                                                            @else
                                                            <a href="@if(isset($ch_reply->memberInfo) && !empty($ch_reply->memberInfo)){{ route('front.user.memberProfile', array('member_company' => $ch_reply->memberInfo->slug)) }}@endif">
                                                                <img src="{{ $defaultImage }}" class="img-fluid" />
                                                            </a>
                                                            @endif
                                                        </div>
                                                        <div class="userCommemt">
                                                            <h4>
                                                                @if(isset($ch_reply->memberInfo) && !empty($ch_reply->memberInfo))
                                                                    <a href="{{ route('front.user.memberProfile', array('member_company' => $ch_reply->memberInfo->slug)) }}">
                                                                        {{ $ch_reply->memberInfo->contact_name }}
                                                                    </a>
                                                                @endif
                                                                <small class="ml-2">@if($ch_reply->created_at != null){{ date('d F, Y', strtotime($ch_reply->created_at)) }}@endif</small>
                                                            </h4>
                                                            <p>{{ $ch_reply->reply_text }}</p>
                                                            @if(!empty($ch_reply->postImages) && count($ch_reply->postImages))

                                                        @foreach($ch_reply->postImages as $fpic)

                                                            @php
                                                            if (isset($fpic->media_path) && !empty($fpic->media_path)) {
                                                                $postImageChild = asset('public/uploads/posts/images/'. $fpic->media_path);
                                                            }
                                                            
                                                            @endphp

                                                             <div class="attached-img-wrap">
                                                             <label>Images &amp; Video</label>
                                                              <div class="attached-img">
                                                                <a class="fancybox" rel="group" href="{{ $postImageChild}}"><img src="{{ $postImageChild }}" width="100" height="100"  class="hover-shadow" /></a>                            
                                                              </div>
                                                            </div>


                                                        

                                                        @endforeach
                                                    @endif

                                                    @if(!empty($ch_reply->video_url))

                                                            @php

                                                                $video_url = $ch_reply->video_url;


                                                            $url =videoType($video_url);

                                                            if($url=='youtube')
                                                            {
                                                                $video_id = extractVideoID($video_url);

                                                            
                                                                $thumbnail3 = getYouTubeThumbnailImage($video_id);
                                                            }
                                                            else if($url=='vimeo')
                                                            {
                                                                $video_id = getVimeoId($video_url);

                                                            
                                                                $thumbnail3 = getVimeoThumb($video_id);
                                                            }

                                                                
                                                            $ch_reply->video_url =getYoutubeEmbedUrl($ch_reply->video_url);



                                                                

                                                                

                                                            @endphp


                                                            @if(isset($thumbnail3))

                                                             <a onclick ="openVideoModal('{{$ch_reply->video_url}}')"><img src="{{$thumbnail3}}" class="pitch"></a>

                                                             @endif

                                                            

                                                             
                                                        
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @else
                                        <ul> <!-- NO REPLY :: it works when no reply for a comment - only for first time -->
                                            <li><div id="addCommentBox_{{ $post->id }}-{{ $fpr->id }}"></div></li>
                                        </ul>
                                        @endif
                                        <!-- For n number of reply -->
                                    </li>
                                    @endforeach
                                </ul>
                                @else
                                <ul> <!-- NO FIRST COMMENT :: it works when no comment - only for first time -->
                                    <li><div id="addCommentBox_{{ $post->id }}-0"></div></li>
                                </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- Comment End -->
                </div>
                <div class="post-comment-mobile">
                    <p>Add your comment</p>
                    <form>
                        <textarea class="form-control" placeholder="Your comment..."></textarea>
                        <button type="button" class="btn btn-secondary cancel">Cancel</button>
                        <button type="button" class="btn btn-primary">Add comment</button>
                    </form>
                </div>
            </div>
        @endforeach
        <div class="col-sm-12 mt-2">
            @if(isset($myPosts) && count($myPosts)) {{ $myPosts->appends(request()->query())->links() }} @endif
        </div>
    @else
    <div class="col-sm-12">
        <div class="postCard">
            <div class="postWrap">
                <div class="postTop">
                    <p>Sorry! No post found.</p>
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

@endsection

@push('page_js')
<script>
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

       /* var video_id ='post_video_' + postID + '-' + parentID;

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

    