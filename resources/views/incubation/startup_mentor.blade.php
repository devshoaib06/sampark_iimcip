@extends('incubation.layouts.app')
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

        {{-- <div class="col-sm-12">
                <form name="frmx_src" action="{{ route('front.mentor.startup') }}" method="GET">
                            <div class="postWrap mt-3 search-alpha">
                            <strong>Search StartUps: </strong><br>
                            <!-- <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-search"></i></div>
                            </div>
                            <input type="text" id="autocomplete" name="search" class="form-control" placeholder="Search StartUps" 
                                value="@if (isset($_GET['search'])){{ $_GET['search'] }}@endif"> -->
                               <div>
                                @php

                                $letters = "A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
                               $lettersarray=explode(",",$letters);

                                
                                $letters = array();
                                    for ($i = 0; $i < 26; $i++)
                                    {
                                       @endphp

                                       <a href="{{ route('front.mentor.startup') }}?searr_id={{ $lettersarray[$i]}}@if (isset($_GET['industry_id']) && $_GET['industry_id'] != '')&industry_id={{ $_GET['industry_id'] }}@endif">{{$lettersarray[$i]}}&nbsp;</a>

                                       @php
                                    }
                                    @endphp

                                    

                                    <a href="{{ route('front.mentor.startup') }}@if (isset($_GET['industry_id']) && $_GET['industry_id'] != '')?industry_id={{ $_GET['industry_id'] }}@endif" class="btn btn-primary" style="width:auto;"><b class="text-white"> All</b> </a>
                                    </div>
                                        <br>
                                        <hr class="mt-0">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-1"><p><strong>Filter by Industry:</strong></p></div>
                                            <div><select class="form-control" name="industry_id" onchange="if (this.value) window.location.href=this.value">
                                       
                                       <option value="{{ route('front.mentor.startup') }}@if (isset($_GET['searr_id']) && $_GET['searr_id'] != '')?searr_id={{ $_GET['searr_id'] }}@endif">All</option>

                                        @if (isset($industry_category_show) && !empty($industry_category_show) && count($industry_category_show))
                                        @foreach ($industry_category_show as $v)

                                        <option value="{{ route('front.mentor.startup') }}?industry_id={{ $v->id}}@if (isset($_GET['searr_id']) && $_GET['searr_id'] != '')&searr_id={{ $_GET['searr_id'] }}@endif" @if (isset($_GET['industry_id']) && $_GET['industry_id'] == $v->id) selected="true" @endif onchange="window.open (this.value)" >{{ $v->industry_category }}</option>
                                          
                                        @endforeach

                                        @endif


                                   </select></div>
                                            
                                        </div>

                                    

                                   

                                     

                                
                        </div>
                </form>
            </div> --}}

        @if (isset($users) && !empty($users) && count($users))
            @foreach ($users as $user)
                @php
                    
                    if (isset($user['memberInfo']) && !empty($user['memberInfo'])) {
                        $memberInfo = $user['memberInfo'];
                        //dd($memberInfo);
                    }
                @endphp

                @if(!empty($memberInfo->member_company))
                <div class="col-sm-12">
                    <div class="postCard">
                        <div class="postWrap startup-post">

                            @php
                                $profileImage = $defaultImage = $authUserImage = asset('public/front_end/images/profile-pic.png');
                                if (isset($memberInfo) && !empty($memberInfo) && $memberInfo->image != null) {
                                    $profileImage = asset('public/uploads/user_images/thumb/' . $memberInfo->image);
                                }
                                if (Auth::user()->image != null) {
                                    $authUserImage = asset('public/uploads/user_images/thumb/' . Auth::user()->image);
                                } else {
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




                                                @if (isset($memberInfo) && !empty($memberInfo))
                                                    <a href="{{ route('front.user.memberProfile', ['member_company' => $memberInfo->slug]) }}"
                                                        target="_blank">{{ $memberInfo->member_company }}</a>
                                                @endif

                                            </h3>
                                        </div>
                                        <div class="col-lg-5">
                                            {{-- <div class="row">
                                                        <div class="col-3 col-sm-6"><p><i class="fa fa-list-alt"></i> <strong class="text-primary">{{ $user['number_of_post'] }}</strong> Posts</p></div>
                                                        <div class="col-9 col-sm-6"><p><i class="far fa-comment-alt"></i> <strong class="text-primary">{{ $user['number_of_reply'] }}</strong> Replies</p></div>
                                                    </div> --}}
                                            <div class="userReply d-flex justify-end">
                                                <div>
                                                    <p><i class="fa fa-list-alt"></i> <strong
                                                            class="text-primary">{{ $user['number_of_post'] }}</strong>
                                                        Posts</p>
                                                </div>
                                                <div>
                                                    <p><i class="far fa-comment-alt"></i> <strong
                                                            class="text-primary">{{ $user['number_of_reply'] }}</strong>
                                                        Replies</p>
                                                </div>

                                                {{-- <div><p><i class="far fa-comment-alt"></i> <strong class="text-primary">{{ $user['number_of_reply'] }}</strong> Report </p></div> --}}
                                            </div>

                                        </div>

                                    </div>


                                    <hr>
                                        {{-- @if (isset($memberInfo) && !empty($memberInfo))
                                        <h4><a href="{{ route('front.user.account') }}?search={{ $memberInfo->member_company }}">{{ $memberInfo->member_company }}</a></h4>
                                        @endif  --}}
                                    @if (isset($memberInfo->allIndustryIds) &&
                                        !empty($memberInfo->allIndustryIds) &&
                                        count($memberInfo->allIndustryIds))
                                        <div class="member-info" style="padding-left: 0; width: 100%;">
                                            <table width="100%" cellpadding="5">
                                                <tr>
                                                    <td valign="top" style="width: 147px"><i
                                                            class="fas fa-building text-primary"></i> <strong>Industry
                                                            Verticals:</strong></td>
                                                    <td>
                                                        @php
                                                            $i = 0;
                                                            $tot = count($memberInfo->allIndustryIds);
                                                        @endphp
                                                        @foreach ($memberInfo->allIndustryIds as $v)
                                                            @if (isset($v->industryInfo) && !empty($v->industryInfo))
                                                                <span>{{ trim($v->industryInfo->industry_category) }}</span>
                                                                @if ($i < $tot - 1)
                                                                    ,
                                                                @endif
                                                            @endif
                                                            @php $i++; @endphp
                                                        @endforeach
                                    @endif
                                    </td>
                                    </tr>

                                    {{-- @if ($memberInfo->member_spec != '')
                                        <tr>
                                            <td valign="top"><i class="fas fa-info-circle text-primary"></i>
                                                <strong>Specialist In:</strong></td>
                                            <td><span>{{ $memberInfo->member_spec }}</span></td>
                                        </tr>
                                    @endif --}}
                                    @if (isset($user['founders']) && !empty($user['founders']) && count($user['founders']))
                                        <tr>
                                            <td valign="top"><i class="fas fa-user text-primary"></i>
                                                <strong>Founder:</strong>
                                            </td>
                                            <td>
                                                @php
                                                    $i = 0;
                                                    $tot = count($user['founders']);
                                                @endphp
                                                @foreach ($user['founders'] as $v1)
                                                    @if (isset($v1->name) && !empty($v1->name))
                                                        <span>{{ trim($v1->name) }}</span>
                                                        @if ($i < $tot - 1)
                                                            ,
                                                        @endif
                                                    @endif
                                                    @php $i++; @endphp
                                                @endforeach
                                    @endif
                                    </td>
                                    </tr>
                                    </table>
                                    <p class="text-left">

                                    </p>
                                    <div class="clearfix"></div>

                                    </p>




                                    <div class="clearfix"></div>
                                    @if (isset($memberInfo) && !empty($memberInfo))
                                    <p class="text-left">
                                        
                                        
                                        <a href="{{ route('task_list', ['uid' => $memberInfo->id]) }}"
                                            class="btn btn-success addMore"><span class="glyphicon glyphicon glyphicon-list"
                                                aria-hidden="true"></span> Task List</a>
                                        <a href="{{ route('add_task', ['uid' => $memberInfo->id]) }}"
                                            class="btn btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus"
                                                aria-hidden="true"></span> Add Task</a>
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- <div class="postCount">

                                        <p>No of Posts: <span class="btn btn-sm btn-primary">{{ $user['number_of_post'] }}</span>  No of Replies: <span class="btn btn-sm btn-primary">{{ $user['number_of_reply'] }}</span></p>


                                    </div> -->

                    </div>
                    <!-- Comment Start -->

                    <!-- Comment End -->
                </div>
                @endif

    </div>
    @endforeach
@else
    <div class="col-sm-12">
        <div class="postCard">
            <div class="postWrap">
                <div class="postTop">
                    <p>Sorry! startup not found.</p>
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
                    <iframe id="cartoonVideo" width="560" height="315" src="//www.youtube.com/embed/YE7VzlLtp-4"
                        frameborder="0" allowfullscreen></iframe>
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

                var image_id = 'post_image_' + postID + '-' + parentID;


                var image_files = $("input#" + image_id)[0].files;
                //console.log(files.length);

                if (image_files.length > 4) {
                    var err_image = 'error_image_' + postID + '-' + parentID;
                    $('#' + err_image).text("Maximum 4 Images Allowed");
                    return false;
                }

                for (var i = 0; i < image_files.length; i++) {
                    formData.append("images[]", image_files[i], image_files[i]['name']);

                }

                /*var video_id ='post_video_' + postID + '-' + parentID;

                    var video_files =$("input#"+video_id)[0].files;
                   

                   
                    for(var i=0;i<video_files.length;i++){
                        formData.append("video", video_files[i], video_files[i]['name']);

                    }*/


                formData.append("post_id", postID);
                formData.append("reply_text", commentTxt);
                formData.append("replied_on", parentID);
                formData.append("video_url", videoText);

                if (postID != '' && parentID != '' && commentTxt != '') {
                    //alert("entert");return false;
                    $.ajax({
                        url: "{{ route('front.user.addComment') }}",
                        method: 'POST',
                        data: formData,
                        dataType: 'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,

                        beforeSend: function() {
                            $('#addCommentBTN_' + postID + '-' + parentID).attr('disabled',
                                'disabled');
                            $('#addCommentBTN_' + postID + '-' + parentID).css({
                                'background-color': '#cecece'
                            });
                        },
                        success: function(data) {
                            if (data.status == 'ok') {
                                var ctxt = '';
                                var rtxt = '';
                                $('#addCommentBox_' + postID + '-' + parentID).prepend(data
                                    .repComtHtml);
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
                                $('#replyCount_' + postID + '-' + parentID).text(data
                                    .replyCount + ' ' + rtxt);
                                $('#addCommentBTN_' + postID + '-' + parentID).removeAttr(
                                    'disabled');
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
            });
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
                            $(videonode).find('source').attr('src', event.target.result).appendTo(
                                placeVideoPreview);
                        }

                        reader.readAsDataURL(input.files[i]);
                    }
                }
            };

            $('.add-video').on('change', function() {
                videoPreview(this, 'video.vid-preview');
                $('.video-preview').css('display', 'block');
            });
        });

        function getComboA(selectObject, id) {

            imagesPreview(selectObject, 'div.img_preview_' + id);
            document.getElementById("img_preview_" + id).innerHTML = "";
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


        function addFavourate(post_id, user_id) {
            var formData = new FormData;

            var status = $("#post_hidden_" + post_id).val();



            formData.append('_token', "{{ csrf_token() }}");
            formData.append("post_id", post_id);
            formData.append("user_id", user_id);
            formData.append("status", status);


            $.ajax({
                url: "{{ route('front.user.addFavourate') }}",
                method: 'POST',
                data: formData,
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,

                beforeSend: function() {

                },
                success: function(data) {
                    if (data.status == 'ok') {


                        var src1 = "{{ asset('public/front_end/images/heart_two.jpg') }}";

                        var src2 = "{{ asset('public/front_end/images/heart_one.png') }}";


                        if (status == 1) {
                            $('.post_fav_' + post_id + ' img').attr("src", src1);

                            $("#post_hidden_" + post_id).val(0);
                        } else {
                            $('.post_fav_' + post_id + ' img').attr("src", src2);

                            $("#post_hidden_" + post_id).val(1);
                        }


                    }
                }
            });
        }
    </script>
@endpush
