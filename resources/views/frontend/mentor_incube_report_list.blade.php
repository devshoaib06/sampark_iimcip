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

<div class="col-sm-12">
   <div class="panel panel-default">
      <div class="panel-heading">
         <h3 class="panel-title">Startups           </h3>
      </div>
      <div class="panel-body">
         <div>
            <form method="POST" action="" accept-charset="UTF-8" url="">
               <input name="_token" type="hidden" value="BgHI7QwULv0BP3g0PUONoKjmSYy4dapbgfdlcPmh"> 
               <div class="form-group col-sm-6">
                  <label>Financial Year</label>
                  <select name="year" class="form-control">
                     <option value="">Select</option>
                     <option value="2018">2018</option>
                     <option value="2019">2019</option>
                     <option value="2020">2020</option>
                     <option value="2021">2021</option>
                  </select>
               </div>
               <div class="form-group col-sm-6">
                  <label>Month</label>
                  <select name="month" class="form-control">
                     <option value="">Select</option>
                     <option value="last3">Past 3 months</option>
                     <option value="last6">Past 6 months</option>
                     <option value="04">April</option>
                     <option value="05">May</option>
                     <option value="06">June</option>
                     <option value="07">July</option>
                     <option value="08">August</option>
                     <option value="09">September</option>
                     <option value="10">October</option>
                     <option value="11">November</option>
                     <option value="12">December</option>
                     <option value="01">January</option>
                     <option value="02">February</option>
                     <option value="03">March</option>
                  </select>
               </div>
               <div class="form-group col-sm-3">
                  <label>&nbsp;</label>
                  <button type="submit" class="btn btn-default" id="submitButton">Search</button>
               </div>
            </form>
         </div>
         <table class="table table-bordered">
            <thead>
               <tr>
                  <th width="25%">Month-Year</th>
                  <th width="25%">Action</th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td> Sep 21</td>
                  <td>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/download_incubate_report/09/2021">Download</a>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/incubate_report_monthwise/09/2021">Edit report monthwise</a>
                  </td>
               </tr>
               <tr>
                  <td> Aug 21</td>
                  <td>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/download_incubate_report/08/2021">Download</a>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/incubate_report_monthwise/08/2021">Edit report monthwise</a>
                  </td>
               </tr>
               <tr>
                  <td> Jul 21</td>
                  <td>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/download_incubate_report/07/2021">Download</a>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/incubate_report_monthwise/07/2021">Edit report monthwise</a>
                  </td>
               </tr>
               <tr>
                  <td> Jun 21</td>
                  <td>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/download_incubate_report/06/2021">Download</a>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/incubate_report_monthwise/06/2021">Edit report monthwise</a>
                  </td>
               </tr>
               <tr>
                  <td> May 21</td>
                  <td>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/download_incubate_report/05/2021">Download</a>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/incubate_report_monthwise/05/2021">Edit report monthwise</a>
                  </td>
               </tr>
               <tr>
                  <td> Apr 21</td>
                  <td>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/download_incubate_report/04/2021">Download</a>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/incubate_report_monthwise/04/2021">Edit report monthwise</a>
                  </td>
               </tr>
               <tr>
                  <td> Mar 21</td>
                  <td>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/download_incubate_report/03/2021">Download</a>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/incubate_report_monthwise/03/2021">Edit report monthwise</a>
                  </td>
               </tr>
               <tr>
                  <td> Feb 21</td>
                  <td>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/download_incubate_report/02/2021">Download</a>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/incubate_report_monthwise/02/2021">Edit report monthwise</a>
                  </td>
               </tr>
               <tr>
                  <td> Jan 21</td>
                  <td>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/download_incubate_report/01/2021">Download</a>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/incubate_report_monthwise/01/2021">Edit report monthwise</a>
                  </td>
               </tr>
               <tr>
                  <td> Dec 20</td>
                  <td>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/download_incubate_report/12/2020">Download</a>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/incubate_report_monthwise/12/2020">Edit report monthwise</a>
                  </td>
               </tr>
               <tr>
                  <td> Nov 20</td>
                  <td>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/download_incubate_report/11/2020">Download</a>
                     <a class="btn btn-small btn-info" href="https://iimcip.org/our-network/admin/incubate_report_monthwise/11/2020">Edit report monthwise</a>
                  </td>
               </tr>
            </tbody>
         </table>
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

    