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
    
    @if(isset($memberInfo))
    <div class="col-md-12">
        <div class="member-wrap">
            <div class="member-img">
            @php 
                $profileImage = asset('public/front_end/images/profile-pic.png');
                if($memberInfo->image != '' && $memberInfo->image != null) {
                    $profileImage = asset('public/uploads/user_images/thumb/'. $memberInfo->image);
                }
            @endphp
            @if(isset($profileImage) && $profileImage != '')
                <img src="{{ $profileImage }}" class="img-fluid">
            @endif
            </div>
            <div class="member-info">
            <h1>{{ $memberInfo->member_company }}</h1>
            <h4>No of Posts: <span>{{$number_of_post}}</span>  No of Replies: <span>{{$number_of_reply}}</span></h4>
            <ul>
            <li><i class="fas fa-user"></i><strong>Name:</strong><span><a href="#">{{ $memberInfo->contact_name }}</a></span></li>
            <li><i class="fas fa-building"></i><strong>Industry Verticals:</strong>
            @if(isset($memberInfo->allIndustryIds) && !empty($memberInfo->allIndustryIds) && count($memberInfo->allIndustryIds))
                @php $i = 0; $tot = count($memberInfo->allIndustryIds); @endphp
                @foreach($memberInfo->allIndustryIds as $v)
                    @if(isset($v->industryInfo) && !empty($v->industryInfo)) 
                        <span>{{ trim($v->industryInfo->industry_category) }}</span>@if($i < $tot - 1), @endif
                    @endif
                @php $i++; @endphp
                @endforeach
            @endif

            
            @if($memberInfo->member_spec != '')
            <li><i class="fas fa-info-circle"></i><strong>Specialist In:</strong><span>{{ $memberInfo->member_spec }}</li>
            @endif
            @if($memberInfo->member_looking != '')
            <li><i class="fas fa-info-circle"></i><strong>Looking For:</strong><span>{{ $memberInfo->member_looking }}</li>
            @endif
            @if($memberInfo->member_help != '')
            <li><i class="fas fa-info-circle"></i><strong>Can Help In:</strong><span>{{ $memberInfo->member_help }}</li>
            @endif
            @if($memberInfo->achievements != '')
            <li><i class="fas fa-info-circle"></i><strong>Achievements:</strong><span>{{ $memberInfo->achievements }}</li>
            @endif
            @if($memberInfo->certifications != '')
            <li><i class="fas fa-info-circle"></i><strong>Certifications:</strong><span>{{ $memberInfo->certifications }}</li>
            @endif
            @if($memberInfo->is_raised_invest != '')
            <li><i class="fas fa-info-circle"></i><strong>Investment Name:</strong><span>{{ $memberInfo->invest_name }}</li>
            @endif
            @if($memberInfo->stage_id != '')
            <li><i class="fas fa-info-circle"></i><strong>Milestone:</strong><span>{{ $memberInfo->stage_name }}</li>
            @endif
            </li>
            <li><i class="fas fa-envelope"></i><strong>Email:</strong><span><a href="#">{{ $memberInfo->email_id }}</a></span></li>
            <li><i class="fas fa-phone-volume"></i><strong>Contact Number:</strong><span><a href="#">{{ $memberInfo->contact_no }}</a></span></li>

            @if($memberInfo->speech != '')

            <li><i class="fas fa-info-circle"></i><strong>Founder Pitch:</strong><span> @php

                                    $video_id = explode("?v=", $memberInfo->speech);
                                    $video_id = $video_id[1];
                                    $thumbnail="http://img.youtube.com/vi/".$video_id."/sddefault.jpg";

                                    $memberInfo->speech =getYoutubeEmbedUrl($memberInfo->speech);

                                @endphp

                                <a   onclick ="openVideoModal('{{$memberInfo->speech}}')">Click Here To View Video</a></li></span>
                
            @endif



            @if($memberInfo->website != '')
                <li><i class="fas fa-user"></i><strong>Website:</strong><span><a href="{{ $memberInfo->website }}">{{ $memberInfo->website }}</a></span></li>
            @endif

             @if($memberInfo->address != '')
            <li><i class="fas fa-map-marker-alt"></i><strong>Location:</strong><span>{{ $memberInfo->address }}</span></li>
            @endif
            
            </ul>
           
            </div>
        </div>

        @if($founderscount>0)

        @foreach($founders as $founder)
        <div class="founder-cont">
            <div class="founder-head">
            <h6>{{ $founder->name }}</h6>

            </div>
            
            <div class="founder-wrap">
                
                <div class="member-img">
                @php 
                    $profileImage = asset('public/front_end/images/profile-pic.png');
                    if($founder->image != '' && $founder->image != null) {
                        $founderImage = asset('public/uploads/founder_images/thumb/'. $founder->image);
                    }
                @endphp
                @if(isset($founderImage) && $founderImage != '')
                    <img src="{{ $founderImage }}" class="img-fluid" width="10px">
                @endif
                </div>

                <div class="member-info founder-info">
                   <!--  <ul>
                        <li><i class="fas fa-user"></i><strong>Name:</strong><span><a href="#">{{ $founder->name }}</a></span></li>
                    </ul> -->

                    <ul>
                        <li><i class="fas fa-info-circle"></i><strong>Profile:</strong><p>{{ $founder->profile }}</p></li>
                    </ul>

                </div>

            </div>
        </div>
        @endforeach

        @if(isset($buisness) && count($buisness)>0)
            <div class="product-cont">
                <div class="product-head">
                    <h6>Product/Services</h6>
                </div>

                <div class="col-md-12">
                    <div class="product-info">
                        <p>{{ $memberInfo->buisness_info }}</p>
                    </div>
                </div>
                <div class="product-wrap">
                    <div class="row">
                         <label>Product Images:</label>
                    @foreach($buisness as $buisnes)
                    
                        <div class="col-md-3">
                            <div class="product-img"> 
                            @php 
                                $profileImage = asset('public/front_end/images/profile-pic.png');
                                if($buisnes->image != '' && $buisnes->image != null) {
                                    $buisnessImage = asset('public/uploads/website_images/thumb/'. $buisnes->image);
                                }
                            @endphp
                            @if(isset($buisnessImage) && $buisnessImage != '')
                           
                                <figure>
                                    <img src="{{ $buisnessImage }}" class="img-fluid" height="10%">
                                    <figcaption>{{ $buisnes->caption }}</figcaption>
                                </figure>
                            @endif
                            </div>
                        </div>

                    @endforeach

                    </div>
                    <div class="row">

                    Product Videos:
                    @foreach($buisness as $buisnes)
                    
                        <div class="col-md-3">
                            <div class="product-video">
                           

                            @if(!empty($buisnes->buisness_video))

                                                 @php

                                                    $video_id1 = explode("?v=", $buisnes->buisness_video);

                                                    //print_r($video_id1);die;

                                                    if(count($video_id1)>0)
                                                    {
                                                        $video_id1 = $video_id1[1];

                                                        
                                                        $thumbnail2="http://img.youtube.com/vi/".$video_id1."/sddefault.jpg";

                                                        //echo $thumbnail2;die;

                                                        $buisnes->buisness_video=getYoutubeEmbedUrl($buisnes->buisness_video);
                                                    }
                                                    

                                                @endphp
                                                @if(!empty($thumbnail2))
                                                <a onclick ="openVideoModal('{{$buisnes->buisness_video}}')"><img src="{{$thumbnail2}}" width="60" height="140"></a>

                                                @endif

                                        @endif
                            </div>
                        </div>
                    
                    @endforeach
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="product-link">
                        <p>To know more please click here: <a href="{{ $memberInfo->website }}">{{ $memberInfo->website }}</a></p>
                    </div>
                
                </div>
               
            </div>

        @endif

        @if(!empty($company_videos))
         <div class="product-cont">
            <div class="product-head">
                <h6>Company Videos</h6>
                
            </div>
            <div class="product-wrap">
                <div class="row">
                @foreach($company_videos as $vv)
                    

                    @if(!empty($vv->company_video))

                                         @php

                                            $video_id = explode("?v=", $vv->company_video);

                                            if(count($video_id)>1)
                                            {
                                                $video_id = $video_id[1];
                                            $thumbnail="http://img.youtube.com/vi/".$video_id."/sddefault.jpg";

                                            Auth::user()->speech =getYoutubeEmbedUrl($vv->company_video->speech);
                                            }
                                            

                                        @endphp


                                        @if(!empty($thumbnail))


                                        <a onclick ="openVideoModal('{{$vv->company_video}}')"><img src="{{$thumbnail}}" width="60" height="140"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        @endif

                                        @endif
                @endforeach
                </div>
            </div>

        </div>

        @endif

        @endif

        @if($memberInfo->about_you != '')
        <div class="member-about">
            <h2>About</h2>
            {!! html_entity_decode($memberInfo->about_you, ENT_QUOTES) !!}
        </div>
        @endif
        <!-- <div class="member-post">
           
        </div> -->
        </div>
    
    
    @endif

    </div>

    <div id="myModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Video</h4>
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
<script type="text/javascript">

    function openVideoModal(video_url)

    {
         
        //alert(video_url);
        $("#cartoonVideo").attr('src', video_url);
            //alert(video_url);
        $("#myModal").modal('show');
    }

</script>
@endpush
@endsection

    