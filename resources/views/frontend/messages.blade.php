@extends('frontend.layouts.app')
@section('content')
    <div class="row message-box">
        @if (Session::has('msg') && Session::has('msg_class'))
            <div class="col-sm-12">
                <div class="postCard">
                    <div class="{{ Session::get('msg_class') }}" style="margin-bottom: 0;">
                        {{ Session::get('msg') }}
                    </div>
                </div>
            </div>
        @endif
        <?php $a = [];
        
        ?>

        @if (isset($users) && !empty($users) && count($users))
            @foreach ($users as $user)
            @if ($user['user'])
                
            
                <?php  

        
        if(!in_array($user['user']->id, $a)){ 
            
            array_push($a,$user['user']->id); 
 
        ?>

                <div class="col-sm-12">
                    <div class="postCard">
                        <div class="postWrap company-post">


                            <!--<div class="postUser d-flex">-->
                            <div class="chatUser">

                                <div class="userInfo">
                                    <div class="row">
                                        <div class="col-md-3" style="width: 647px;">

                                            <h4>

                                                <a
                                                    href="{{ route('front.user.sendMessage') }}?user_id={{ $user['user']->id }}">

                                                    <span style="color:#02025c;">{{ $user['user']->contact_name }} </span>

                                                    @if ($user->read_status == 0 && $sender_id != $user->sender_id)
                                                        <!-- <img style="width: 20px; " src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ-T06FRVBtF6Wl86dMz2SGU3bo0n9kEDWPIg&usqp=CAU"> -->
                                                    @endif


                                                    <br> <span style="color:blue;">{{ $user['user']->designation }}</span>

                                                    <br> {{ $user['user']->member_company }}
                                            </h4>
                                            </a>

                                            <br>


                                        </div>

                                        <div class="col-md-7">
                                            @if ($user->read_status == 0 && $sender_id != $user->sender_id)
                                                <b>
                                                    <p style="padding-top: 8px;">{{ $user->message }}</p>
                                                </b>
                                            @else
                                                <p style="padding-top: 8px;">{{ $user->message }}</p>
                                            @endif
                                        </div>

                                        <div class="col-md-2">
                                            <a style="font-size: 11px;
                                                    color: #ffffff;
                                                    font-weight: bold;
                                                    background-color: #2d75a1;
                                                    padding: 3px 13px;
                                                    border-radius: 5px;
                                                    float: right;"
                                                href="{{ route('front.user.sendMessage') }}?user_id={{ $user['user']->id }}">View
                                                More</a>
                                        </div>
                                    </div>

                                </div>
                            </div>



                        </div>
                        <!-- Comment Start -->

                        <!-- Comment End -->
                    </div>

                </div>

                <?php } ?>
                @endif
            @endforeach
        @else
            <div class="col-sm-12">
                <div class="postCard">
                    <div class="postWrap">
                        <div class="postTop">
                            <p>Sorry! No Messages found.</p>
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
