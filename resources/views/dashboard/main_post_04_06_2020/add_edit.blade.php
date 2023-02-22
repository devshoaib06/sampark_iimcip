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
                        <option value="{{ $member->id }}" @if(isset($data) && $data->private_member_id == $member->id) selected="selected" @endif >{{ $member->first_name." ".$member->last_name }}</option>
                    @endforeach
                    @endif
                    </select>
                </div>
                
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
    function getMember(id){
//        alert(id);
        if(id==2){
            $("#memDiv").show();
        }else{
            $("#memDiv").hide();
            $('#private_member').val('');
        }
    }
</script>



@endpush