@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
  <h1>
    Edit Startup
    <!--small>it all starts here</small-->
  </h1>
  <!-- <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('users_list') }}">Members List</a></li>
    <li class="active">Update Member</li>
  </ol> -->
</section>
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}
  </div>
  @endif

  <div class="row">
    <div class="col-md-6">
      @can('admin-view')
      <a href="{{ route('users_list') }}" class="btn btn-primary"><i class="fa fa-users" aria-hidden="true"></i> All
        Startups</a>
      @endcan
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <!-- <h3 class="box-title">Update Member</h3> -->

          <div class="box-tools pull-right">

          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx"
            action="@if(isset($user_data) && !empty($user_data)){{ route('upd_user', array('utid' => $user_data->timestamp_id)) }}@endif"
            method="post" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Startup Name : <em>*</em></label>
                  <input type="text" name="member_company" class="form-control" placeholder="Enter Organization Name"
                    value="@if(isset($user_data) && !empty($user_data)){{ $user_data->member_company }}@endif">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Organization Type : </label>
                  <select name="company_type" class="form-control">
                    <option value="">Select Organization Type</option>
                    @if(!empty($companyTypeList))
                    @foreach($companyTypeList as $s)
                    <option value="{{ $s->id }}" @if( isset($user_data) && $user_data->company_type == $s->id )
                      selected="selected" @endif>{{ $s->company_type }}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Legal Status : </label>
                  <select name="legal_status" class="form-control">
                    <option value="">Select Legal Status</option>
                    @if(!empty($legalStatusList))
                    @foreach($legalStatusList as $s)
                    <option value="{{ $s->id }}" @if( isset($user_data) && $user_data->legal_status == $s->id )
                      selected="selected" @endif>{{ $s->legal_status }}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Organization Code(SHG Code/DPIIT No.) : </label>
                  <input type="text" name="company_code" class="form-control" placeholder="Enter Organization Code"
                    value="@if(isset($user_data) && !empty($user_data)){{ $user_data->company_code }}@endif">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Year of Incorporation : </label>
                  <input type="text" name="incorporation" class="form-control" placeholder="Enter Year of Incorporation"
                    value="@if(isset($user_data) && !empty($user_data)){{ $user_data->incorporation }}@endif">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Primary Contact : <em>*</em></label>
                  <input type="text" name="contact_name" class="form-control" placeholder="Enter Contact Name"
                    value="@if(isset($user_data) && !empty($user_data)){{ $user_data->contact_name }}@endif">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Milestone of Startup:</label>
                  <input type="text" class="form-control" name="milestone"
                    value="@if(isset($user_data) && !empty($user_data)){{ $user_data->milestone }}@endif" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Email-id : <em>*</em></label>
                  <input type="email" name="email_id" class="form-control" placeholder="Enter Email-Id"
                    value="@if(isset($user_data) && !empty($user_data)){{ $user_data->email_id }}@endif">
                  @if($errors->has('email_id'))
                  <span class="roy-vali-error"><small>{{$errors->first('email_id')}}</small></span>
                  @endif
                </div>
              </div>
            </div>

            <div class="row">

              <div class="col-md-4">
                <div class="form-group">
                  <label>Contact Number : </label>
                  <input type="text" name="contact_no" class="form-control onlyNumber"
                    placeholder="Enter Contact Number"
                    value="@if(isset($user_data) && !empty($user_data)){{ $user_data->contact_no }}@endif">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Mobile : <em>*</em></label>
                  <input type="text" name="mobile" class="form-control" placeholder="Enter Mobile No."
                    autocomplete="off"
                    value="@if(isset($user_data) && !empty($user_data)){{ $user_data->mobile }}@endif">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Startup current Stage : </label>
                  <select name="stage_id" class="form-control">
                    <option value="">Select Startup Stage</option>
                    @if(!empty($stageList))
                    @foreach($stageList as $s)
                    <option value="{{ $s->id }}" @if( isset($user_data) && $user_data->stage_id == $s->id )
                      selected="selected" @endif>{{ $s->stage_name }}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Country : <em>*</em></label>
                  <input type="text" name="country" class="form-control" placeholder="Enter Country" autocomplete="off"
                    value="@if(isset($user_data) && !empty($user_data)){{ $user_data->country }}@endif">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>State : </label>
                  <input type="text" name="state" class="form-control" placeholder="Enter State" autocomplete="off"
                    value="@if(isset($user_data) && !empty($user_data)){{ $user_data->state }}@endif">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>District : </label>
                  <input type="text" name="district" class="form-control" placeholder="Enter District"
                    autocomplete="off"
                    value="@if(isset($user_data) && !empty($user_data)){{ $user_data->district }}@endif">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>City/Block: : </label>
                  <input type="text" name="city" class="form-control" placeholder="Enter City" autocomplete="off"
                    value="@if(isset($user_data) && !empty($user_data)){{ $user_data->city }}@endif">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Pin Code : </label>
                  <input type="text" name="pincode" class="form-control" placeholder="Enter Pin Code" autocomplete="off"
                    value="@if(isset($user_data) && !empty($user_data)){{ $user_data->pincode }}@endif">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Specialization:</label>
                  <textarea class="form-control" name="member_spec"
                    placeholder="Specialization..">@if(isset($user_data) && !empty($user_data)){{ $user_data->city }}@endif</textarea>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Operational Presence:</label>
                  <textarea class="form-control" name="operational_presence"
                    placeholder="Operational Presence">@if(isset($user_data) && !empty($user_data)){{ $user_data->operational_presence }}@endif</textarea>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Market Reach:</label>
                  <textarea class="form-control" name="market_reach"
                    placeholder="Market Reach">@if(isset($user_data) && !empty($user_data)){{ $user_data->market_reach }}@endif</textarea>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>What are you looking for:</label>
                  <textarea class="form-control" name="member_looking"
                    placeholder="What are..">@if(isset($user_data) && !empty($user_data)){{ $user_data->member_looking }}@endif</textarea>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>What can you help in:</label>
                  <textarea class="form-control" name="member_help"
                    placeholder="What can you..">@if(isset($user_data) && !empty($user_data)){{ $user_data->member_help }}@endif</textarea>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Achievements:</label>
                  <textarea class="form-control" name="achievements"
                    placeholder="Achievements..">@if(isset($user_data) && !empty($user_data)){{ $user_data->achievements }}@endif</textarea>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Certifications:</label>
                  <textarea class="form-control" name="certifications"
                    placeholder="Certifications..">@if(isset($user_data) && !empty($user_data)){{ $user_data->certifications }}@endif</textarea>
                </div>
              </div>
              {{--<div class="col-md-4 raise-invest">
              <div class="form-group">
                <label for="is_raised_invest">Raised Investments:</label>
                        <input type="radio" name="is_raised_invest" onclick="change_hidden_answer_type_value(1)" id="is_raised_invest" value="1" 
                    @if(isset($user_data->is_raised_invest))
                        @if($user_data->is_raised_invest == 1) 
                            checked

                        @endif                      
                    @endif>Yes
                    <input type="radio" name="is_raised_invest" id="is_raised_invest" onclick="change_hidden_answer_type_value(0)"  value="0" 
                    @if(isset($user_data->is_raised_invest))
                        @if($user_data->is_raised_invest == 0) 
                            checked

                        @endif                      
                    @endif>No

                <input type="hidden" name="raise_check" id="raise_check" value="{{$user_data->is_raised_invest}}">
            </div>
        </div>--}}


        {{--Souvik change--}}
        <div class="col-md-12">

          <div id="invest_sec">

            @if (isset($company_investments) && count($company_investments)>0)
            @php
            $c=count($company_investments);
            $i=1;
            @endphp


            @foreach ($company_investments as $vv)
            <div id="invest_sec_{{$i}}">
              <div class="row">

                <div class="col-md-6">
                  <div class="founder-header">
                    <label>Investment Info </label>
                  </div>
                  <label>Source :</label>
                  <input type="text" name="company_source[]" class="form-control" id="company_source_{{$i}}"
                    placeholder="Source" value="@if(!empty($vv->source)){{$vv->source}}@endif">

                  <label>Instrument :</label>
                  <input type="text" name="company_instrument[]" class="form-control" id="company_instrument_{{$i}}"
                    placeholder="Instrument" value="@if(!empty($vv->instrument)){{$vv->instrument}}@endif">

                  <label>Value :</label>
                  <input type="text" name="company_value[]" class="form-control" id="company_value_{{$i}}"
                    placeholder="Value" value="@if(!empty($vv->value)){{$vv->value}}@endif">

                  @if ($i != 1)
                  <a id="rm-answer-{{$i}}" class="btn btn-danger btn-remove" onClick="remove_invest({{$i}})">Remove</a>
                  @else
                  <a id="ad-contact_person-{{$i}}" class="btn btn-success btn-add" onClick="clone_invest()">Add More</a>
                  <input type="hidden" name="answer_row_count" id="answer_row_count" value="{{$c}}" />
                  @endif
                </div>
              </div>
            </div>
            @php
            ++$i;
            @endphp
            @endforeach
            @else

            <div id="invest_sec_1">
              <div class="row">
                <div class="col-md-4 raise-invest">
                  <label for="is_raised_invest">Raised Investments:</label>
                  <input type="radio" name="is_raised_invest" onclick="change_hidden_answer_type_value(1)"
                    id="is_raised_invest" value="1" @if(isset($user_data->is_raised_invest))
                  @if($user_data->is_raised_invest == 1)
                  checked

                  @endif
                  @endif>Yes
                  <input type="radio" name="is_raised_invest" id="is_raised_invest"
                    onclick="change_hidden_answer_type_value(0)" value="0" @if(isset($user_data->is_raised_invest))
                  @if($user_data->is_raised_invest == 0)
                  checked

                  @endif
                  @endif>No



                  <input type="hidden" name="raise_check" id="raise_check" value="{{$user_data->is_raised_invest}}">
                  <div class="form-group" id="show_raise" style="display: none">

                    {{--<textarea class="form-control" name="invest_name" placeholder="Investment Info..">{{ $user->invest_name }}</textarea>--}}

                    <label> Source : </label>
                    <input type="text" name="company_source[]" class="form-control" id="company_source_1"
                      placeholder="Source">

                    <label> Instrument : </label>
                    <input type="text" name="company_instrument[]" class="form-control" id="company_instrument_1"
                      placeholder="Instrument">

                    <label> Value : </label>
                    <input type="text" name="company_value[]" class="form-control" id="company_value_1"
                      placeholder="Value">

                    <a id="ad-contact_person-3" class="btn btn-success btn-add" onClick="clone_invest()">Add More</a>
                    <input type="hidden" name="answer_row_count" id="answer_row_count" value="1" />


                  </div>
                </div>




              </div>
            </div>
            @endif
          </div>

        </div>


        <div class="col-md-12">
          <div class="form-group" id="show_raise" style="display: none">
            <label>Investment Info :</label>
            <textarea class="form-control" name="invest_name"
              placeholder="Investment Info..">@if(isset($user_data) && !empty($user_data)){{ $user_data->invest_name }}@endif</textarea>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Website : <em>*</em></label>
            <input type="text" name="website" class="form-control" placeholder="Enter Website" autocomplete="off"
              value="@if(isset($user_data) && !empty($user_data)){{ $user_data->website }}@endif">
          </div>
        </div>
        <!--
            <div class="col-md-4">
              <div class="form-group">
                <label>Business Type : </label>
                <select name="legal_status" class="form-control">
                  <option>Select Business Type</option>
                  <option value="pvt" @if( isset($user_data) && $user_data->legal_status =='pvt' ) selected="selected" @endif>Pvt Ltd</option>
                  <option value="partnership" @if( isset($user_data) && $user_data->legal_status == 'partnership' ) selected="selected" @endif>Partnership</option>
                </select>
              </div>
            </div>
-->
        <div class="col-md-4">
          <div class="form-group">
            <label>Profile Info : <em>*</em></label>
            <!-- <input type="text" name="profile_info" class="form-control" placeholder="Enter Profile Info"  autocomplete="off" value="@if(isset($user_data) && !empty($user_data)){{ $user_data->profile_info }}@endif"> -->
            <textarea name="profile_info" class="form-control" placeholder="Enter Profile Info"
              autocomplete="off"> @if(isset($user_data) && !empty($user_data)){{ $user_data->profile_info }}@endif</textarea>
          </div>
        </div>


        <div class="col-md-4">
          <div class="row">
            <div class="col-md-9">
              <div class="form-group">
                <label>Profile Image :</label>
                <div class="custom-file">
                  <input type="file" name="image" id="user_image" class="custom-file-input">
                  <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                  <span class="roy-vali-error" id="ar-user_image-err"></span>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                @if(isset($user_data) && !empty($user_data) && $user_data->image != '' && $user_data->image != null)
                @php
                $imageURL = asset('public/uploads/user_images/thumb/'.$user_data->image);
                @endphp
                <a class="fancybox" data-fancybox="images" href="{{ $imageURL}}"> <img src="{{ $imageURL }}"
                    id="user_image_preview" class="ar_img_preview" data="{{ $imageURL }}"></a>
                @else
                <a class="fancybox" data-fancybox="images" href="{{ asset('public/images/user-avatar.png') }}">
                  <img src="{{ asset('public/images/user-avatar.png') }}" id="user_image_preview" class="ar_img_preview"
                    data="{{ asset('public/images/user-avatar.png') }}"></a>
                @endif
                <i class="fa fa-times base-red libtn" id="user_image_rm"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label>Founder Pitch :</label>
            <div class="custom-file">
              <input type="file" name="speech" class="custom-file-input" id="speech"
                aria-describedby="inputGroupFileAddon02">
              <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
            </div>

            @if(isset($user_data))

            @php $profileImage = asset('public/uploads/user_images/'.$user_data->speech); @endphp
            @if(isset($user_data->speech) && $user_data->speech!='')

            <br>
            <a class="fancybox" data-fancybox="images" href="{{ $profileImage}}" download> Download Company Pitch</a>

            @endif

            @endif
          </div>


        </div>
      </div>
      <div class="row">
        <!--
            <div class="col-md-6">
                <div class="founder-header">
                    <label>Founder</label>        
                </div>
                <div id="answer_sec">
                    @if (isset($founders) && count($founders)>0)
                        @php
                            $c=count($founders);
                            $i=1;
                        @endphp
                        @foreach ($founders as $ca)
                    <div id="answer_sec_{{$i}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Name : </label>
                                    <input type="text" name="founder_name[]" class="form-control" id="founder_name_{{$i}}"  value="{{$ca->name}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Profile :</label>
                                    <textarea class="form-control" name="founder_profile[]" id="founder_profile_{{$i}}">{{$ca->profile}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Image:</label>
                                    <div class="custom-file">
                                    <input type="file" name="founder_img[]" class="custom-file-input" id="founder_img__{{$i}}" aria-describedby="inputGroupFileAddon02">
                                    <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                                    <input type="hidden" name="founder_img_hidden[]" id="founder_img_hidden{{$i}}" value="{{$ca->image}}" />

                                    @php 
                                        if($ca->image != '' && $ca->image != null) {
                                            $founderImage = asset('public/uploads/founder_images/thumb/'.$ca->image);
                                        }
                                        else
                                        {
                                            $founderImage = asset('public/uploads/founder_images/thumb/promoters_images/noimage.png'); 
                                        }
                                    @endphp
                                    @if(isset($founderImage) && $founderImage != '')
                                         <a class="fancybox" data-fancybox="images" href="{{ $founderImage}}"><img src="{{ $founderImage }}" class="browse-img"></a>
                                    @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                @if ($i != 1) 
                                <a  id="rm-answer-{{$i}}" class="btn btn-danger" onClick="remove_answer({{$i}})">X</a>
                                @else
                                <a id="ad-contact_person-{{$i}}" class="btn btn-success add-founders" onClick="clone_answer()">Add More</a>
                                <input type="hidden" name="answer_row_count" id="answer_row_count" value="{{$c}}" />  
                                @endif 
                            </div>
                        </div>
                    </div> 
                    @php
                    ++$i;
                    @endphp
                    @endforeach
                    @else   
                    <div id="answer_sec_1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Name : </label>
                                    <input type="text" name="founder_name[]" class="form-control" id="founder_name_1"  value="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                <label>Profile :</label>
                                    <textarea class="form-control" name="founder_profile[]" id="founder_profile_1"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                <label>Image:</label>
                                    <div class="custom-file">
                                    <input type="file" name="founder_img[]" class="custom-file-input" id="founder_img_1" aria-describedby="inputGroupFileAddon02">
                                    <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <a id="ad-answer-1" class="btn btn-success add-founders" onClick="clone_answer()">Add More</a>
                                <input type="hidden" name="answer_row_count" id="answer_row_count" value="1" />        
                            </div>
                        </div>
                    </div>
                @endif 
                </div>
            </div>
-->

        <div class="col-md-6">
          <div class="founder-header">
            <label>Product/Services</label>
          </div>
          <div id="service_sec">
            @if (isset($buisness) && count($buisness)>0)
            @php
            $c=count($buisness);
            $i=1;
            @endphp
            @foreach ($buisness as $va)

            <div id="service_sec_{{$i}}">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Image:</label>
                    <div class="custom-file">
                      <input type="file" name="buisness_img[]" class="custom-file-input" id="buisness_img_{{$i}}"
                        aria-describedby="inputGroupFileAddon02">
                      <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                      <input type="hidden" name="buisness_img_hidden[]" id="buisness_img_hidden{{$i}}"
                        value="{{$va->image}}" />
                      @php
                      if($va->image != '' && $va->image != null) {
                      $serviceImage = asset('public/uploads/website_images/thumb/'.$va->image);
                      }
                      @endphp
                      @if(isset($serviceImage) && $serviceImage != '')
                      <a class="fancybox" data-fancybox="images" href="{{ $serviceImage}}"><img
                          src="{{ $serviceImage }}" class="browse-img"></a>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Caption : </label>
                    <input type="text" name="buisness_caption[]" class="form-control" id="buisness_caption_{{$i}}"
                      value="@if(!empty($va->caption)){{$va->caption}}@endif">
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <label>Video :{{$va->buisness_video}} </label>
                    <input type="text" name="buisness_video[]" class="form-control" id="buisness_video_{{$i}}"
                      placeholder="Enter YouTube or Vimeo Video Link"
                      value="@if(!empty($va->buisness_video)){{$va->buisness_video}}@endif">
                  </div>



                  @if(!empty($va->buisness_video))

                  @php

                  $video_url = $va->buisness_video;


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

                  $va->buisness_video=getYoutubeEmbedUrl($va->buisness_video);


                  @endphp
                  @if(!empty($thumbnail3))
                  <a onclick="openVideoModal('{{$va->buisness_video}}')"><img src="{{$thumbnail3}}"
                      class="pitch_two"></a>

                  @endif

                  @endif
                </div>
                <!-- <div class="col-md-12">
                            <div class="form-group">
                                <label>Webisite :</label>
                                <textarea class="form-control" name="buisness_website[]" id="buisness_website_{{$i}}">{{$va->website}}</textarea>
                            </div>
                        </div> -->
                <div class="col-md-12">
                  @if ($i != 1)
                  <a id="rm-answer-{{$i}}" class="btn btn-danger" onClick="remove_service({{$i}})">X</a>
                  @else
                  <a id="ad-contact_person-{{$i}}" class="btn btn-success add-services" onClick="clone_service()">Add
                    More</a>
                  <input type="hidden" name="answer_row_count" id="answer_row_count" value="{{$c}}" />
                  @endif
                </div>
              </div>
            </div>
            @php
            ++$i;
            @endphp
            @endforeach
            @else
            <div id="service_sec_1">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Image:</label>
                    <div class="custom-file">
                      <input type="file" name="buisness_img[]" class="custom-file-input" id="buisness_img_1"
                        aria-describedby="inputGroupFileAddon02">
                      <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                    </div>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <label>Caption : </label>
                    <input type="text" name="buisness_caption[]" class="form-control" id="buisness_caption_1" value="">
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <label>Video : </label>
                    <input type="text" name="buisness_video[]" class="form-control" id="buisness_video"
                      placeholder="Enter YouTube or Vimeo Video Link" value="">
                  </div>
                </div>

                <!-- <div class="col-md-12">
                            <div class="form-group">
                            <label>Website :</label>
                                <textarea class="form-control" name="buisness_website[]" id="buisness_website_1"></textarea>
                            </div>
                        </div> -->
                <div class="col-md-12">
                  <a id="ad-answer-2" class="btn btn-success add-services" onClick="clone_service()">Add More</a>
                  <input type="hidden" name="answer_row_count" id="answer_row_count" value="1" />
                </div>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>

      <div class="row">

        <div class="col-sm-12 col-md-12">
          <div class="founder-header">
            <label>Company Video</label>
          </div>

          @if (isset($company_videos) && count($company_videos)>0)
          @php
          $c=count($company_videos);
          $i=1;
          @endphp
          @foreach ($company_videos as $vv)


          <div id="video_sec">
            <div id="video_sec_{{$i}}">
              <div class="row">

                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Link : </label>
                    <input type="text" name="company_video[]" class="form-control" id="company_video_{{$i}}"
                      placeholder="Enter YouTube or Vimeo Video Link"
                      value="@if(!empty($vv->company_video)){{$vv->company_video}}@endif">
                  </div>



                  @if(!empty($vv->company_video))

                  @php

                  $video_url = $vv->company_video;


                  $url =videoType($video_url);

                  if($url=='youtube')
                  {
                  $video_id = extractVideoID($video_url);


                  $thumbnail2 = getYouTubeThumbnailImage($video_id);
                  }
                  else if($url=='vimeo')
                  {
                  $video_id = getVimeoId($video_url);


                  $thumbnail2 = getVimeoThumb($video_id);
                  }

                  $vv->company_video =getYoutubeEmbedUrl($vv->company_video);


                  @endphp


                  @if(!empty($thumbnail2))


                  <a onclick="openVideoModal('{{$vv->company_video}}')"><img src="{{$thumbnail2}}"
                      class="pitch_two"></a>
                  @endif

                  @endif
                </div>

                <div class="col-sm-12 col-md-6">
                  @if ($i != 1)
                  <a id="rm-answer-{{$i}}" class="btn btn-danger" onClick="remove_video({{$i}})">X</a>
                  @else
                  <a id="ad-contact_person-{{$i}}" class="btn btn-success add-services" onClick="clone_video()">Add
                    More</a>
                  <input type="hidden" name="answer_row_count" id="answer_row_count" value="{{$c}}" />
                  @endif
                </div>
              </div>
            </div>
          </div>


          @endforeach



          @else
          <div id="video_sec">
            <div id="video_sec_1">
              <div class="row">

                <div class="col-sm-12 col-md-6">
                  <div class="form-group">
                    <label>Link : </label>
                    <input type="text" name="company_video[]" class="form-control" id="company_video"
                      placeholder="Enter YouTube or Vimeo Video Link" value="">
                  </div>
                </div>

                <div class="col-sm-12 col-md-6">
                  <a id="ad-answer-3" class="btn btn-success add-services" onClick="clone_video()">Add More</a>
                  <input type="hidden" name="answer_row_count" id="answer_row_count" value="1" />
                </div>
              </div>
            </div>

            @endif


          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Industry : <em>*</em></label>
                <select name="industry_id[]" class="form-control" multiple="">
                  <!-- <option>Select Industry Category</option> -->

                  @if(!empty($industryList))
                  @foreach($industryList as $s)
                  <option value="{{ $s->id }}" @if( isset($memberBusiness) && in_array($s->id, $memberBusiness))
                    selected="selected" @endif>{{ $s->industry_category }}</option>
                  @endforeach
                  @endif
                </select>
              </div>
            </div>
            <div class="col-md-6">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Service Locations : <em>*</em></label>
                <select name="location_id[]" class="form-control" multiple="">
                  <!-- <option>Select Industry Category</option> -->

                  @if(!empty($locationsList))
                  @foreach($locationsList as $s)
                  <option value="{{ $s->id }}" @if( isset($memberLocations) && in_array($s->id, $memberLocations))
                    selected="selected" @endif
                    >{{ $s->name }}</option>
                  @endforeach
                  @endif
                </select>
              </div>
            </div>
            <div class="col-md-6">
            </div>
          </div>


          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Programme : </label>
                <select name="program_id[]" class="form-control" multiple="">
                  <option>Select Program</option>

                  @if(!empty($programme))
                  @foreach($programme as $s)
                  <option value="{{ $s->id }}" @if( isset($memberPrograms) && in_array($s->id, $memberPrograms))
                    selected="selected" @endif >{{ $s->name }}
                  </option>
                  @endforeach
                  @endif
                </select>
              </div>
            </div>
          </div>




          <?php 
          //dd($memberBusiness);
          ?>


          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Save and Update">
              </div>
            </div>
          </div>
          </form>
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
                <iframe id="cartoonVideo" width="560" height="315" src="//www.youtube.com/embed/YE7VzlLtp-4"
                  frameborder="0" allowfullscreen></iframe>
              </div>
            </div>
          </div>
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
  $( function () {
    $( "body" ).on( 'keypress', '.onlyNumber', function ( evt ) {
      var charCode = ( evt.which ) ? evt.which : event.keyCode
      if ( charCode > 31 && ( charCode < 48 || charCode > 57 ) )
        return false;
      return true;
    } );
    $( '#role_ids' ).select2( {
      placeholder: "Select a Role(s)"
    } );
  } );
  $.validator.addMethod( 'logosize', function ( value, element, param ) {
    return this.optional( element ) || ( element.files[ 0 ].size <= param )
  }, 'File size must be less than 2mb.' );
  $( '#frmx' ).validate( {
    errorElement: 'span',
    errorClass: 'roy-vali-error',
    rules: {

      'role_ids[]': {
        required: true
      },
      first_name: {
        required: true,
        minlength: 3
      },
      last_name: {
        required: true,
        minlength: 2
      },
      email_id: {
        required: true,
        email: true
      },
      contact_no: {
        maxlength: 12,
        digits: true
        //number: true
      },
      status: {
        required: true
      },
      image: {
        extension: "jpg|jpeg|png|gif|svg",
        logosize: 2000000,
      },
      stage_id: {
        required: true,
      },
    },
    messages: {

      'role_ids[]': {
        required: 'Please Select Role.'
      },
      first_name: {
        required: 'Please Enter First Name.'
      },
      last_name: {
        required: 'Please Enter Last Name.'
      },
      email_id: {
        required: 'Please Enter Email-id.',
        email: 'Please Enter Valid Email-id.'
      },
      image: {
        extension: 'Please upload any image file.'
      },
      stage_id: {
        required: 'Stagename is required..'
      },
    }
  } );
  $( function () {

    $( '.libtn' ).hide();
    $( "#user_image" ).change( 'click', function () {
      Ari_USER_IMAGE_Preview( this );
    } );

    function Ari_USER_IMAGE_Preview( input_fileupload ) {
      if ( input_fileupload.files && input_fileupload.files[ 0 ] ) {
        $( '#user_image_rm' ).show();
        var fs = input_fileupload.files[ 0 ].size;
        if ( fs <= 2000000 ) {
          var fileName = input_fileupload.files[ 0 ].name;
          var ext = fileName.split( '.' ).pop().toLowerCase();
          if ( ext == "jpg" || ext == "png" || ext == "jpeg" || ext == "gif" ) {
            var reader = new FileReader();
            reader.onload = function ( e ) {
              $( '#user_image_preview' ).attr( 'src', e.target.result );
              $( "#ar-user_image-err" ).html( '' );
            }

            reader.readAsDataURL( input_fileupload.files[ 0 ] );
          } else {
            //alert('Upload .jpg,.png Image only');
            $( "#ar-user_image-err" ).html( 'Choose only jpg, png, gif image.' );
          }
        } else {
          //alert('Upload Less Than 200KB Photo');
          $( "#ar-user_image-err" ).html( 'Choose less than 2mb image size.' );
        }
      }
    }

    $( '#user_image_rm' ).on( 'click', function () {
      $( '#user_image_preview' ).attr( 'src', $( '#user_image_preview' ).attr( 'data' ) );
      $( this ).hide();
      $( "#ar-user_image-err" ).html( '' );
      $( '#user_image' ).val( '' );
      $( '#user_image-error' ).hide();
    } );
  } );
</script>
<script type="text/javascript">
  $( document ).ready( function () {
    $( ".fancybox" ).fancybox();
    $i = 1;
    $( '.add-founder' ).click( function () {
      $( '.founder' ).clone().appendTo( '.founder-list' ).removeAttr( 'class', 'founder' ).addClass(
        'founders' + $i ).append(
        '<div class="form-group"><a class="btn remove-founder btn-danger" onclick="removeItem(' + $i +
        ')">Remove</a></div>' );

      $i++;
    } );

    //alert($("#is_raised_invest").val());

    if ( $( "#raise_check" ).val() == 1 ) {
      $( "#show_raise" ).show();
    } else {
      $( "#show_raise" ).hide();
    }



  } );

  function removeItem( $j ) {
    //alert($j);
    $( ".founders" + $j ).remove();
  }

  function clone_answer() {

    var id = $( "#answer_row_count" ).val();
    var new_id = parseInt( id ) + 1;
    $( "#answer_row_count" ).val( new_id );
    var table_row = "";

    table_row += '<div id="answer_sec_' + new_id + '">';
    table_row += '<div class="row">';
    table_row += '<div class="col-md-12">';
    table_row += '<div class="form-group">';
    table_row += '<label>Name : </label>';
    table_row += '<input type="text" name="founder_name[]" id="founder_name_' + new_id +
      '" class="form-control" value="" /> ';
    table_row += '</div>';
    table_row += '</div>';
    table_row += '<div class="col-md-12">';
    table_row += '<div class="form-group">';
    table_row += '<label>Profile :</label>';
    table_row += '<textarea class="form-control" name="founder_profile[]" id="founder_profile_' + new_id +
      '"></textarea>';

    table_row += '</div>';
    table_row += '</div>';
    table_row += '<div class="col-md-12">';
    table_row += '<div class="form-group">';
    table_row += '<label>Image :</label>';
    table_row +=
      ' <div class="custom-file"><input type="file" name="founder_img[]" class="custom-file-input" id="founder_img' +
      new_id +
      '" aria-describedby="inputGroupFileAddon02"><label class="custom-file-label" for="inputGroupFile02">Choose file</label></div>';



    table_row += '</div>';
    table_row += '</div>';
    table_row += '<div class="col-md-12">';
    table_row += '<a id="rm-answer-' + new_id + '"  class="btn btn-danger btn-remove" onClick="remove_answer(\'' +
      new_id + '\')">';
    table_row += 'Remove';
    table_row += '</div>';
    table_row += '</div>';
    table_row += '</div>';


    $( "#answer_sec" ).append( table_row );

  }

  function remove_answer( id ) {


    $( "#answer_sec_" + id ).remove();


  }

  function clone_service() {

    var id = $( "#answer_row_count" ).val();
    var new_id = parseInt( id ) + 1;
    $( "#answer_row_count" ).val( new_id );
    var table_row = "";

    table_row += '<div id="service_sec_' + new_id + '">';
    table_row += '<div class="row">';
    table_row += '<div class="col-md-12">';
    table_row += '<div class="form-group">';
    table_row += '<label>Image :</label>';
    table_row +=
      ' <div class="custom-file"><input type="file" name="buisness_img[]" class="custom-file-input" id="buisness_img_' +
      new_id +
      '" aria-describedby="inputGroupFileAddon02"><label class="custom-file-label" for="inputGroupFile02">Choose file</label></div>';



    table_row += '</div>';
    table_row += '</div>';
    table_row += '<div class="col-md-12">';
    table_row += '<div class="form-group">';
    table_row += '<label>Caption : </label>';
    table_row += '<input type="text" name="buisness_caption[]" id="buisness_caption_' + new_id +
      '" class="form-control" value="" /> ';
    table_row += '</div>';
    table_row += '</div>';
    table_row += '<div class="col-md-12">';
    table_row += '<div class="form-group">';
    table_row += '<label>Video : </label>';
    table_row += '<input type="text" name="buisness_video[]" id="buisness_video_' + new_id +
      '" class="form-control" placeholder="Enter YouTube or Vimeo Video Link"  value="" /> ';
    table_row += '</div>';
    table_row += '</div>';
    /*table_row += '<div class="col-md-12">';
    table_row += '<div class="form-group">';
    table_row += '<label>Website :</label>';
    table_row += '<textarea class="form-control" name="buisness_website[]" id="buisness_website_' + new_id + '"></textarea>';



    table_row += '</div>';
    table_row += '</div>';*/

    table_row += '<div class="col-md-12">';
    table_row += '<a id="rm-answer-' + new_id + '"  class="btn btn-danger btn-remove" onClick="remove_service(\'' +
      new_id + '\')">';
    table_row += 'Remove';
    table_row += '</div>';
    table_row += '</div>';
    table_row += '</div>';


    $( "#service_sec" ).append( table_row );

  }

  function remove_service( id ) {


    $( "#service_sec_" + id ).remove();


  }

  function clone_video() {



    var id = $( "#answer_row_count" ).val();
    var new_id = parseInt( id ) + 1;
    $( "#answer_row_count" ).val( new_id );
    var table_row = "";

    table_row += '<div id="video_sec_' + new_id + '">';
    table_row += '<div class="row">';
    table_row += '<div class="col-md-12">';
    table_row += '<div class="form-group">';
    table_row += '<label>Link : </label>';
    table_row += '<input type="text" name="company_video[]" id="company_video_' + new_id +
      '" class="form-control"  placeholder="Enter YouTube or Vimeo Video Link"/> ';
    table_row += '</div>';
    table_row += '</div>';
    /*table_row += '<div class="col-md-12">';
    table_row += '<div class="form-group">';
    table_row += '<label>Website :</label>';
    table_row += '<textarea class="form-control" name="buisness_website[]" id="buisness_website_' + new_id + '"></textarea>';



    table_row += '</div>';
    table_row += '</div>';*/

    table_row += '<div class="col-md-12">';
    table_row += '<a id="rm-answer-' + new_id + '"  class="btn btn-danger btn-remove" onClick="remove_video(\'' +
      new_id + '\')">';
    table_row += 'Remove';
    table_row += '</div>';
    table_row += '</div>';
    table_row += '</div>';

    $( "#video_sec" ).append( table_row );

  }

  function remove_video( id ) {


    $( "#video_sec_" + id ).remove();


  }

  function change_hidden_answer_type_value( id ) {

    if ( id == 1 ) {
      $( "#show_raise" ).show();
    } else {
      $( "#show_raise" ).hide();
    }





  }

  function openVideoModal( video_url )

  {

    $( "#cartoonVideo" ).attr( 'src', video_url );
    //alert(video_url);
    $( "#myModal" ).modal( 'show' );
  }

  function clone_invest() {

    var id = $( "#answer_row_count" ).val();
    var new_id = parseInt( id ) + 1;
    $( "#answer_row_count" ).val( new_id );
    var table_row = "";


    table_row += '<div id="invest_sec_' + new_id + '">';
    table_row += '<div class="row">';
    table_row += '<div class="col-md-4">';
    table_row += '<div class="form-group">';
    table_row += '<label>Source : </label>';
    table_row += '<input type="text" name="company_source[]" id="company_source_' + new_id +
      '" class="form-control"  placeholder="Source"/> ';
    table_row += '</div>';
    table_row += '</div>';

    table_row += '<div class="col-md-4">';
    table_row += '<div class="form-group">';
    table_row += '<label>Instrument : </label>';
    table_row += '<input type="text" name="company_instrument[]" id="company_instrument_' + new_id +
      '" class="form-control"  placeholder="Instrument"/> ';
    table_row += '</div>';
    table_row += '</div>';


    table_row += '<div class="col-md-4">';
    table_row += '<div class="form-group">';
    table_row += '<label>Value : </label>';
    table_row += '<input type="text" name="company_value[]" id="company_value_' + new_id +
      '" class="form-control"  placeholder="Value"/> ';
    table_row += '</div>';
    table_row += '</div>';




    table_row += '<div class="col-md-12">';
    table_row += '<a id="rm-answer-' + new_id + '"  class="btn btn-danger btn-remove" onClick="remove_invest(\'' +
      new_id + '\')">';
    table_row += 'Remove';
    table_row += '</div>';
    table_row += '</div>';
    table_row += '</div>';
    table_row += '</div>';

    $( "#invest_sec" ).append( table_row );



  }

  function remove_invest( id ) {


    $( "#invest_sec_" + id ).remove();


  }
</script>
@endpush