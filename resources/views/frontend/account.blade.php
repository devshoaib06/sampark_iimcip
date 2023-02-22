
@extends('frontend.layouts.app')
@section('content')


@php
 if (Auth::check()) {
	 $user_type = Auth::user()->user_type;
 }
@endphp

@if(isset($usersQuery) )
 @foreach($usersQuery as $users)
	
	
	@include('frontend.startup_search',['users' => $users]) 
	
 
 @endforeach
@endif

@if(isset($companyQuery) )
 @foreach($companyQuery as $company)
	
	
	@include('frontend.company_search',['company' => $company]) 
	
 
 @endforeach
@endif	

@if(isset($mentorQuery) )
 @foreach($mentorQuery as $mentor)
	
	
	@include('frontend.mentor_search',['mentor' => $mentor]) 
	
 
 @endforeach
@endif	

{{--@if(isset($ajaxPostData) )
@foreach($ajaxPostData as $postdata)
	
	dd($postdata);
	@include('frontend.post_category_search',['postdata' => $postdata]) 
	
 
@endforeach
@endif--}}

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
	
	
	

    
    @if (isset($normalPost) && !empty($normalPost) && count($normalPost))
        @foreach($normalPost as $post)
            @php
                if (isset($post->memberInfo) && !empty($post->memberInfo)) {
                    $memberInfo = $post->memberInfo;
					//dd($memberInfo);
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
							@endif--}}
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


                        @php

                        $removeChar = ["https://", "http://"];
                        if(isset($memberInfo) && !empty($memberInfo)) {

                            $http_referer_link = str_replace($removeChar, "", $memberInfo->linkedIn);
                        }


                        @endphp

                        <div class="postUser">
                            <div class="userImg">
                                @if(isset($memberInfo) && !empty($memberInfo)) 
                                    <a data-toggle="modal" data-target="#userInfo"  onclick="user_details('{{$memberInfo->contact_name}}', '{{$memberInfo->designation}}',{{$memberInfo->id}},'{{ $http_referer_link }}', '{{$profileImage}}','{{$memberInfo->member_company}}','{{$memberInfo->email_id}}','{{$memberInfo->about_you}}')">
                                        <img src="{{ $profileImage }}" class="img-fluid" />
                                        </a>
                                @endif
							
							@if(isset($post->postCategories) && !empty($post->postCategories) && count($post->postCategories))
                               
                                    @foreach($post->postCategories as $pc)
                                        @if(isset($pc->categoryInfo) && !empty($pc->categoryInfo) && $pc->categoryInfo->name != '')
                                            <p>
                                                <a href="{{ route('front.user.account') }}?category={{ base64_encode($pc->categoryInfo->id) }}">{{ $pc->categoryInfo->name }}</a>
                                            </p>
                                        @endif
                                    @endforeach
                                
							@endif
							
							
							
                            </div>
                            <div class="userInfo">
                                <h3>
                                    @if(isset($memberInfo) && !empty($memberInfo))
                                    <a data-toggle="modal" data-target="#userInfo"  onclick="user_details('{{$memberInfo->contact_name}}', '{{$memberInfo->designation}}',{{$memberInfo->id}},'{{ $http_referer_link }}', '{{$profileImage}}','{{$memberInfo->member_company}}','{{$memberInfo->email_id}}','{{$memberInfo->about_you}}')">{{ $memberInfo->contact_name }}</a> 
                                    @endif
                                    <span>{{ date('F d, Y', strtotime($post->created_at)) }}</span>
                                </h3>
                                @if(isset($memberInfo) && !empty($memberInfo)) 
                                    <h4><a href="{{ route('front.user.memberProfile', array('member_company' => $memberInfo->slug)) }}" target="_blank">{{ $memberInfo->member_company }}</a></h4>
                                @endif
                            </div>
                        </div>
                        <div class="postTitle">
                            @if(isset($memberInfo) && !empty($memberInfo)) 
                                <a  data-toggle="modal" data-target="#myModalmail"  style="    font-size: 13px;
                                color: #ffffff;
                                font-weight: bold;
                                background-color: #2d75a1;
                                padding: 6px 13px;
                                border-radius: 5px;
                                float: right; display:none;" onclick="receiver({{$memberInfo->id}})" id="sendMessage{{$memberInfo->id}}" >Send Message</a>
                            @endif
                                        <h3> 


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
                                        {!! html_entity_decode($pnohtml, ENT_QUOTES) !!}
                                    </p>
                                </div>
                            @else
                                <p>{!! html_entity_decode($pnohtml, ENT_QUOTES) !!}</p>
                            @endif

                            

                             @if(!empty($post->video_link))

                                 @php

                                    $video_url = $post->video_link;

                                    $thumbnail='';
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
						<br>
						@if(isset($post->image))
						<div class="postImages">
							<img src="/public/front_end/images/{{$post->image}}">
						
						</div>
						@endif
					
						@php //resize_crop_image(100, 100,image, image) @endphp
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
                                    <input type="button" id="addCommentBTN_{{ $post->id }}-0" data-postid="{{ $post->id }}" data-postpid="0" class="addCommentBTN btn-comment" value="Add comment">
                                </div>
                                <div class="comment-attachment">
                                    <label for="post_image_{{ $post->id }}-0">Add Images</label>
                                    <input type="file" id="post_image_{{ $post->id }}-0"  class="form-control input-comment add-img" onchange="getComboA(this,'{{ $post->id }}-0')" accept="image/x-png,image/gif,image/jpeg" multiple="true">
                                    <p id="error_image_{{ $post->id }}-0" class="err-msg" style="color: red;"></p>
                                     <label for="post_video_{{ $post->id }}-0">Add Video</label>
                                    <input type="text" id="post_video_{{ $post->id }}-0"  class="form-control input-comment input-url" placeholder="Enter Youtube or Vimeo Video Link">
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
                                                    <a href="{{ route('front.user.memberProfile', array('member_company' =>$fpr->memberInfo->slug)) }}" target="_blank">
                                                        <img src="{{ asset('public/uploads/user_images/thumb/'. $fpr->memberInfo->image) }}" class="img-fluid" />
                                                    </a>
                                                    @else
                                                    <a href="@if(isset($fpr->memberInfo) && !empty($fpr->memberInfo)){{ route('front.user.memberProfile', array('member_company' => $fpr->memberInfo->slug))}}@endif" target="_blank">
                                                        <img src="{{ $defaultImage }}" class="img-fluid" />
                                                    </a>
                                                    @endif
                                                </div>
                                                <div class="userCommemt">
                                                    <h4>
                                                        @if(isset($fpr->memberInfo) && !empty($fpr->memberInfo))
                                                            <a href="{{ route('front.user.memberProfile', array('member_company' => $fpr->memberInfo->slug)) }}" target="_blank">
                                                                {{ $fpr->memberInfo->contact_name }}
                                                            </a>
                                                        @endif
                                                        <small class="ml-2">@if($fpr->created_at != null){{ date('d F, Y', strtotime($fpr->created_at)) }}@endif</small>
                                                    </h4>
                                                    <p>{{ $fpr->reply_text }}</p>
                                                    <div class="attached-img-wrap">
                                                        <label>Images & Video</label>
                                                        <div class="attached-img">
                                                            @if(!empty($fpr->postImages) && count($fpr->postImages))
                                                                @foreach($fpr->postImages as $fpi)
                                                                    @php
                                                                    if (isset($fpi->media_path) && !empty($fpi->media_path)) {
                                                                        $postImage = asset('public/uploads/posts/images/'. $fpi->media_path);
                                                                    }
                                                                    @endphp
                                                                    <a class="fancybox" data-fancybox="images" href="{{ $postImage}}"><img src="{{ $postImage }}" width="100" height="100"  class="hover-shadow" /></a>
                                                                @endforeach
                                                            @endif
                                                            @if(!empty($fpr->video_url))

                                                            @php

                                                                $video_url = $fpr->video_url;


                                                            $url =videoType($video_url);
                                                            $thumbnail1='';

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
                                                        </div>
                                                    </div>



                                                    <ul>
                                                        <!-- Self comment reply off -->
                                                        <li class="addCommentLink"><a href="#">Add Reply</a></li>
                                                        <li class="replyLink"><a href="#">
                                                            <span id="replyCount_{{ $post->id }}-{{ $fpr->id }}">
                                                                @if(isset($fpr->childReplies) && count($fpr->childReplies))
                                                                    {{ count($fpr->childReplies) }}
                                                                    @if(count($fpr->childReplies) > 1)
                                                                        Replies
                                                                    @else
                                                                        Reply
                                                                    @endif
                                                                @endif 
                                                            </span>
                                                        </a></li>
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

                                                    <p id="error_image_{{ $post->id }}-{{ $fpr->id }}" class="err-msg" style="color: red;"></p>

                                                    <label for="post_video_{{ $post->id }}-{{ $fpr->id }}">Add Video</label>
                                                    <input type="text" id="post_video_{{ $post->id }}-{{ $fpr->id }}"  class="form-control input-comment input-url" placeholder="Enter Youtube or Vimeo link" >
                                                    
                                                </div>

                                                <div class="preview-wrap">
                                                    <div class="img_preview_{{ $post->id }}-{{ $fpr->id }}">
                                                    </div>
                                                    <!-- <div class="video-preview">
                                                        <video class="vid-preview" width="80" height="40"></video>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- For n number of reply , Arindam -> need to check this block -->
                                        @if(isset($fpr->childReplies) && count($fpr->childReplies))
                                        <ul class="replyList" id="replyList_{{ $post->id }}-{{ $fpr->id }}">
                                            <li><div id="addCommentBox_{{ $post->id }}-{{ $fpr->id }}"></div></li>
                                            @foreach($fpr->childReplies as $ch_reply)
                                            <li>
                                                <div class="commentWrapOuter">
                                                    <div class="commentWrap">
                                                        <div class="userImg">
                                                            @if(isset($ch_reply->memberInfo) && !empty($ch_reply->memberInfo) && $ch_reply->memberInfo->image != null)
                                                            <a href="{{ route('front.user.memberProfile', array('member_company' =>$ch_reply->memberInfo->slug))}}" target="_blank">
                                                                <img src="{{ asset('public/uploads/user_images/thumb/'. $ch_reply->memberInfo->image) }}" class="img-fluid" />
                                                            </a>
                                                            @else
                                                            <a href="@if(isset($ch_reply->memberInfo) && !empty($ch_reply->memberInfo)){{ route('front.user.memberProfile', array('member_company' => $ch_reply->memberInfo->slug)) }}@endif" target="_blank">
                                                                <img src="{{ $defaultImage }}" class="img-fluid" />
                                                            </a>
                                                            @endif
                                                        </div>
                                                        <div class="userCommemt">
                                                            <h4>
                                                                @if(isset($ch_reply->memberInfo) && !empty($ch_reply->memberInfo))
                                                                    <a href="{{ route('front.user.memberProfile', array('member_company' => $ch_reply->memberInfo->slug)) }}" target="_blank">
                                                                        {{ $ch_reply->memberInfo->contact_name }}
                                                                    </a>
                                                                @endif
                                                                <small class="ml-2">@if($ch_reply->created_at != null){{ date('d F, Y', strtotime($ch_reply->created_at)) }}@endif</small>
                                                            </h4>
                                                            <p>{{ $ch_reply->reply_text }}</p>
                                                            <div class="attached-img-wrap">
                                                                <label>Images & Video</label>
                                                                <div class="attached-img">

                                                                @if(!empty($ch_reply->postImages) && count($ch_reply->postImages))

                                                                @foreach($ch_reply->postImages as $fpic)

                                                                    @php
                                                                    if (isset($fpic->media_path) && !empty($fpic->media_path)) {
                                                                        $postImageChild = asset('public/uploads/posts/images/'. $fpic->media_path);
                                                                    }
                                                                
                                                                @endphp

                                                                <a class="fancybox" rel="group" href="{{ $postImageChild}}"><img src="{{ $postImageChild }}" class="pitch" /></a>

                                                                @endforeach
                                                            @endif

                                                           
                                                           

                                                             @if(!empty($ch_reply->video_url))

                                                            @php

                                                                $video_url = $ch_reply->video_url;


                                                            $url =videoType($video_url);

                                                            $thumbnail3='';

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
            @if(isset($normalPost) && count($normalPost)) {{ $normalPost->appends(request()->query())->links() }} @endif
        </div>
    @else
    <div class="col-sm-12">
        <div class="postCard">
            <div class="postWrap">
                <div class="postTop">
                    <p>{{$mesg}}</p>
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
  <div class="modal-dialog">


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
                                    <label>Message:</label>
                                    <textarea class="form-control" name="message" required="required"  ></textarea>
                                    <input type="hidden" class="form-control" name="receiver_id" id="receiver_id" required="required"  value="">
                                    
                                        
                            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Send</button>
      </div>
    </div>
</form>
  </div>
</div>

<div class="modal fade" role="dialog" tabindex="-1" id="userInfo">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header user-info-head">
        <img src="http://iimcip.net/public/front_end/images/pop-up.jpg" class="img-fluid">
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <div class="user-info-img">
            <img src="http://iimcip.net/public/front_end/images/dummy-user.jpg" id="usr_img" class="img-fluid">
        </div>
        <div class="user-info-details">
            <h2 id="user_name">Subhrangshu Sanyal</h2>
            <div align="center">
           <b> <span id="designation">Finance, Asset Management</span></b><span>,</span> <span id="company_name"></span>

<br>
<!--           <span id="email_id"></span>-->
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

function user_details(user_name,designation,r_id,hrefval,userimage,company_name,email_id,about_me){
 
 $("#user_name").html(user_name);
 $("#designation").html(designation);
 $("#r_id").val(r_id);
 $("#company_name").html(company_name);
 $("#email_id").html(email_id);
  $("#about_me").html(about_me);
   
 $("#ahref").attr("href", "//"+hrefval);

 $('#usr_img').attr('src', userimage);

}

function receiver(id){
    $("#receiver_id").val(id);
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

    