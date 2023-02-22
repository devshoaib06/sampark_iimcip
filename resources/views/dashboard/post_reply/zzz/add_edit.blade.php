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
   
      <a href="{{ route('main_post.all') }}" class="btn btn-primary"> All Posts</a>
    
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
        <div class="box-body">
          <form name="frm" id="frmx" action="@if( isset($data) ){{ route('main_post.update', array('id' => $data->id)) }}@else{{ route('main_post.save') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="row page_block">
            <div class="col-md-10">
              <div class="form-group">
                <label>Post Title : <em>*</em></label>
                <input type="text" name="post_title" id="post_title" class="form-control" placeholder="Enter Post Title" value="@if( isset($data) ){{ $data->post_title }}@endif" required="required">
              </div>
              
              
              <div class="form-group">
                <label>Post Information : <em>*</em></label>
                <textarea name="post_information" id="post_information" class="form-control" style="height: 100px;" placeholder="Enter Post Information">@if( isset($data) ){{ html_entity_decode($data->post_info, ENT_QUOTES) }}@endif</textarea>
              </div>
                
                <div class="form-group">
                    <label>Post Type : <em>*</em></label>
                    <select name="post_type" class="form-control">
                    <option value="">-- Select Post Type --</option>
                    <option value="1" @if(isset($data) && $data->post_type == '1') selected="selected" @endif>Public</option>
                    <option value="2" @if(isset($data) && $data->post_type == '2') selected="selected" @endif>Private</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Private Member : <em>*</em></label>
                    <select name="private_member" class="form-control">
                    <option value="">-- Select Private Member --</option>
                    <option value="1" @if(isset($data) && $data->status == '1') selected="selected" @endif>Active</option>
                    <option value="2" @if(isset($data) && $data->status == '2') selected="selected" @endif>Inactive</option>
                    </select>
                </div>
            </div>
            
          </div>



          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                @if( isset($data) )
                <input type="submit" class="btn btn-primary" value="Save Changes">
                <input type="hidden" id="table_id" value="{{ $data->id }}">
                @else
                <input type="submit" class="btn btn-primary" value="Add Page">
                <input type="hidden" id="table_id" value="0">
                @endif
                <input type="hidden" id="table_name" value="posts">
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

@include('dashboard.modals.editor_imgmedia_modal')

@endsection


@push('page_js')
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
var editor_pg_cont = CKEDITOR.replace( 'pg_cont', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );
var editor_description = CKEDITOR.replace( 'description', {
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
    slug: {
      required: true,
      nowhitespace: true,
      pattern: /^[A-Za-z\d-.]+$/,
      remote:{
        url: "{{ route('checkSlugUrlSelf') }}",
        type: "post",
        data: {
          "slug_url": function() {
            return $( "#pgSlug" ).val();
          },
          "_token": function() {
            return "{{ csrf_token() }}";
          },
          "id": function() {
            return $( "#table_id" ).val();
          },
          "tab": function() {
            return $( "#table_name" ).val();
          }
        }
      }
    }
  },
  messages: {
    post_title: {
      required: 'Please Enter Title or Heading.'
    },
    slug: {
      required: 'Please Enter Page URL or Link.',
      nowhitespace: 'White Space or Blank Space Not Allowed, Use Hyphen.',
      pattern: 'Any Special Character Not Allowed, Except Hyphen.',
      remote: 'This URL Already Exist, Try Another.'
    }
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

@include('dashboard.modals.editor_imgmedia_modal_script')

@endpush