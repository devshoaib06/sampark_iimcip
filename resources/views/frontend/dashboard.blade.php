@extends('frontend.layouts.app_dashboard')
@section('content')

<div class="row">

    {{-- <div class="col-lg-2 col-md-4 col-sm-6">
        <a href="{{ route('front.user.feed') }}">
        <div class="card">
            <div class="card-img">
                <img src="{{ asset('/public/front_end/images/post.png')}}" class="img-fluid">
            </div>
            <h3>Relevant Posts</h3>
            <span>{{$allPostCount}}</span>
        </div>
        </a>
    </div>
    
    <div class="col-lg-2 col-md-4 col-sm-6">
        <a href="{{ route('front.user.bookmark') }}?category={{ base64_encode(2) }}@if(isset($_GET['post']) && $_GET['post'] != '')&post={{ $_GET['post'] }}@endif">
        <div class="card">
            <div class="card-img">
                <img src="{{ asset('/public/front_end/images/announcement.png')}}" class="img-fluid">
            </div>
            <h3>Announcements</h3>
            <span>{{$announcementsPostCount}}</span>
        </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <a href="{{ route('front.user.bookmark') }}?category={{ base64_encode(3) }}@if(isset($_GET['post']) && $_GET['post'] != '')&post={{ $_GET['post'] }}@endif">
        <div class="card">
            <div class="card-img">
                <img src="{{ asset('/public/front_end/images/collaborations.png')}}" class="img-fluid">
            </div>
            <h3>Collaboration</h3>
            <span>{{$collaborationPostCount}}</span>
        </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <a href="{{ route('front.user.bookmark') }}?category={{ base64_encode(4) }}@if(isset($_GET['post']) && $_GET['post'] != '')&post={{ $_GET['post'] }}@endif">
        <div class="card">
            <div class="card-img">
                <img src="{{ asset('/public/front_end/images/awards.png')}}" class="img-fluid">
            </div>
            <h3>Achievements</h3>
            <span>{{$achievementsPostCount}}</span>
        </div>
        </a>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <a href="{{ route('front.user.bookmark') }}?category={{ base64_encode(5) }}@if(isset($_GET['post']) && $_GET['post'] != '')&post={{ $_GET['post'] }}@endif">
        <div class="card">
            <div class="card-img">
                <img src="{{ asset('/public/front_end/images/opportunities.png')}}" class="img-fluid">
            </div>
            <h3>Opportunities</h3>
            <span>{{$opportunitiesPostCount}}</span>
        </div>
        </a>
    </div> --}}
    
   <!--  <div class="col-lg-2 col-md-4 col-sm-6">
        <a href="{{ route('front.user.bookmark') }}?category={{ base64_encode(1) }}@if(isset($_GET['post']) && $_GET['post'] != '')&post={{ $_GET['post'] }}@endif">
        <div class="card">
            <div class="card-img">
                <img src="{{ asset('/public/front_end/images/news.png')}}" class="img-fluid">
            </div>
            <h3>News</h3>
            <span>{{$newsPostCount}}</span>
        </div>
        </a>
    </div> -->
    <div class="col-lg-2 col-md-3 col-sm-4">
        <a href="{{ route('front.user.startup') }}">
        <div class="card">
            <div class="card-img">
                <img src="{{ asset('/public/front_end/images/startup.png')}}" class="img-fluid">
            </div>
            <h3>StartUps</h3>
            <span>{{$allStartUpCount}}</span>
        </div>
        </a>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
    <ul class="nav nav-tabs" id="dashboardTab" role="tablist">
         <li class="nav-item" role="presentation">
            <a class="nav-link active" id="post-tab" data-toggle="tab" href="#post" role="tab" aria-controls="post" aria-selected="true">Relevant Posts</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="announcements-tab" data-toggle="tab" href="#announcements" role="tab" aria-controls="announcements" aria-selected="false">Announcements</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="collaboration-tab" data-toggle="tab" href="#collaboration" role="tab" aria-controls="collaboration" aria-selected="false">Collaboration</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="achievements-tab" data-toggle="tab" href="#achievements" role="tab" aria-controls="achievements" aria-selected="false">Achievements</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="opportunities-tab" data-toggle="tab" href="#opportunities" role="tab" aria-controls="opportunities" aria-selected="false">Opportunities</a>
        </li>
         <!-- <li class="nav-item" role="presentation">
            <a class="nav-link " id="news-tab" data-toggle="tab" href="#news" role="tab" aria-controls="news" aria-selected="false">News</a>
        </li> -->
       
    </ul>
    <div class="tab-content" id="dashboardTabContent">
        <div class="tab-pane fade show active" id="post" role="tabpanel" aria-labelledby="post-tab">
            <div class="row">
                 @if ($allPostCount >0)
                    @foreach($allPost as $post6)
                        @php
                            if (isset($post6->memberInfo) && !empty($post6->memberInfo)) {
                                $memberInfo5 = $post6->memberInfo;
                            }
                        @endphp
                    <div class="col-md-12">
                        <div class="postCard">
                            <div class="postWrap latest">
                                <div class="postTop">
                                    @if(isset($post6->postIndistries) && !empty($post6->postIndistries) && count($post6->postIndistries))
                                    <div class="postTopLeft">
                                        <ul>
                                            @foreach($post6->postIndistries as $pi6)
                                        @if(isset($pi6->industryInfo) && !empty($pi6->industryInfo) && $pi6->industryInfo->industry_category != '')
                                            <li><a href="{{ route('front.user.account') }}?industry={{ base64_encode($pi6->industryInfo->id) }}">{{ $pi6->industryInfo->industry_category }}</a></li>

                                             @endif
                                     @endforeach
                                            
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                                @php 
                                $profileImage = $defaultImage = $authUserImage = asset('public/front_end/images/profile-pic.png');
                                if(isset($memberInfo5) && !empty($memberInfo5) && $memberInfo5->image != null) {
                                    $profileImage = asset('public/uploads/user_images/thumb/'. $memberInfo5->image);
                                }
                                if(Auth::user()->image != null) {
                                    $authUserImage = asset('public/uploads/user_images/thumb/'. Auth::user()->image);
                                }
                               @endphp
                                <div class="postUser">
                                    <div class="userImg">
                                        <img src="{{ $profileImage }}" class="img-fluid">
                                    </div>
                                    <div class="userInfo">
                                        @if(isset($memberInfo5) && !empty($memberInfo5))
                                        <h3><a href="{{ route('front.user.memberProfile', array('timestamp_id' => $memberInfo5->timestamp_id)) }}">{{ $memberInfo5->contact_name }}</a> 
                                        @endif
                                        <span>{{ date('F d, Y', strtotime($post6->created_at)) }}</span></h3>
                                         @if(isset($memberInfo5) && !empty($memberInfo5)) 
                                        <h4><a href="{{ route('front.user.account') }}?search={{ $memberInfo5->member_company }}">{{ $memberInfo5->member_company }}</a></h4>
                                         @endif
                                    </div>
                                </div>
                                <div class="postTitle">
                                    <h2>{{ $post6->post_title }}</h2>
                                </div>
                                <div class="postDetails">
                                    @php
                                    $phtml = html_entity_decode($post6->post_info, ENT_QUOTES);
                                    $pnohtml = strip_tags($phtml);
                                @endphp

                                @if(strlen($pnohtml) > 250)
                                    <p>
                                         {!! html_entity_decode($post6->post_info, ENT_QUOTES) !!}
                                    </p>

                                @else
                                <p>{!! html_entity_decode($post6->post_info, ENT_QUOTES) !!}</p>
                            @endif
                                </div>
                            </div>
                        </div>
                    </div>
                   @endforeach
                   
                   @if($allPostCount>5)
                   <div class="col-md-12">
                    <a href="{{ route('front.user.feed') }}" class="btn btn-primary btn-view">View More</a>
                    </div>
                    @endif
                @else
                  <p>Sorry! No post found.</p>
                @endif
                
                
            </div>
        </div>
        
        <div class="tab-pane fade" id="announcements" role="tabpanel" aria-labelledby="announcements-tab">
            <div class="row">
                 @if ($announcementsPostCount>0)
                    @foreach($announcementsPost as $post2)
                        @php
                            if (isset($post2->memberInfo) && !empty($post2->memberInfo)) {
                                $memberInfo1 = $post2->memberInfo;
                            }
                        @endphp
                    <div class="col-md-12">
                        <div class="postCard">
                            <div class="postWrap latest">
                                <div class="postTop">
                                    @if(isset($post2->postIndistries) && !empty($post2->postIndistries) && count($post2->postIndistries))
                                    <div class="postTopLeft">
                                        <ul>
                                            @foreach($post2->postIndistries as $pi2)
                                        @if(isset($pi2->industryInfo) && !empty($pi2->industryInfo) && $pi2->industryInfo->industry_category != '')
                                            <li><a href="{{ route('front.user.account') }}?industry={{ base64_encode($pi2->industryInfo->id) }}">{{ $pi2->industryInfo->industry_category }}</a></li>

                                             @endif
                                     @endforeach
                                            
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                                @php 
                                $profileImage = $defaultImage = $authUserImage = asset('public/front_end/images/profile-pic.png');
                                if(isset($memberInfo1) && !empty($memberInfo1) && $memberInfo1->image != null) {
                                    $profileImage = asset('public/uploads/user_images/thumb/'. $memberInfo1->image);
                                }
                                if(Auth::user()->image != null) {
                                    $authUserImage = asset('public/uploads/user_images/thumb/'. Auth::user()->image);
                                }
                               @endphp
                                <div class="postUser">
                                    <div class="userImg">
                                        <img src="{{ $profileImage }}" class="img-fluid">
                                    </div>
                                    <div class="userInfo">
                                        @if(isset($memberInfo1) && !empty($memberInfo1))
                                        <h3><a href="{{ route('front.user.memberProfile', array('timestamp_id' => $memberInfo1->timestamp_id)) }}">{{ $memberInfo1->contact_name }}</a> 
                                        @endif
                                        <span>{{ date('F d, Y', strtotime($post2->created_at)) }}</span></h3>
                                         @if(isset($memberInfo1) && !empty($memberInfo1)) 
                                        <h4><a href="{{ route('front.user.account') }}?search={{ $memberInfo1->member_company }}">{{ $memberInfo1->member_company }}</a></h4>
                                         @endif
                                    </div>
                                </div>
                                <div class="postTitle">
                                    <h2>{{ $post2->post_title }}</h2>
                                </div>
                                <div class="postDetails">
                                    @php
                                    $phtml = html_entity_decode($post2->post_info, ENT_QUOTES);
                                    $pnohtml = strip_tags($phtml);
                                @endphp

                                @if(strlen($pnohtml) > 250)
                                    <p>
                                         {!! html_entity_decode($post2->post_info, ENT_QUOTES) !!}
                                    </p>

                                @else
                                <p>{!! html_entity_decode($post2->post_info, ENT_QUOTES) !!}</p>
                            @endif
                                </div>
                            </div>
                        </div>
                    </div>
                   @endforeach
                   @if ($announcementsPostCount>5)
                   <div class="col-md-12">
                    <a href="{{ route('front.user.bookmark') }}?category={{ base64_encode(2) }}@if(isset($_GET['post']) && $_GET['post'] != '')&post={{ $_GET['post'] }}@endif" class="btn btn-primary btn-view">View More</a>
                    </div>
                   @endif

                @else
                  <p>Sorry! No post found.</p>
                @endif


                
                
            </div>
        
        </div>

        <div class="tab-pane fade" id="collaboration" role="tabpanel" aria-labelledby="collaboration-tab">
            <div class="row">
                 @if ($collaborationPostCount>0)
                    @foreach($collaborationPost as $post3)
                        @php
                            if (isset($post3->memberInfo) && !empty($post3->memberInfo)) {
                                $memberInfo2 = $post3->memberInfo;
                            }
                        @endphp
                    <div class="col-md-12">
                        <div class="postCard">
                            <div class="postWrap latest">
                                <div class="postTop">
                                    @if(isset($post3->postIndistries) && !empty($post3->postIndistries) && count($post3->postIndistries))
                                    <div class="postTopLeft">
                                        <ul>
                                            @foreach($post3->postIndistries as $pi3)
                                        @if(isset($pi3->industryInfo) && !empty($pi3->industryInfo) && $pi3->industryInfo->industry_category != '')
                                            <li><a href="{{ route('front.user.account') }}?industry={{ base64_encode($pi3->industryInfo->id) }}">{{ $pi3->industryInfo->industry_category }}</a></li>

                                             @endif
                                     @endforeach
                                            
                                        </ul>
                                    </div>
                                    @endif


                                </div>
                                @php 
                                $profileImage = $defaultImage = $authUserImage = asset('public/front_end/images/profile-pic.png');
                                if(isset($memberInfo2) && !empty($memberInfo2) && $memberInfo2->image != null) {
                                    $profileImage = asset('public/uploads/user_images/thumb/'. $memberInfo2->image);
                                }
                                if(Auth::user()->image != null) {
                                    $authUserImage = asset('public/uploads/user_images/thumb/'. Auth::user()->image);
                                }
                               @endphp

                               
                                <div class="postUser">
                                    <div class="userImg">
                                        <img src="{{ $profileImage }}" class="img-fluid">
                                    </div>
                                    <div class="userInfo">
                                        @if(isset($memberInfo2) && !empty($memberInfo2))
                                        <h3><a href="{{ route('front.user.memberProfile', array('timestamp_id' => $memberInfo2->timestamp_id)) }}">{{ $memberInfo2->contact_name }}</a> 
                                        @endif
                                        <span>{{ date('F d, Y', strtotime($post3->created_at)) }}</span></h3>
                                         @if(isset($memberInfo2) && !empty($memberInfo2)) 
                                        <h4><a href="{{ route('front.user.account') }}?search={{ $memberInfo2->member_company }}">{{ $memberInfo2->member_company }}</a></h4>
                                         @endif
                                    </div>
                                </div>
                                <div class="postTitle">
                                    <h2>{{ $post3->post_title }}</h2>
                                </div>
                                <div class="postDetails">
                                    @php
                                    $phtml = html_entity_decode($post3->post_info, ENT_QUOTES);
                                    $pnohtml = strip_tags($phtml);
                                @endphp

                                @if(strlen($pnohtml) > 250)
                                    <p>
                                         {!! html_entity_decode($post3->post_info, ENT_QUOTES) !!}
                                    </p>

                                @else
                                <p>{!! html_entity_decode($post3->post_info, ENT_QUOTES) !!}</p>
                            @endif
                                </div>
                            </div>
                        </div>
                    </div>
                   @endforeach
                    @if ($collaborationPostCount>5)
                       <div class="col-md-12">
                        <a href="{{ route('front.user.bookmark') }}?category={{ base64_encode(3) }}@if(isset($_GET['post']) && $_GET['post'] != '')&post={{ $_GET['post'] }}@endif" class="btn btn-primary btn-view">View More</a>
                        </div>
                     @endif
                @else
                  <p>Sorry! No post found.</p>
                @endif
                
                
            </div>
        </div>

        <div class="tab-pane fade" id="achievements" role="tabpanel" aria-labelledby="achievements-tab">
            <div class="row">
                 @if ($achievementsPostCount>0)
                    @foreach($achievementsPost as $post4)
                        @php
                            if (isset($post4->memberInfo) && !empty($post4->memberInfo)) {
                                $memberInfo3 = $post4->memberInfo;
                            }
                        @endphp
                    <div class="col-md-12">
                        <div class="postCard">
                            <div class="postWrap latest">
                                <div class="postTop">
                                    @if(isset($post4->postIndistries) && !empty($post4->postIndistries) && count($post4->postIndistries))
                                    <div class="postTopLeft">
                                        <ul>
                                            @foreach($post4->postIndistries as $pi4)
                                        @if(isset($pi4->industryInfo) && !empty($pi4->industryInfo) && $pi4->industryInfo->industry_category != '')
                                            <li><a href="{{ route('front.user.account') }}?industry={{ base64_encode($pi4->industryInfo->id) }}">{{ $pi4->industryInfo->industry_category }}</a></li>

                                             @endif
                                     @endforeach
                                            
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                                
                                @php 
                                $profileImage = $defaultImage = $authUserImage = asset('public/front_end/images/profile-pic.png');
                                if(isset($memberInfo3) && !empty($memberInfo3) && $memberInfo3->image != null) {
                                    $profileImage = asset('public/uploads/user_images/thumb/'. $memberInfo3->image);
                                }
                                if(Auth::user()->image != null) {
                                    $authUserImage = asset('public/uploads/user_images/thumb/'. Auth::user()->image);
                                }
                               @endphp

                                <div class="postUser">
                                    <div class="userImg">
                                        <img src="{{ $profileImage }}" class="img-fluid">
                                    </div>
                                    <div class="userInfo">
                                        @if(isset($memberInfo3) && !empty($memberInfo3))
                                        <h3><a href="{{ route('front.user.memberProfile', array('timestamp_id' => $memberInfo3->timestamp_id)) }}">{{ $memberInfo3->contact_name }}</a> 
                                        @endif
                                        <span>{{ date('F d, Y', strtotime($post4->created_at)) }}</span></h3>
                                         @if(isset($memberInfo3) && !empty($memberInfo3)) 
                                        <h4><a href="{{ route('front.user.account') }}?search={{ $memberInfo3->member_company }}">{{ $memberInfo3->member_company }}</a></h4>
                                         @endif
                                    </div>

                                </div>
                                <div class="postTitle">
                                    <h2>{{ $post4->post_title }}</h2>
                                </div>
                                <div class="postDetails">
                                    @php
                                    $phtml = html_entity_decode($post4->post_info, ENT_QUOTES);
                                    $pnohtml = strip_tags($phtml);
                                @endphp

                                @if(strlen($pnohtml) > 250)
                                    <p>
                                         {!! html_entity_decode($post4->post_info, ENT_QUOTES) !!}
                                    </p>

                                @else
                                <p>{!! html_entity_decode($post4->post_info, ENT_QUOTES) !!}</p>
                            @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($achievementsPostCount>5)
                    <div class="col-md-12">
                    <a href="{{ route('front.user.bookmark') }}?category={{ base64_encode(4) }}@if(isset($_GET['post']) && $_GET['post'] != '')&post={{ $_GET['post'] }}@endif" class="btn btn-primary btn-view">View More</a>
                    </div>
                     @endif
                   @endforeach

                @else
                  <p>Sorry! No post found.</p>
                @endif
                
                
            </div>
        </div>

        <div class="tab-pane fade" id="opportunities" role="tabpanel" aria-labelledby="opportunities-tab">
            <div class="row">
                 @if ($opportunitiesPostCount>0)
                    @foreach($opportunitiesPost as $post5)
                        @php
                            if (isset($post5->memberInfo) && !empty($post5->memberInfo)) {
                                $memberInfo4 = $post5->memberInfo;
                            }
                        @endphp
                    <div class="col-md-12">
                        <div class="postCard">
                            <div class="postWrap latest">
                                <div class="postTop">
                                    @if(isset($post5->postIndistries) && !empty($post5->postIndistries) && count($post5->postIndistries))
                                    <div class="postTopLeft">
                                        <ul>
                                            @foreach($post5->postIndistries as $pi5)
                                        @if(isset($pi5->industryInfo) && !empty($pi5->industryInfo) && $pi5->industryInfo->industry_category != '')
                                            <li><a href="{{ route('front.user.account') }}?industry={{ base64_encode($pi5->industryInfo->id) }}">{{ $pi5->industryInfo->industry_category }}</a></li>

                                             @endif
                                     @endforeach
                                            
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                                @php 
                                $profileImage = $defaultImage = $authUserImage = asset('public/front_end/images/profile-pic.png');
                                if(isset($memberInfo4) && !empty($memberInfo4) && $memberInfo4->image != null) {
                                    $profileImage = asset('public/uploads/user_images/thumb/'. $memberInfo4->image);
                                }
                                if(Auth::user()->image != null) {
                                    $authUserImage = asset('public/uploads/user_images/thumb/'. Auth::user()->image);
                                }
                               @endphp
                                <div class="postUser">
                                    <div class="userImg">
                                        <img src="{{ $profileImage }}" class="img-fluid">
                                    </div>
                                    <div class="userInfo">
                                        @if(isset($memberInfo4) && !empty($memberInfo4))
                                        <h3><a href="{{ route('front.user.memberProfile', array('timestamp_id' => $memberInfo4->timestamp_id)) }}">{{ $memberInfo4->contact_name }}</a> 
                                        @endif
                                        <span>{{ date('F d, Y', strtotime($post5->created_at)) }}</span></h3>
                                         @if(isset($memberInfo4) && !empty($memberInfo4)) 
                                        <h4><a href="{{ route('front.user.account') }}?search={{ $memberInfo4->member_company }}">{{ $memberInfo4->member_company }}</a></h4>
                                         @endif
                                    </div>
                                </div>
                                <div class="postTitle">
                                    <h2>{{ $post5->post_title }}</h2>
                                </div>
                                <div class="postDetails">
                                    @php
                                    $phtml = html_entity_decode($post5->post_info, ENT_QUOTES);
                                    $pnohtml = strip_tags($phtml);
                                @endphp

                                @if(strlen($pnohtml) > 250)
                                    <p>
                                         {!! html_entity_decode($post5->post_info, ENT_QUOTES) !!}
                                    </p>

                                @else
                                <p>{!! html_entity_decode($post5->post_info, ENT_QUOTES) !!}</p>
                            @endif
                                </div>
                            </div>
                        </div>
                    </div>
                   @endforeach
                   @if ($opportunitiesPostCount>5)
                   <div class="col-md-12">
                    <a href="{{ route('front.user.bookmark') }}?category={{ base64_encode(5) }}@if(isset($_GET['post']) && $_GET['post'] != '')&post={{ $_GET['post'] }}@endif" class="btn btn-primary btn-view">View More</a>
                    </div>
                   @endif
                @else
                  <p>Sorry! No post found.</p>
                @endif
                
                
            </div>
        </div>
        

        <div class="tab-pane fade " id="news" role="tabpanel" aria-labelledby="news-tab">
            <div class="row">
                 @if ($newsPostCount>0)
                    @foreach($newsPost as $post1)
                        @php
                            if (isset($post1->memberInfo) && !empty($post1->memberInfo)) {
                               $memberInfo = $post1->memberInfo;
                            }
                        @endphp
                    <div class="col-md-12">
                        <div class="postCard">
                            <div class="postWrap latest">
                                <div class="postTop">
                                    @if(isset($post1->postIndistries) && !empty($post1->postIndistries) && count($post1->postIndistries))
                                    <div class="postTopLeft">
                                        <ul>
                                            @foreach($post1->postIndistries as $pi1)
                                        @if(isset($pi1->industryInfo) && !empty($pi1->industryInfo) && $pi1->industryInfo->industry_category != '')
                                            <li><a href="{{ route('front.user.account') }}?industry={{ base64_encode($pi1->industryInfo->id) }}">{{ $pi1->industryInfo->industry_category }}</a></li>

                                             @endif
                                     @endforeach
                                            
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                                @php 
                                $profileImage = $defaultImage = $authUserImage = asset('public/front_end/images/profile-pic.png');
                                if(isset($memberInfo) && !empty($memberInfo) && $memberInfo->image != null) {
                                    $profileImage = asset('public/uploads/user_images/thumb/'. $memberInfo->image);
                                }
                                if(Auth::user()->image != null) {
                                    $authUserImage = asset('public/uploads/user_images/thumb/'. Auth::user()->image);
                                }
                               @endphp
                                <div class="postUser">
                                    <div class="userImg">
                                        <img src="{{ $profileImage }}" class="img-fluid">
                                    </div>
                                    <div class="userInfo">
                                        @if(isset($memberInfo) && !empty($memberInfo))
                                        <h3><a href="{{ route('front.user.memberProfile', array('timestamp_id' => $memberInfo->timestamp_id)) }}">{{ $memberInfo->contact_name }}</a> 
                                        @endif
                                        <span>{{ date('F d, Y', strtotime($post1->created_at)) }}</span></h3>
                                         @if(isset($memberInfo) && !empty($memberInfo)) 
                                        <h4><a href="{{ route('front.user.account') }}?search={{ $memberInfo->member_company }}">{{ $memberInfo->member_company }}</a></h4>
                                         @endif
                                    </div>
                                </div>
                                <div class="postTitle">
                                    <h2>{{ $post1->post_title }}</h2>
                                </div>
                                <div class="postDetails">
                                    @php
                                    $phtml = html_entity_decode($post1->post_info, ENT_QUOTES);
                                    $pnohtml = strip_tags($phtml);
                                @endphp

                                @if(strlen($pnohtml) > 250)
                                    <p>
                                         {!! html_entity_decode($post1->post_info, ENT_QUOTES) !!}
                                    </p>

                                @else
                                <p>{!! html_entity_decode($post1->post_info, ENT_QUOTES) !!}</p>
                            @endif
                                </div>
                            </div>
                        </div>
                    </div>
                   @endforeach

                @if ($newsPostCount>5)
                <div class="col-md-12">
                <a href="{{ route('front.user.bookmark') }}?category={{ base64_encode(1) }}@if(isset($_GET['post']) && $_GET['post'] != '')&post={{ $_GET['post'] }}@endif" class="btn btn-primary btn-view">View More</a>
                </div>
                @endif

                @else
                  <p>Sorry! No post found.</p>
                @endif

                
                
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


</script>
@endpush

    