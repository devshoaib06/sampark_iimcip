@extends('dashboard.layouts.app')


@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($content_type))
    Edit Post
    @else
    Add New Post
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
  </ol>
</section>
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div class="row">
    <div class="col-md-6">
    @can('project-view')
      <a href="{{ route('main_post.all') }}" class="btn btn-primary"> All Posts</a>
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
          <h3 class="box-title">@if(isset($data)) Edit Post @else Add Post @endif</h3>

          <div class="box-tools pull-right">
            
          </div>
        </div>

          <?php 
          if(isset($data)){
              foreach($data As $data){
                  $id = $data->id;
              }
          }
          
          
          ?>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if( isset($data) ){{ route('main_post.update', array('id' => $id)) }}@else{{ route('main_post.save') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          
          <div class="row page_block">
            <div class="col-md-10">
              <div class="form-group">
                <label>Post Title : <em>*</em></label>
                <input type="text" name="post_title" id="post_title" class="form-control" placeholder="Enter Post Title" value="@if( isset($data) ){{ $data->post_title }}@endif" required="required">
              </div>

              <div class="form-group">
                <label>Post Information : </label>
                <textarea name="post_information" id="post_information" class="form-control" style="height: 100px;" placeholder="Enter Post Information">@if( isset($data) ){{ html_entity_decode($data->post_info, ENT_QUOTES) }}@endif</textarea>
              </div>

              <div class="form-group">
                <label> Video : </label>
               

                <input type="text" class="form-control" name="video_link" placeholder="Enter YouTube or Vimeo Video Link" value="@if( isset($data->video_link) ){{ $data->video_link }}@endif" />

                @if(!empty($data->video_link))

                      @php

                                $video_url = $data->video_link;


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

                                $data->video_link =getYoutubeEmbedUrl($data->video_link);

                            @endphp

                            <a   onclick ="openVideoModal('{{$data->video_link}}')"><img src="{{$thumbnail}}" class="pitch_admin"></a></li></span>

                @endif
              </div>


              
                
                
                <div class="form-group">
                    <label>Post Type : <em>*</em></label>
                    <select name="post_type" class="form-control" onchange="getMember(this.value);">
                    <option value="">-- Select Post Type --</option>
                    <option value="1" @if(isset($data) && $data->post_type == '1') selected="selected" @endif>Public</option>
                    <option value="2" @if(isset($data) && $data->post_type == '2') selected="selected" @endif>Private</option>
                    </select>
                </div>
                <div id="memDiv" @if((isset($data) && $data->private_member_id=='') || !isset($data)) style="display: none;" @endif class="form-group">
                    <label>Private Member : </label>
                    <select name="private_member" id="private_member" class="form-control">
                    <option value="">-- Select Private Member --</option>
                    @if($member)
                    @foreach($member As $member)
                        <option value="{{ $member->id }}" @if(isset($data) && $data->private_member_id == $member->id) selected="selected" @endif >{{ $member->contact_name }}</option>
                    @endforeach
                    @endif
                    </select>
                </div>
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Industry : <em>*</em></label>
                       <select name="industry_id[]" class="form-control" multiple="">
                        <!-- <option>Select Industry Category</option> -->
                        @if(!empty($industryList))
                         @foreach($industryList as $s)
                        <option value="{{ $s->id }}" @if( isset($PostIndustry) && in_array($s->id, $PostIndustry)) selected="selected" @endif>{{ $s->industry_category }}</option>
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
                      <label>Category : </label>
                       <select name="category_id[]" class="form-control" multiple="">
                        <!-- <option>Select Industry Category</option> -->
                        @if(!empty($categoryList))
                         @foreach($categoryList as $s1)
                        <option value="{{ $s1->id }}" @if( isset($PostCategory) && in_array($s1->id, $PostCategory)) selected="selected" @endif>{{ $s1->name }}</option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                  </div> 
                </div>

                @if( isset($data) )

                  @if(Auth::user()->id==1)

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Set Bookmark : </label>
                         <input type="radio" name="is_bookmarked"  onclick="showDate(1)"  value="1" 
                      @if(isset($data->is_bookmarked))
                          @if($data->is_bookmarked == 1) 
                              checked

                          @endif                      
                      @endif >Yes
                      <input type="radio" name="is_bookmarked"   value="0" onclick="showDate(0)" 
                      @if(isset($data->is_bookmarked))
                          @if($data->is_bookmarked == 0) 
                              checked

                          @endif                      
                      @endif>No
                      <input type="hidden" id="is_bookmarked" value="{{$data->is_bookmarked}}">
                      </div>
                    </div>


                    
                  </div>
                  <div class="row">
                    


                    <div class="col-md-6" id="bookmarkdate" style="display: none">
                        <div class="form-group">

                          <label>Bookmark End Date : </label>

                          <input type="text" name="bookmark_end_date" id="bookmark_end_date" class="date form-control" value="@if( isset($data->bookmark_end_date) ){{ $data->bookmark_end_date }}@endif" class="date form-control">

                        </div>

                    </div> 
                  </div>

                  @endif

                @else
                  @if(Auth::user()->id==1)

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Set Bookmark : </label>
                         <input type="radio" name="is_bookmarked"   value="1" onclick="showDate(1)" >Yes
                      <input type="radio" name="is_bookmarked"   value="0" onclick="showDate(0)" >No
                      </div>
                    </div>
                    
                  </div>

                  <div class="row">
                    
                    <div class="col-md-6" id="bookmarkdate" style="display: none">
                        <div class="form-group">

                          <label>Bookmark End Date : </label>

                          <input type="text" name="bookmark_end_date" class="date form-control" >

                        </div>

                    </div> 
                  </div>

                  @endif


                @endif
                
            </div>
              
              
            
          </div>





          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                @if( isset($data) )
                <input type="submit" class="btn btn-primary" value="Save Changes">
                <input type="hidden" id="table_id" value="{{ $id }}">
                @else
                <input type="submit" class="btn btn-primary" value="Add Post">
                <input type="hidden" id="table_id" value="0">
                @endif
                <input type="hidden" id="table_name" value="Posts">
              </div>
            </div>
          </div>
          </form>
          <!-- Modal -->
        <!-- Modal HTML -->
        <div id="myModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                       <!--  <h4 class="modal-title">Video</h4> -->
                    </div>
                    <div class="modal-body">
                        <iframe id="cartoonVideo" width="560" height="315" src="//www.youtube.com/embed/YE7VzlLtp-4" frameborder="0" allowfullscreen></iframe>
                    </div>
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
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('public/assets/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
<script type="text/javascript">

var editor_description = CKEDITOR.replace( 'post_information', {
  customConfig: "{{ asset('public/assets/ckeditor/mini_config.js') }}",
} );
var fm = $('#frmx');
fm.validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
  normalizer: function( value ) {
    return $.trim( value );
  },
  rules: {
    post_title: {
      required: true,
      minlength: 3
    },
    
//    post_information: {
//      required: true,
//    },
    
    post_type: {
      required: true,
    },
    
//    private_member: {
//      required: true,
//    },

  },
  messages: {
    post_title: {
      required: 'Please Enter Post Title.'
    },
    
//    post_information: {
//      required: 'Please Enter Post Information.'
//    },
    
    post_type: {
      required: 'Please Select Post Type.'
    },
    
//    private_member: {
//      required: 'Please Select Private Member.'
//    },

  },
  errorPlacement: function(error, element) {
    element.parent('.form-group').addClass('has-error');
    if (element.attr("data-error-container")) { 
      error.appendTo(element.attr("data-error-container"));
    } else {
      error.insertAfter(element); 
    }
  },
  success: function(label) {
    label.closest('.form-group').removeClass('has-error');
  }
});
$( function() {

  <?php if( !isset($data) ) { ?>
  $('#page_title').on('blur', function() {
    if( $.trim( $(this).val() ) != '' ) {
      $('#pgSlug').val( string_to_slug( $(this).val() ) );
    }
  });
  <?php } ?>

  $('#metaboxClick').on('click', function() {
    $('.meta_panel').slideToggle();
  });
  
});
function string_to_slug(str) {
  str = str.replace(/^\s+|\s+$/g, "");
  str = str.toLowerCase();
  var from = "åàáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
  var to = "aaaaaaeeeeiiiioooouuuunc------";
  for (var i = 0, l = from.length; i < l; i++) {
    str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
  }
  str = str
    .replace(/[^a-z0-9 -]/g, "") // remove invalid chars
    .replace(/\s+/g, "-") // collapse whitespace and replace by -
    .replace(/-+/g, "-") // collapse dashes
    .replace(/^-+/, "") // trim - from start of text
    .replace(/-+$/, ""); // trim - from end of text
  return str;
}
</script>

<script type="text/javascript">
  
  $( document ).ready(function() {


    var is_bookmark =$("#is_bookmarked").val();
    
    if(is_bookmark == 1){

        $("#bookmarkdate").show();        
    }else{
        $("#bookmarkdate").hide();        
    }
});

</script>



<script type="text/javascript">
    function getMember(id){
//        alert(id);
        if(id==2){
            $("#memDiv").show();
        }else{
            $("#memDiv").hide();
            $('#private_member').val('');
        }
    }


    <?php if(isset($data)) {?> getMember( <?php echo $data->post_type; ?> );<?php }?>
</script>

<script type="text/javascript">

    $('.date').datepicker({  

       format: 'mm/dd/yyyy'

     });  

    function showDate(id){
   
    if(id == 1){
        $("#bookmarkdate").show();        
    }else{
        $("#bookmarkdate").hide();        
    }
  }

  function openVideoModal(video_url)

    {
         
        $("#cartoonVideo").attr('src', video_url);
            //alert(video_url);
        $("#myModal").modal('show');
    }

</script>  



@endpush