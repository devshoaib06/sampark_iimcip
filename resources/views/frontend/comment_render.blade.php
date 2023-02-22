@if(isset($memberInfo) && isset($postInfo))
    @php $defaultImage = asset('public/front_end/images/profile-pic.png'); @endphp
    <div class="commentWrapOuter">
        <div class="commentWrap">
            <div class="userImg">
                @if($memberInfo->image != null)
                    <a href="{{ route('front.user.memberProfile', array('timestamp_id' => $memberInfo->timestamp_id)) }}" target="_blank">
                        <img src="{{ asset('public/uploads/user_images/thumb/'. $memberInfo->image) }}" class="img-fluid" />
                    </a>
                @else
                    <a href="{{ route('front.user.memberProfile', array('timestamp_id' => $memberInfo->timestamp_id)) }}" target="_blank">
                        <img src="{{ $defaultImage }}" class="img-fluid" />
                    </a>
                @endif
            </div>
            <div class="userCommemt">
                <h4>
                    <a href="{{ route('front.user.memberProfile', array('timestamp_id' => $memberInfo->timestamp_id)) }}" target="_blank">
                        {{ $memberInfo->contact_name }}
                    </a>
                    <small class="ml-2">@if($postInfo->created_at != null){{ date('d F, Y', strtotime($postInfo->created_at)) }}@endif</small>
                </h4>
                <p>{{ $postInfo->reply_text }}</p>

                @if(!empty($postInfo->image) && count($postInfo->image))

                <div class="attached-img-wrap">
                        <label>Images & Video</label>
                        <div class="attached-img">

                    @foreach($postInfo->image as $fpi)

                        @php
                        if (isset($fpi->media_path) && !empty($fpi->media_path)) {
                            $postImage = asset('public/uploads/posts/images/'. $fpi->media_path);
                        }
                        
                        @endphp

                        <a class="fancybox" data-fancybox="images" href="{{ $postImage}}"><img src="{{ $postImage }}" width="100" height="100"  class="hover-shadow" /></a>


                        


                    

                    @endforeach
                
                @endif


                @if(!empty($postInfo->video_url))

                                 @php

                                    $video_url = $postInfo->video_url;


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

                                    

                                    $postInfo->video_url =getYoutubeEmbedUrl($postInfo->video_url);

                                @endphp

                                <a onclick ="openVideoModal('{{$postInfo->video_url}}')"><img src="{{$thumbnail}}" class="pitch"></a>

                            @endif

                </div>
            </div>
                <!-- <ul>
                    <li class="addCommentLink"><a href="#">Add Reply</a></li>
                </ul> -->
            </div>
        </div>
    </div>
@endif