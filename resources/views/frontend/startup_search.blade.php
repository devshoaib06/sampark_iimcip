@extends('frontend.layouts.app')
@section('content')

@if (isset($users) && !empty($users) )
        
	<div class="col-sm-12">
        <div class="postCard">
			<div class="postWrap startup-post">
			
				
				@php 
                            $profileImage = $defaultImage = $authUserImage = asset('public/front_end/images/profile-pic.png');
                            if(isset($users) && !empty($users) && $users->image != null) {
                                $profileImage = asset('public/uploads/user_images/thumb/'. $users->image);
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
				<div class="postUser d-flex">
                            <div class="userImg">
                                <img src="{{ $profileImage }}" class="img-fluid" />
                         </div>
						 <div class="userInfo">
                                <div class="row">
                                    <div class="col-lg-7">

                                        
                                        <h3>
									@if(isset($users) && !empty($users))
                                    <a href="{{ route('front.user.memberProfile', array('member_company' => $users->slug)) }}" target="_blank">{{$users->member_company}}</a> 
                                    @endif
									</h3>
                                    </div>
								 <div class="col-lg-5">
                                      
                                        <div class="userReply d-flex justify-end">
                                            <div><p><i class="fa fa-list-alt"></i> <strong class="text-primary">{{$users['number_of_post']}}</strong> Posts</p></div>
                                            <div><p><i class="far fa-comment-alt"></i> <strong class="text-primary">{{$users['number_of_reply']}}</strong> Replies</p></div>
                                        </div>
                                        
                                    </div>
                                </div>  
  
                                <hr>
                                <!-- @if(isset($memberInfo) && !empty($memberInfo)) 
                                    <h4><a href="{{ route('front.user.account') }}?search={{ $memberInfo->member_company }}">{{ $memberInfo->member_company }}</a></h4>
                                @endif -->
                                @if(isset($users->allIndustryIds) && !empty($users->allIndustryIds) && count($users->allIndustryIds))
                                <div class="member-info" style="padding-left: 0; width: 100%;">
                                    <table width="100%" cellpadding="5">
                                        <tr>
                                            <td  valign="top" style="width: 147px"><i class="fas fa-building text-primary"></i> <strong>Industry Verticals:</strong></td>
                                            <td>
                                        @php $i = 0; $tot = count($users->allIndustryIds); @endphp
                                        @foreach($users->allIndustryIds as $v)
                                            @if(isset($v->industryInfo) && !empty($v->industryInfo)) 
                                                <span>{{ trim($v->industryInfo->industry_category) }}</span>@if($i < $tot - 1), @endif
                                            @endif
                                        @php $i++; @endphp
                                        @endforeach
                                    @endif</td>
                                        </tr>

                                        @if($users->member_spec != '')
                                        <tr>
                                            <td valign="top"><i class="fas fa-info-circle text-primary"></i> <strong>Specialist In:</strong></td>
                                            <td><span>{{ $users->member_spec }}</span></td>
                                        </tr>
                                        @endif
                                        @if(isset($user['founders']) && !empty($user['founders']) && count($user['founders']))
                                        <tr>
                                            <td valign="top"><i class="fas fa-user text-primary"></i> <strong>Founder:</strong></td>
                                            <td>
                                        @php $i = 0; $tot = count($user['founders']); @endphp
                                        @foreach($user['founders'] as $v1)
                                        
                                            @if(isset($v1->name) && !empty($v1->name)) 
                                                <span>{{ trim($v1->name) }}</span>@if($i < $tot - 1), @endif
                                            @endif
                                        @php $i++; @endphp
                                        @endforeach
                                    @endif</td>
                                        </tr>
                                    </table>
                                <p class="text-left">
                                    
                                </p>
                                <div class="clearfix"></div>

                                </p>
                                    



                                <div class="clearfix"></div>
                                <p class="text-left">
                                    
                                </p>
                            </div>								
                                    

       
    @else
    <div class="col-sm-12">
        <div class="postCard">
            <div class="postWrap">
                <div class="postTop">
                    <p>Sorry! No startup found.</p>
                </div>
            </div>
        </div>
    </div>
    @endif
	

   
                

@endsection