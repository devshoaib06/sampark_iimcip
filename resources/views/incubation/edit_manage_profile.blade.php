@extends('incubation.layouts.app')
@section('accountRightMenu')
    @include('incubation.includes.account_right_menu')
@endsection
@section('content')

    <div class="row">
        @if (Session::has('msg') && Session::has('msg_class'))
            <div class="col-sm-12">
                <div class="postCard">
                    <div class="{{ Session::get('msg_class') }}" style="margin-bottom: 0;">
                        {{ Session::get('msg') }}
                    </div>
                </div>
            </div>
        @endif
        @php
            //$startup_id = startup_id(Auth::user()->id);
            //echo $startup_id;
            $user = DB::table('users')
                ->where('id', '=', $startUpId)
                ->first();
            
            //dd($user);
            
        @endphp

        <div class="col-sm-12">
            <div class="postCard manage-wrap">
                <div class="postWrap">
                    <div class="pwdbox">
                        <h3>Manage Company Profilesssss</h3>
                        <form name="frm_pfupd" id="frm_pfupd" action="{{ route('startup.updprof', [$user->id]) }}"
                            method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Industry Verticals:</label>
                                        <div>
                                            <select class="form-control indusCatIds" name="industry_category_id[]"
                                                id="indusCatIds" multiple="multiple" required="required"
                                                style="width: 100%;">
                                                <option value="">Select Industry Verticals</option>
                                                @if (isset($industry_category) && count($industry_category))
                                                    @foreach ($industry_category as $v)
                                                        <option value="{{ $v->id }}"
                                                            @if (isset($currentIndusCats) && !empty($currentIndusCats) && in_array($v->id, $currentIndusCats)) selected="selected" @endif>
                                                            {{ $v->industry_category }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Organization Type:</label>
                                        <div>
                                            <select class="form-control " name="company_type" id="company_type"
                                                required="required" style="width: 100%;">
                                                <option value="">Select Organization Type</option>
                                                @if (isset($company_type) && count($company_type))
                                                    @foreach ($company_type as $v)
                                                        <option value="{{ $v->id }}"
                                                            @if ($user->company_type == $v->id) selected="selected" @endif>
                                                            {{ $v->company_type }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>Organization Name:</label>
                                        <input type="text" class="form-control" name="member_company" required="required"
                                            value="{{ $user->member_company }}" />
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>Organization Code(SHG Code/DPIIT No.):</label>
                                        <input type="text" class="form-control" name="company_code" required="required"
                                            value="{{ $user->company_code }}" />
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>Organization Stage:</label>
                                        <select class="form-control" name="stage_id" required="required">
                                            <option value="">Select Startup Stage</option>
                                            @if (isset($stage) && count($stage))
                                                @foreach ($stage as $v)
                                                    <option value="{{ $v->id }}"
                                                        @if ($user->stage_id == $v->id) selected="selected" @endif>
                                                        {{ $v->stage_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="col-md-12 col-lg-6">
                         <div class="form-group">
                             <label>Milestone of Startup:</label>
                             <input type="text" class="form-control" name="milestone"  value="{{ Auth::user()->milestone }}" />
                         </div>
                         </div> -->
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>Legal Status:</label>
                                        <select class="form-control" name="legal_status">
                                            <option value="">Select Legal Status</option>
                                            @if (isset($legal_status) && count($legal_status))
                                                @foreach ($legal_status as $v)
                                                    <option value="{{ $v->id }}"
                                                        @if ($user->legal_status == $v->id) selected="selected" @endif>
                                                        {{ $v->legal_status }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="col-md-12 col-lg-6">
                         <div class="form-group">
                             <label>Company Name:</label>
                             <input type="text" class="form-control" name="company_name" required="required" value="{{ $user->company_name }}" />
                         </div>
                         </div> -->
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>Year of Incorporation:</label>
                                        <input type="text" class="form-control" name="incorporation" required="required"
                                            value="{{ $user->incorporation }}" />
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>Company Email:</label>
                                        <input type="email" class="form-control" name="email_id" required="required"
                                            value="{{ $user->email_id }}" />
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>Company Phone:</label>
                                        <input type="text" class="form-control onlyNumber" name="company_mobile"
                                            required="required" value="{{ $user->company_mobile }}" />
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>Website:</label>
                                        <input type="text" class="form-control" name="website"
                                            value="{{ $user->website }}" />
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>Country:</label>
                                        <input type="text" class="form-control" name="country" required="required"
                                            value="{{ $user->country }}" />
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>State:</label>
                                        <input type="text" class="form-control" name="state" required="required"
                                            value="{{ $user->state }}" />
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>District:</label>
                                        <input type="text" class="form-control" name="district" required="required"
                                            value="{{ $user->district }}" />
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>City/Block:</label>
                                        <input type="text" class="form-control" name="city" required="required"
                                            value="{{ $user->city }}" />
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>Address:</label>
                                        <textarea class="form-control" name="address" placeholder="Address..">{{ $user->address }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>Pin Code:</label>
                                        <input type="text" class="form-control" name="pincode" required="required"
                                            value="{{ $user->pincode }}" />
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>Specialization:</label>
                                        <textarea class="form-control" name="member_spec" placeholder="Specialist..">{{ $user->member_spec }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>Operational Presence:</label>
                                        <textarea class="form-control" name="operational_presence" placeholder="Operational Presence">{{ $user->operational_presence }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>Market Reach:</label>
                                        <textarea class="form-control" name="market_reach" placeholder="Market Reach">{{ $user->market_reach }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>What are you looking for:</label>
                                        <textarea class="form-control" name="member_looking" placeholder="What are..">{{ $user->member_looking }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>What can you help in:</label>
                                        <textarea class="form-control" name="member_help" placeholder="What can you..">{{ $user->member_help }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>Achievements:</label>
                                        <textarea class="form-control" name="achievements" placeholder="Achievements..">{{ $user->achievements }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>Patents/Licenses:</label>
                                        <textarea class="form-control" name="certifications" placeholder="Patents/Licenses">{{ $user->certifications }}</textarea>
                                    </div>
                                </div>
                                {{-- 
                  <div class="col-md-12 col-lg-6">
                     <label for="is_raised_invest">Raised Investments:</label>
                     <input type="radio" name="is_raised_invest" onclick="change_hidden_answer_type_value(1)" id="is_raised_invest" value="1" 
                     @if (isset($user->is_raised_invest))
                     @if ($user->is_raised_invest == 1) 
                     checked
                     @endif                      
                     @endif>Yes
                     <input type="radio" name="is_raised_invest" id="is_raised_invest" onclick="change_hidden_answer_type_value(0)"  value="0" 
                     @if (isset($user->is_raised_invest))
                     @if ($user->is_raised_invest == 0) 
                     checked
                     @endif                      
                     @endif>No
                     <input type="hidden" name="raise_check" id="raise_check" value="{{$user->is_raised_invest}}">
                     <div class="form-group" id="show_raise" style="display: none">
                        <label>Investment Info :</label>
                        <textarea class="form-control" name="invest_name" placeholder="Investment Info..">{{ $user->invest_name }}</textarea>
                     </div>
                  </div>
                  --}}
                                {{-- Souvik change --}}
                                {{-- 
                  <div class="col-md-12 col-lg-6">
                     --}}
                                {{-- 
                     <div id="invest_sec" >
                        @if (isset($company_investments) && count($company_investments) > 0)
                        @php
                        $c=count($company_investments);
                        $i=1;
                        @endphp
                        <div class="founder-header">
                           <label>Investment Info </label>
                        </div>
                        @foreach ($company_investments as $vv)   
                        <div id="invest_sec_{{$i}}">
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="inv-info-box">
                                    <div class="holder">
                                       <div class="field-box">
                                          <label>Source :</label>
                                          <input type="text" name="company_source[]" class="form-control" id="company_source_{{$i}}" placeholder="Source" value="@if (!empty($vv->source)){{$vv->source}}@endif">
                                       </div>
                                       <div class="field-box">
                                          <label>Instrument :</label>
                                          <input type="text" name="company_instrument[]" class="form-control" id="company_instrument_{{$i}}" placeholder="Instrument" value="@if (!empty($vv->instrument)){{$vv->instrument}}@endif">
                                       </div>
                                       <div class="field-box">
                                          <label>Value :</label>
                                          <input type="text" name="company_value[]" class="form-control" id="company_value_{{$i}}" placeholder="Value" value="@if (!empty($vv->value)){{$vv->value}}@endif">
                                       </div>
                                    </div>
                                    @if ($i != 1) 
                                    <a  id="rm-answer-{{$i}}" class="btn btn-danger btn-remove" onClick="remove_invest({{$i}})">Remove</a>
                                    @else
                                    <a id="ad-contact_person-{{$i}}" class="btn btn-success btn-add" onClick="clone_invest()">Add More</a>
                                    <input type="hidden" name="answer_row_count" id="answer_row_count" value="{{$c}}" />  
                                    @endif
                                 </div>
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
                              <div class="col-lg-12">
                                 <label for="is_raised_invest">Raised Investments:</label>
                                 <input type="radio" name="is_raised_invest" onclick="change_hidden_answer_type_value(1)" id="is_raised_invest" value="1" 
                                 @if (isset($user->is_raised_invest))
                                 @if ($user->is_raised_invest == 1) 
                                 checked
                                 @endif                      
                                 @endif>Yes
                                 <input type="radio" name="is_raised_invest" id="is_raised_invest" onclick="change_hidden_answer_type_value(0)"  value="0" 
                                 @if (isset($user->is_raised_invest))
                                 @if ($user->is_raised_invest == 0) 
                                 checked
                                 @endif                      
                                 @endif>No
                                 <input type="hidden" name="raise_check" id="raise_check" value="{{$user->is_raised_invest}}">
                                 <div class="form-group" id="show_raise" style="display: none">
                                    <textarea class="form-control" name="invest_name" placeholder="Investment Info..">{{ $user->invest_name }}</textarea>
                                    <label> Source : </label>
                                    <input type="text" name="company_source[]" class="form-control" id="company_source_1" placeholder="Source">
                                    <label> Instrument : </label>
                                    <input type="text" name="company_instrument[]" class="form-control" id="company_instrument_1" placeholder="Instrument">
                                    <label> Value : </label>
                                    <input type="text" name="company_value[]" class="form-control" id="company_value_1" placeholder="Value">
                                    <a id="ad-contact_person-3" class="btn btn-success btn-add" onClick="clone_invest()">Add More</a>
                                    <input type="hidden" name="answer_row_count" id="answer_row_count" value="1" />        
                                 </div>
                              </div>
                           </div>
                        </div>
                        @endif 
                     </div>
                     --}}
                                {{-- 
                  </div>
                  --}}
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>Company Logo:</label>
                                        <div class="custom-file">
                                            <input type="file" name="image" class="custom-file-input"
                                                id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label com_logo" for="inputGroupFile01">Choose
                                                file</label>
                                            @php
                                                if ($user->image != '' && $user->image != null) {
                                                    $profileImage = asset('public/uploads/user_images/thumb/' . $user->image);
                                                } else {
                                                    $profileImage = asset('public/uploads/user_images/thumb/entreprenaur_photo/noimage.png');
                                                }
                                            @endphp
                                            @if (isset($profileImage) && $profileImage != '')
                                                <a class="fancybox" data-fancybox="images" href="{{ $profileImage }}">
                                                    <img src="{{ $profileImage }}" class="browse-img">
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="form-group mt-3">
                         @php
                             if (Auth::user()->image != '' && Auth::user()->image != null) {
                                 $profileImage = asset('public/uploads/user_images/thumb/' . Auth::user()->image);
                             }
                         @endphp
                         @if (isset($profileImage) && $profileImage != '')
    <img src="{{ $profileImage }}" style="width: 40px; height: 40px; border-radius:20px;">
    @endif
                         </div> -->
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>Company Pitch:</label>
                                        <div class="custom-file">
                                            <input type="file" name="speech" class="custom-file-input file-pitch"
                                                id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label file_pitch_label"
                                                for="inputGroupFile01">Choose file</label>
                                            @php  $profileImage = asset('public/uploads/user_images/'.$user->speech);   @endphp
                                            @if (isset($user->speech) && $user->speech != '')
                                                <br>
                                                <a class="fancybox" data-fancybox="images" href="{{ $profileImage }}"
                                                    download> Download Company Pitch</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br>
                                        The allowed files are ppt, pptx, pptm, pps, ppsx, ppsm, pdf, doc, docx and rtf. Max
                                        size of file would be 5 Mb
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label>About Company:</label>
                                    <textarea name="about_you" id="aboutMe" class="form-control" placeholder="Tell us about yourself"
                                        style="height: 150px;">{!! html_entity_decode($user->about_you, ENT_QUOTES) !!}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <div class="founder-header">
                                    <label>Company Video</label>
                                </div>
                                <div id="video_sec">
                                    @if (isset($company_videos) && count($company_videos) > 0)
                                        @php
                                            $c = count($company_videos);
                                            $i = 1;
                                        @endphp
                                        @foreach ($company_videos as $vv)
                                            <div id="video_sec_{{ $i }}">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Link : </label>
                                                            <input type="text" name="company_video[]"
                                                                class="form-control"
                                                                id="company_video_{{ $i }}"
                                                                placeholder="Enter YouTube or Vimeo Video Link"
                                                                value="@if (!empty($vv->company_video)) {{ $vv->company_video }} @endif">
                                                        </div>
                                                        @if (!empty($vv->company_video))
                                                            @php
                                                                $video_url = $vv->company_video;
                                                                $url = videoType($video_url);
                                                                if ($url == 'youtube') {
                                                                    $video_id = extractVideoID($video_url);
                                                                    $thumbnail4 = getYouTubeThumbnailImage($video_id);
                                                                } elseif ($url == 'vimeo') {
                                                                    $video_id = getVimeoId($video_url);
                                                                    $thumbnail4 = getVimeoThumb($video_id);
                                                                }
                                                                $vv->company_video = getYoutubeEmbedUrl($vv->company_video);
                                                            @endphp
                                                            @if (!empty($thumbnail4))
                                                                <a onclick="openVideoModal('{{ $vv->company_video }}')"><img
                                                                        src="{{ $thumbnail4 }}" class="pitch_two"></a>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="col-md-12">
                                                        @if ($i != 1)
                                                            <a id="rm-answer-{{ $i }}"
                                                                class="btn btn-danger btn-remove"
                                                                onClick="remove_video({{ $i }})">Remove</a>
                                                        @else
                                                            <a id="ad-contact_person-{{ $i }}"
                                                                class="btn btn-success btn-add"
                                                                onClick="clone_video()">Add More</a>
                                                            <input type="hidden" name="answer_row_count"
                                                                id="answer_row_count" value="{{ $c }}" />
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @php
                                                ++$i;
                                            @endphp
                                        @endforeach
                                    @else
                                        <div id="video_sec_1">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label> Link : </label>
                                                        <input type="text" name="company_video[]" class="form-control"
                                                            id="company_video_1"
                                                            placeholder="Enter YouTube or Vimeo Video Link">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <a id="ad-contact_person-3" class="btn btn-success btn-add"
                                                        onClick="clone_video()">Add More</a>
                                                    <input type="hidden" name="answer_row_count" id="answer_row_count"
                                                        value="1" />
                                                </div>
                                                <!-- <div class="col-md-12">
                                  <div class="form-group">
                                  <label>Website :</label>
                                      <textarea class="form-control" name="buisness_website[]" id="buisness_website_1"></textarea>
                                  </div>
                                  </div> -->
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <!-- <div class="col-md-12 col-lg-6">
                         <div class="founder-header">
                             <label>Founder</label>
                         </div>
                         
                         <div id="answer_sec">
                             @if (isset($founders) && count($founders) > 0)
    @php
        $c = count($founders);
        $i = 1;
    @endphp
                         
                                 @foreach ($founders as $ca)
    <div id="answer_sec_{{ $i }}">
                                 <div class="row">
                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label>Name : </label>
                                             <input type="text" name="founder_name[]" class="form-control" id="founder_name_{{ $i }}"  value="@if (!empty($ca->name)) {{ $ca->name }} @endif">
                                         </div>
                                     </div>
                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label>Brief Profile :</label>
                                             <textarea class="form-control" name="founder_profile[]" id="founder_profile_{{ $i }}">
                     @if (!empty($ca->profile))
{{ $ca->profile }}
@endif
                     </textarea>
                                         </div>
                                     </div>
                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label>LinkedIn profile :</label>
                                             <input type="text" name="founder_linc_profile[]" class="form-control" id="founder_linc_profile_{{ $i }}"  value="@if (!empty($ca->linkdin_profile)) {{ $ca->linkdin_profile }} @endif">
                         
                                            
                                         </div>
                                     </div>
                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label>Image:</label>
                                             <div class="custom-file">
                                             <input type="file" name="founder_img[]" class="custom-file-input" id="founder_img__{{ $i }}" aria-describedby="inputGroupFileAddon02">
                                             <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                                             <input type="hidden" name="founder_img_hidden[]" id="founder_img_hidden{{ $i }}" value="{{ $ca->image }}" />
                         
                                             @php
                                                 if ($ca->image != '' && $ca->image != null) {
                                                     $founderImage = asset('public/uploads/founder_images/thumb/' . $ca->image);
                                                 } else {
                                                     $founderImage = asset('public/uploads/founder_images/thumb/promoters_images/noimage.png');
                                                 }
                                             @endphp
                                             @if (isset($founderImage) && $founderImage != '')
    <a class="fancybox" data-fancybox="images" href="{{ $founderImage }}"><img src="{{ $founderImage }}" class="browse-img"></a>
    @endif
                                             </div>
                                         </div>
                                     </div>
                                     <div class="col-md-12">
                                         @if ($i != 1)
    <a  id="rm-answer-{{ $i }}" class="btn btn-danger btn-remove" onClick="remove_answer({{ $i }})">Remove</a>
@else
    <a id="ad-contact_person-{{ $i }}" class="btn btn-success btn-add" onClick="clone_answer()">Add More</a>
                                         <input type="hidden" name="answer_row_count" id="answer_row_count" value="{{ $c }}" />
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
                                         <label> Brief Profile :</label>
                                             <textarea class="form-control" name="founder_profile[]" id="founder_profile_1"></textarea>
                                         </div>
                                     </div>
                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label>LinkedIn profile :</label>
                                             <input type="text" name="founder_linc_profile[]" class="form-control" id="founder_linc_profile_1">
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
                                         <a id="ad-answer-1" class="btn btn-success btn-add" onClick="clone_answer()">Add More</a>
                                         <input type="hidden" name="answer_row_count" id="answer_row_count" value="1" />
                                     </div>
                                 </div>
                             </div>
    @endif
                         </div>
                         </div> -->
                                <div class="col-md-12 col-lg-12">
                                    <div class="founder-header">
                                        <!--<label>Product/Services</label>-->
                                        <label>Product Info</label>
                                    </div>
                                    <div id="service_sec">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Description: </label>
                                                <!-- <input type="text" name="buisness_info" class="form-control" id="buisness_info"  value="@if (!empty(Auth::user()->buisness_info)) {{ Auth::user()->buisness_info }} @endif"> -->
                                                <textarea class="form-control" name="buisness_info" id="buisness_info">
@if (!empty($user->buisness_info))
{{ $user->buisness_info }}
@endif
</textarea>
                                            </div>
                                        </div>
                                        @if (isset($buisness) && count($buisness) > 0)
                                            @php
                                                $c = count($buisness);
                                                $i = 1;
                                            @endphp
                                            @foreach ($buisness as $va)
                                                <div id="service_sec_{{ $i }}">
                                                    <div class="row">
                                                        <!--<div class="col-md-12 col-lg-6">-->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Product Name : </label>
                                                                <input type="text" name="buisness_caption[]"
                                                                    class="form-control"
                                                                    id="buisness_caption_{{ $i }}"
                                                                    value="{{ $va->caption }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Image:</label>
                                                                <div class="custom-file">
                                                                    <input type="file" name="buisness_img[]"
                                                                        class="custom-file-input"
                                                                        id="buisness_img_{{ $i }}"
                                                                        aria-describedby="inputGroupFileAddon02">
                                                                    <label class="custom-file-label"
                                                                        for="inputGroupFile02">Choose file</label>
                                                                    <input type="hidden" name="buisness_img_hidden[]"
                                                                        id="buisness_img_hidden{{ $i }}"
                                                                        value="{{ $va->image }}" />
                                                                    @php
                                                                        if ($va->image != '' && $va->image != null) {
                                                                            $serviceImage = asset('public/uploads/website_images/thumb/' . $va->image);
                                                                        } else {
                                                                            $serviceImage = asset('public/uploads/website_images/thumb/products_images/noimage .png');
                                                                        }
                                                                    @endphp
                                                                    @if (isset($serviceImage) && $serviceImage != '')
                                                                        <a class="fancybox" data-fancybox="images"
                                                                            href="{{ $serviceImage }}">
                                                                            <a class="fancybox" data-fancybox="images"
                                                                                href="{{ $serviceImage }}">
                                                                                <img src="{{ $serviceImage }}"
                                                                                    class="browse-img"></a></a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--<div class="col-md-12 col-lg-6">-->
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Video:</label>
                                                                <input type="text" name="buisness_video[]"
                                                                    class="form-control"
                                                                    id="buisness_video_{{ $i }}"
                                                                    placeholder="Enter YouTube or Vimeo Video Link"
                                                                    value="@if (!empty($va->buisness_video)) {{ $va->buisness_video }} @endif">
                                                            </div>
                                                            @if (!empty($va->buisness_video))
                                                                @php
                                                                    $video_url = $va->buisness_video;
                                                                    $url = videoType($video_url);
                                                                    if ($url == 'youtube') {
                                                                        $video_id = extractVideoID($video_url);
                                                                        $thumbnail2 = getYouTubeThumbnailImage($video_id);
                                                                    } elseif ($url == 'vimeo') {
                                                                        $video_id = getVimeoId($video_url);
                                                                        $thumbnail2 = getVimeoThumb($video_id);
                                                                    }
                                                                    $va->buisness_video = getYoutubeEmbedUrl($va->buisness_video);
                                                                @endphp
                                                                @if (!empty($thumbnail2))
                                                                    <a
                                                                        onclick="openVideoModal('{{ $va->buisness_video }}')"><img
                                                                            src="{{ $thumbnail2 }}"
                                                                            class="pitch_two"></a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                        <!-- <div class="col-md-12">
                                     <div class="form-group">
                                         <label>Webisite :</label>
                                         <textarea class="form-control" name="buisness_website[]" id="buisness_website_{{ $i }}">{{ $va->website }}</textarea>
                                     </div>
                                     </div> -->
                                                        <div class="col-md-12">
                                                            @if ($i != 1)
                                                                <a id="rm-answer-{{ $i }}"
                                                                    class="btn btn-danger btn-remove"
                                                                    onClick="remove_service({{ $i }})">Remove</a>
                                                            @else
                                                                <a id="ad-contact_person-{{ $i }}"
                                                                    class="btn btn-success btn-add"
                                                                    onClick="clone_service()">Add More</a>
                                                                <input type="hidden" name="answer_row_count"
                                                                    id="answer_row_count" value="{{ $c }}" />
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
                                                            <label>Product Image:</label>
                                                            <div class="custom-file">
                                                                <input type="file" name="buisness_img[]"
                                                                    class="custom-file-input" id="buisness_img_1"
                                                                    aria-describedby="inputGroupFileAddon02">
                                                                <label class="custom-file-label"
                                                                    for="inputGroupFile02">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Image Caption : </label>
                                                            <input type="text" name="buisness_caption[]"
                                                                class="form-control" id="buisness_caption_1"
                                                                value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Video : </label>
                                                            <input type="text" name="buisness_video[]"
                                                                class="form-control" id="buisness_video_1"
                                                                placeholder="Enter YouTube or Vimeo Video Link">
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-md-12">
                                     <div class="form-group">
                                     <label>Website :</label>
                                         <textarea class="form-control" name="buisness_website[]" id="buisness_website_1"></textarea>
                                     </div>
                                     </div> -->
                                                    <div class="col-md-12">
                                                        <a id="ad-answer-2" class="btn btn-success btn-add"
                                                            onClick="clone_service()">Add More</a>
                                                        <input type="hidden" name="answer_row_count"
                                                            id="answer_row_count" value="1" />
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <input type="submit" class="btn btn-primary" value="Save Changes">
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
                        <iframe id="cartoonVideo" width="560" height="315"
                            src="//www.youtube.com/embed/YE7VzlLtp-4" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Question modal-->
    @include('frontend.includes.add_question_modal')
    @push('page_js')
        <script>
            var editorPostInfo = CKEDITOR.replace('aboutMe', {
                customConfig: "{{ asset('public/assets/ckeditor/mini_config.js') }}",
            });
            $('#frm_pfupd').validate({
                errorElement: 'span',
                errorClass: 'roy-vali-error',
                rules: {
                    member_company: {
                        required: true
                    },
                    contact_name: {
                        required: true
                    },
                    "industry_category_id[]": {
                        required: true
                    },
                    stage_id: {
                        required: true
                    },
                    email_id: {
                        required: true,
                        email: true
                    },
                    contact_no: {
                        required: true
                    },
                    country: {
                        required: true
                    },
                    city: {
                        required: true
                    }
                },
                messages: {
                    member_company: {
                        required: 'Please enter your company name'
                    },
                    contact_name: {
                        required: 'Please enter contact name'
                    },
                    "industry_category_id[]": {
                        required: 'Please select industies'
                    },
                    stage_id: {
                        required: 'Please select company stage'
                    },
                    email_id: {
                        required: 'Please enter email-id',
                        email: 'Please enter valid email-id'
                    },
                    contact_no: {
                        required: 'Please enter contact number'
                    },
                    country: {
                        required: 'Please enter country'
                    },
                    city: {
                        required: 'Please enter city'
                    }
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
                        $('.browse-img').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#inputGroupFile01").change(function() {
                readURL(this);
            });


            $(document).ready(function() {
                $('.file-pitch').change(function(e) {
                    var pitch_file_name = e.target.files[0].name;
                    $('.file_pitch_label').text(pitch_file_name)
                });
                $('#inputGroupFile01').change(function(e) {
                    var img_file_name = e.target.files[0].name;
                    $('.com_logo').text(img_file_name)
                });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $(".fancybox").fancybox();
                $i = 1;
                $('.add-founder').click(function() {
                    $('.founder').clone().appendTo('.founder-list').removeAttr('class', 'founder').addClass(
                        'founders' + $i).append(
                        '<div class="form-group"><a class="btn remove-founder btn-danger btn-remove" onclick="removeItem(' +
                        $i + ')">Remove</a></div>');

                    $i++;
                });

                //alert($("#is_raised_invest").val());

                if ($("#raise_check").val() == 1) {
                    $("#show_raise").show();
                } else {
                    $("#show_raise").hide();
                }
            });

            function removeItem($j) {
                //alert($j);
                $(".founders" + $j).remove();
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
                table_row += '<input type="text" name="founder_name[]" id="founder_name_' + new_id +
                    '" class="form-control" value="" /> ';
                table_row += '</div>';
                table_row += '</div>';
                table_row += '<div class="col-md-12">';
                table_row += '<div class="form-group">';
                table_row += '<label>Brief Profile :</label>';
                table_row += '<textarea class="form-control" name="founder_profile[]" id="founder_profile_' + new_id +
                    '"></textarea>';

                table_row += '</div>';
                table_row += '</div>';
                table_row += '<div class="col-md-12">';
                table_row += '<div class="form-group">';
                table_row += '<label>LinkedIn profile :</label>';
                table_row += '<input type="text" name="founder_linc_profile[]" class="form-control" id="founder_linc_profile_' +
                    new_id + '"  >';

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
                table_row +=
                    ' <div class="custom-file"><input type="file" name="buisness_img[]" class="custom-file-input" id="buisness_img_' +
                    new_id +
                    '" aria-describedby="inputGroupFileAddon02"><label class="custom-file-label" for="inputGroupFile02">Choose file</label></div>';



                table_row += '</div>';
                table_row += '</div>';
                table_row += '<div class="col-md-12">';
                table_row += '<div class="form-group">';
                table_row += '<label>Product Name : </label>';
                table_row += '<input type="text" name="buisness_caption[]" id="buisness_caption_' + new_id +
                    '" class="form-control" value="" /> ';
                table_row += '</div>';
                table_row += '</div>';
                table_row += '<div class="col-md-12">';
                table_row += '<div class="form-group">';
                table_row += '<label>Video : </label>';
                table_row += '<input type="text" name="buisness_video[]" id="buisness_video_' + new_id +
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
                table_row += '<a id="rm-answer-' + new_id + '"  class="btn btn-danger btn-remove" onClick="remove_service(\'' +
                    new_id + '\')">';
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

                $("#video_sec").append(table_row);

            }

            function remove_video(id) {


                $("#video_sec_" + id).remove();


            }

            function change_hidden_answer_type_value(id) {

                if (id == 1) {
                    $("#show_raise").show();
                } else {
                    $("#show_raise").hide();
                }





            }

            function openVideoModal(video_url)

            {

                $("#cartoonVideo").attr('src', video_url);
                //alert(video_url);
                $("#myModal").modal('show');
            }


            function clone_invest() {

                var id = $("#answer_row_count").val();
                var new_id = parseInt(id) + 1;
                $("#answer_row_count").val(new_id);
                var table_row = "";


                table_row += '<div id="invest_sec_' + new_id + '">';
                table_row += '<div class="row">';
                table_row += '<div class="col-md-12">';

                table_row += '<div class="form-group">';
                table_row += '<label>Source : </label>';
                table_row += '<input type="text" name="company_source[]" id="company_source_' + new_id +
                    '" class="form-control"  placeholder="Source"/> ';
                table_row += '</div>';
                table_row += '</div>';


                table_row += '<div class="col-md-12">';

                table_row += '<div class="form-group">';
                table_row += '<label>Instrument : </label>';
                table_row += '<input type="text" name="company_instrument[]" id="company_instrument_' + new_id +
                    '" class="form-control"  placeholder="Instrument"/> ';
                table_row += '</div>';
                table_row += '</div>';



                table_row += '<div class="col-md-12">';
                table_row += '<div class="form-group">';
                table_row += '<label>Value : </label>';
                table_row += '<input type="text" name="company_value[]" id="company_value_' + new_id +
                    '" class="form-control"  placeholder="Value"/> ';
                table_row += '</div>';
                table_row += '</div>';
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

                $("#invest_sec").append(table_row);



            }

            function remove_invest(id) {


                $("#invest_sec_" + id).remove();


            }
        </script>
    @endpush
@endsection
