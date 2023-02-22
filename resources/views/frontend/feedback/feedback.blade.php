<!DOCTYPE html>
<html class="" lang="en-us">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" shrink-to-fit="no">
    <title>IIM Calcutta | Innovation park </title>
    <link rel="icon" href="https://pms.karmickdev.com/public/front_end/images/favicon.png">
    <link rel="stylesheet" href="{{ asset('public/feedback') }}/all.min.css">
    <link href="{{ asset('public/feedback') }}/css2.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('public/feedback') }}/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('public/feedback') }}/style.css">
    <link rel="stylesheet" href="{{ asset('public/feedback') }}/responsive.css">
    <link rel="stylesheet" href="{{ asset('public/feedback') }}/navigation.css">
    <link rel="stylesheet" href="{{ asset('public/feedback') }}/select2.min.css">
    <link rel="stylesheet" href="{{ asset('public/feedback') }}/jquery.fancybox.css" type="text/css" media="screen">
    <link rel="stylesheet" href="{{ asset('public/feedback') }}/jquery.fancybox-buttons.css" type="text/css"
        media="screen">
    <link rel="stylesheet" href="{{ asset('public/feedback') }}/jquery.fancybox-thumbs.css" type="text/css"
        media="screen">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
        .roy-vali-error {
            color: red;
        }

        /*.search-desktop #autocomplete { width: 400px !important; }
    .search-desktop ul.dropdown-menu { width: 439px !important; left: 0px !important; }*/
        .search-desktop li a.dropdown-item {
            word-wrap: break-word !important;
            white-space: normal !important;
        }

        a.link-nouder:hover {
            text-decoration: none;
        }
    </style>
    <style type="text/css">
        *,
        *:before,
        *:after {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }





        .custom-radio-wrap {
            margin-bottom: 20px;
        }

        .custom-radio-wrap form .form-group {
            margin-bottom: 10px;
        }

        .custom-radio-wrap form .form-group:last-child {
            margin-bottom: 0;
        }

        .custom-radio-wrap form .form-group label {
            -webkit-appearance: none;
            background-color: #fafafa;
            border: 1px solid #cacece;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05);
            padding: 8px;
            border-radius: 50px;
            display: inline-block;
            position: relative;
            vertical-align: middle;
            cursor: pointer;
        }

        .custom-radio-wrap form .form-group .label-text {
            vertical-align: middle;
            cursor: pointer;
            padding-left: 10px;
            margin-left: -5px;
        }

        .custom-radio-wrap form .form-group input {
            display: none;
            cursor: pointer;
        }

        .custom-radio-wrap form .form-group input:checked+label {
            background-color: #e9ecee;
            color: #99a1a7;
            border: 1px solid #0079bf;
        }

        .custom-radio-wrap form .form-group input:checked~.label-text {
            color: #0079bf;
            font-weight: 700;
        }

        .custom-radio-wrap form .form-group input:checked+label:after {
            content: '';
            width: 14px;
            height: 14px;
            border-radius: 50px;
            position: absolute;
            top: 1px;
            left: 1px;
            background: #0079bf;
            box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.3);
            text-shadow: none;
            font-size: 32px;
        }

        .q-top-block {
            width: 80%;
            margin: auto;
            background-color: #2a5071;
            text-align: center;
            margin-top: 10px;
        }

        .q-top-block1 {
            float: left;
            width: 25%;
            color: #fff;
            border-right: 1px solid #2d75a1;
            padding: 1%
        }

        .q-top-block2 {
            float: left;
            width: 50%;
            color: #fff;
            border-right: 1px solid #2d75a1;
            padding: 1%
        }

        .q-top-block-mid {
            width: 80%;
            margin: auto;
            background-color: #f1f2f2;
            text-align: center;
            margin-bottom: 10px;
        }

        .q-top-block-mid1 {
            float: left;
            width: 25%;
            color: #333;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding: 1%
        }

        .q-top-block-mid2 {
            float: left;
            width: 50%;
            color: #333;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding: 1%
        }

        .clearfix {
            clear: both;
        }

        .q-top-block-border {
            border-left: 1px solid #ccc;
        }

        .button-section {
            margin-top: 20px;
            text-align: center;
        }

        .button-section .btn-warning {
            background: #000 !important;
            border: 1px solid #000 !important;
            color: #fff !important;
        }

        .button-section .btn-info {
            background: #2a5071 !important;
        }
    </style>
    <style>
        .cke {
            visibility: hidden;
        }
    </style>
    <style type="text/css">
        .fancybox-margin {
            margin-right: 17px;
        }
    </style>
</head>

<body>

    <header class="siteHeader">
        <div class="container">
            <nav class="navbar navbar-expand-lg pms-nav d-flex justify-content-between">
                <a class="navbar-brand" href="https://pms.karmickdev.com/feeds?post=feed">
                    <img src="{{ asset('public/feedback') }}/logo-1.png" class="img-fluid" alt="IIM Calcutta">
                </a>
            </nav>
        </div>
    </header>

    <main>
        <div class="bodyCont">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="postCard" style="margin-top: 40px;">
                            <form method="post" action="{{ url('mentor-feedback',$parameter->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <div class="">
                                    <div class="col-lg-12">
                                        <div class="postWrap">


                                            <div class="q-top-block">
                                                <div class="q-top-block1">Parameter</div>
                                                <div class="q-top-block1">Score</div>
                                                <div class="q-top-block2">Comments</div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="q-top-block-mid">
                                                <div class="q-top-block-mid1 q-top-block-border">
                                                    {{ $parameter->parameter_name }}</div>
                                                <div class="q-top-block-mid1">
                                                    <input type="hidden" value="{{ $parameter->id }}"
                                                        name="parameter_id" id="parameter_id">
                                                    <input type="number" required min="1" name="score" id="score"
                                                        value="{{ isset($responseBrief)?$responseBrief->parameter_score:'' }}">
                                                </div>
                                                <div class="q-top-block-mid2">
                                                    <input type="text" required name="comment" id="comment"
                                                        value="{{ isset($responseBrief)?$responseBrief->comment:'' }}">
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                @forelse ($questions as $key=>$value )
                                <div class="col-sm-12">
                                    <div class="postCard">
                                        <div class="postWrap">
                                            <div class="postTitle">
                                                <h2 style="margin-bottom:20px;"><i class="fa fa-quora"
                                                        aria-hidden="true" style="color:#2d75a1;"></i>

                                                    {{ $value->question_text }}
                                                </h2>
                                            </div>


                                            <div class="col-sm-2">

                                                <select required
                                                    class="form-control form-select form-select-lg mb-3 col-sm-3A"
                                                    aria-label=".form-select-lg example" name="ans[{{ $value->id }}]">
                                                    <option readonly disabled @if (!isset($quest[$value->id]))
                                                        selected @endif>
                                                        Select</option>
                                                    <option @if (isset($quest[$value->id]))
                                                        @if ($quest[$value->id]=='1')
                                                        selected
                                                        @endif
                                                        @endif
                                                        value="1">Yes</option>
                                                    <option @if (isset($quest[$value->id]))
                                                        @if ($quest[$value->id]=='2')
                                                        selected
                                                        @endif
                                                        @endif value="2">May be</option>
                                                    <option @if (isset($quest[$value->id]))
                                                        @if ($quest[$value->id]=='3')
                                                        selected
                                                        @endif
                                                        @endif value="3">Partial</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                @empty
                                <div class="col-sm-12">
                                    <div class="postCard">
                                        <div class="postWrap">
                                            <div class="postTitle">
                                                <h2 style="margin-bottom:20px;"><i class="fa fa-quora"
                                                        aria-hidden="true" style="color:#2d75a1;"></i>
                                                    No question found.</h2>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                @endforelse
                                <div class="button-section">
                                    <a class="btn btn-warning 
                            @if (!$previous_record)
                            disabled
                            @endif" @if ($previous_record) href="{{ url('mentor-feedback',$previous_record->id) }}"
                                        @endif>Previous</a>


                                    <button type="submit" class="btn btn-success \ text-center">Save
                                        & {{ (!$next_record)?'Finished':'Next' }}
                                    </button>

                            </form>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <script src="{{ asset('public/feedback') }}/jquery.min.js"></script>
        <script src="{{ asset('public/feedback') }}/popper.min.js"></script>
        <script src="{{ asset('public/feedback') }}/bootstrap.min.js"></script>
        <script src="{{ asset('public/feedback') }}/custom.js"></script>



        <script src="{{ asset('public/feedback') }}/bootstrap3-typeahead.min.js"></script>
        <script src="{{ asset('public/feedback') }}/select2.full.min.js"></script>

        <script src="{{ asset('public/feedback') }}/ckeditor.js"></script>
        <script type="text/javascript" src="{{ asset('public/feedback') }}/jquery.validate.min.js"></script>
        <script type="text/javascript" src="{{ asset('public/feedback') }}/additional-methods.min.js"></script>

        <script type="text/javascript" src="{{ asset('public/feedback') }}/jquery.fancybox.pack.js"></script>
        <script type="text/javascript" src="{{ asset('public/feedback') }}/jquery.fancybox-buttons.js"></script>
        <script type="text/javascript" src="{{ asset('public/feedback') }}/jquery.fancybox-media.js"></script>
        <link href="{{ asset('public/feedback') }}/jquery.timepicker.css" rel="stylesheet">

        <script src="{{ asset('public/feedback') }}/jquery.timepicker.js" charset="UTF-8"></script>

        <script>
            // $.fn.modal.Constructor.prototype._enforceFocus = function() {
            //     $(document).off('focusin.bs.modal').on('focusin.bs.modal', $.proxy((function(e) {
            //         if (this.$element[0] !== e.target && !this.$element.has(e.target).length && !$(e.target).closest('.cke_dialog, .cke').length) {
            //         this.$element.trigger('focus');
            //         }
            //     }), this));
            // };
            $( function () {
                $( '.select2' ).select2();
                $( '.indusCatIds' ).select2( {
                    //placeholder: "Choose industry verticals",
                    //allowClear: true
                    //dropdownParent: $("#questionModal")
                } );
                $( '#opnQmodal' ).on( 'click', function () {
                    $( '#questionModal' ).modal();
                    var editorPostInfo = CKEDITOR.replace( 'post_info', {
                        customConfig: "https://pms.karmickdev.com/public/assets/ckeditor/minipost_config.js",
                    } );
                } );
                var path = "https://pms.karmickdev.com/autocomplete";
                $( '#autocomplete' ).typeahead( {
                    minLength: 2,
                    source: function ( query, process ) {
                        return $.get( path, {
                            query: query
                        }, function ( data ) {
                            return process( data );
                        } );
                    },
                    afterSelect: function ( data ) {
                        console.log( data.name );
                        $( 'form[name="frmx_src"]' ).submit();
                    }
                } );
                $( "body" ).on( 'keypress', '.onlyNumber', function ( evt ) {
                    var charCode = ( evt.which ) ? evt.which : event.keyCode;
                    if ( charCode > 31 && ( charCode < 48 || charCode > 57 ) )
                        return false;
                    return true;
                } );



                $( ".checkbox-menu" ).on( "change", "input[type='checkbox']", function () {
                    $( this ).closest( "li" ).toggleClass( "active", this.checked );
                } );

                $( document ).on( 'click', '.allow-focus', function ( e ) {
                    e.stopPropagation();
                } );



                /* $(document).on('click', '.filterCheckbox', function (e) {
                	var val = [];
                	$(':checkbox:checked').each(function(i){
                	  val[i] = $(this).val();
                	});
                	FinalCategory = val.toString();
                	//var dataString = +val;
                	//console.log(val);
                	
                	 $.ajax({
                		  url: '' ,
                		  data: "postdata="+ FinalCategory,
                		  type: 'GET',
                		  dataType: 'html',
                		  success: function(response) {
                			console.log(response);
                			$('#ajaxPost').html(response);
                			
                		  }
                		});   
                		
                }); */


                /* function changePosts(){
		   alert(1);
		   
	   } */


            } );
        </script>



        <script>
            $( document ).ready( function () {
                $( '#addPostType' ).on( 'change', function () {
                    if ( $( this ).val() == '2' ) {
                        $( '.privateMemberBoxDiv' ).css( {
                            'display': 'block'
                        } );
                    } else {
                        $( '.privateMemberBoxDiv' ).css( {
                            'display': 'none'
                        } );
                        $( '.private_member_id' ).val( '' ).trigger( 'change' );
                        $( '#privateMemberId-error' ).text( '' );
                    }
                } );
                $( 'body' ).on( 'change', '.select2', function () {
                    if ( $( this ).val() != '' ) {
                        $( '#' + $( this ).attr( 'id' ) + '-error' ).text( '' );
                    }
                } );
            } );
            // var editorPostInfo = CKEDITOR.replace( 'post_info', {
            //   customConfig: "https://pms.karmickdev.com/public/assets/ckeditor/minipost_config.js",
            // } );

            var Qfm = $( '#frmxq' );


            //    CKEDITOR.replace( 'post_info', {
            //    removeButtons: 'Cut,Copy,Paste,Undo,Redo,Anchor'
            //});

            Qfm.on( 'submit', function () {
                CKEDITOR.instances.post_info.updateElement();
            } );
            Qfm.validate( {
                errorElement: 'span',
                errorClass: 'roy-vali-error',
                ignore: [],
                rules: {
                    post_title: {
                        required: true,
                    },
                    post_info: {
                        required: true
                    },
                    private_member_id: {
                        required: function () {
                            return $( "#addPostType" ).val() == '2';
                        }
                    }
                },
                messages: {
                    post_title: {
                        required: 'Please add your post title',
                    },
                    post_info: {
                        required: 'Please add your post details',
                    },
                    "industry_category_id[]": {
                        required: 'Please select industry verticals'
                    },
                    private_member_id: {
                        required: 'Please select an user'
                    }
                },
                errorPlacement: function ( error, element ) {
                    if ( element.hasClass( 'indusCatIds' ) ) {
                        error.insertAfter( element.parent() );
                    } else if ( element.hasClass( 'select2' ) ) {
                        error.insertAfter( element.parent() );
                    } else if ( element.attr( "data-error-container" ) ) {
                        error.appendTo( element.attr( "data-error-container" ) );
                    } else {
                        error.insertAfter( element );
                    }
                }
            } );
        </script>
        <script>
            $( document ).ready( function () {

                $( ".fancybox" ).fancybox();
                $( 'body' ).on( 'click', '.addCommentBTN', function () {
                    var postID = $.trim( $( this ).data( 'postid' ) );
                    var parentID = $.trim( $( this ).data( 'postpid' ) );
                    var commentTxt = $( '#post_comment_' + postID + '-' + parentID ).val();

                    var videoText = $( '#post_video_' + postID + '-' + parentID ).val();

                    var formData = new FormData;
                    formData.append( '_token', "267rCGNr1mKACeGfDQ5b0WcHsrYQw6nPpJWZe2gF" );

                    var image_id = 'post_image_' + postID + '-' + parentID;


                    var image_files = $( "input#" + image_id )[ 0 ].files;
                    //console.log(files.length);

                    if ( image_files.length > 4 ) {
                        var err_image = 'error_image_' + postID + '-' + parentID;
                        $( '#' + err_image ).text( "Maximum 4 Images Allowed" );
                        return false;
                    }

                    for ( var i = 0; i < image_files.length; i++ ) {
                        formData.append( "images[]", image_files[ i ], image_files[ i ][ 'name' ] );

                    }

                    /* var video_id ='post_video_' + postID + '-' + parentID;

                     var video_files =$("input#"+video_id)[0].files;
                    

                    
                     for(var i=0;i<video_files.length;i++){
                         formData.append("video", video_files[i], video_files[i]['name']);

                     }*/


                    formData.append( "post_id", postID );
                    formData.append( "reply_text", commentTxt );
                    formData.append( "replied_on", parentID );

                    formData.append( "video_url", videoText );


                    if ( postID != '' && parentID != '' && commentTxt != '' ) {
                        //alert("entert");return false;
                        $.ajax( {
                            url: "https://pms.karmickdev.com/add-comment",
                            method: 'POST',
                            data: formData,
                            dataType: 'JSON',
                            contentType: false,
                            cache: false,
                            processData: false,

                            beforeSend: function () {
                                $( '#addCommentBTN_' + postID + '-' + parentID ).attr(
                                    'disabled', 'disabled' );
                                $( '#addCommentBTN_' + postID + '-' + parentID ).css( {
                                    'background-color': '#cecece'
                                } );
                            },
                            success: function ( data ) {
                                if ( data.status == 'ok' ) {
                                    var ctxt = '';
                                    var rtxt = '';
                                    $( '#addCommentBox_' + postID + '-' + parentID )
                                        .prepend( data.repComtHtml );
                                    $( '#post_comment_' + postID + '-' + parentID ).val(
                                        '' );
                                    $( '#post_image_' + postID + '-' + parentID ).val( '' );
                                    $( '#post_video_' + postID + '-' + parentID ).val( '' );

                                    if ( data.commentCount > 1 ) {
                                        ctxt = 'Comments';
                                    } else {
                                        ctxt = 'Comment';
                                    }
                                    $( '#commCount_' + postID ).text( data.commentCount +
                                        ' ' + ctxt );
                                    if ( data.replyCount > 1 ) {
                                        rtxt = 'Replies';
                                    } else {
                                        rtxt = 'Reply';
                                    }
                                    $( '#replyCount_' + postID + '-' + parentID ).text( data
                                        .replyCount + ' ' + rtxt );
                                    $( '#addCommentBTN_' + postID + '-' + parentID )
                                        .removeAttr( 'disabled' );
                                    $( '#addCommentBTN_' + postID + '-' + parentID ).css( {
                                        'background-color': '#2d75a1'
                                    } );
                                    if ( parentID > 0 ) {
                                        $( '#replyList_' + postID + '-' + parentID ).show();
                                    }
                                    //console.log(data);
                                }
                            }
                        } );
                    }
                } );
                $( '.redm' ).on( 'click', function () {
                    var _thisID = $( this ).attr( 'id' );
                    var _conID = _thisID.split( '_' )[ 1 ];
                    $( '#mincontent_' + _conID ).hide();
                    $( '#fullcontent_' + _conID ).show();
                } );

                // video preview in browser
                var videoPreview = function ( input, placeVideoPreview ) {

                    if ( input.files ) {
                        var filesAmount = input.files.length;

                        for ( i = 0; i < filesAmount; i++ ) {
                            var reader = new FileReader();

                            reader.onload = function ( event ) {
                                var videonode = $( $.parseHTML( '<video><source src=""></video>' ) )
                                $( videonode ).find( 'source' ).attr( 'src', event.target.result )
                                    .appendTo( placeVideoPreview );
                            }

                            reader.readAsDataURL( input.files[ i ] );
                        }
                    }
                };

                $( '.add-video' ).on( 'change', function () {
                    videoPreview( this, 'video.vid-preview' );
                    $( '.video-preview' ).css( 'display', 'block' );
                } );
            } );

            function getComboA( selectObject, id ) {

                imagesPreview( selectObject, 'div.img_preview_' + id );
                document.getElementById( "img_preview_" + id ).innerHTML = "";
            }

            // Multiple images preview in browser
            function imagesPreview( input, placeToInsertImagePreview ) {

                if ( input.files ) {
                    var filesAmount = input.files.length;

                    for ( i = 0; i < filesAmount; i++ ) {
                        var reader = new FileReader();

                        reader.onload = function ( event ) {
                            $( $.parseHTML( '<img>' ) ).attr( 'src', event.target.result ).appendTo(
                                placeToInsertImagePreview );
                        }

                        reader.readAsDataURL( input.files[ i ] );
                    }
                }
            };

            function openVideoModal( video_url )

            {

                $( "#cartoonVideo" ).attr( 'src', video_url );
                //alert(video_url);
                $( "#myModal" ).modal( 'show' );
            }

            function addFavourate( post_id, user_id ) {
                var formData = new FormData;

                var status = $( "#post_hidden_" + post_id ).val();



                formData.append( '_token', "267rCGNr1mKACeGfDQ5b0WcHsrYQw6nPpJWZe2gF" );
                formData.append( "post_id", post_id );
                formData.append( "user_id", user_id );
                formData.append( "status", status );


                $.ajax( {
                    url: "https://pms.karmickdev.com/add-favourite",
                    method: 'POST',
                    data: formData,
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,

                    beforeSend: function () {

                    },
                    success: function ( data ) {
                        if ( data.status == 'ok' ) {


                            var src1 = "https://pms.karmickdev.com/public/front_end/images/heart_two.jpg";

                            var src2 = "https://pms.karmickdev.com/public/front_end/images/heart_one.png";


                            if ( status == 1 ) {
                                $( '.post_fav_' + post_id + ' img' ).attr( "src", src1 );

                                $( "#post_hidden_" + post_id ).val( 0 );
                            } else {
                                $( '.post_fav_' + post_id + ' img' ).attr( "src", src2 );

                                $( "#post_hidden_" + post_id ).val( 1 );
                            }


                        }
                    }
                } );
            }
        </script>
    </footer>



</body>

</html>