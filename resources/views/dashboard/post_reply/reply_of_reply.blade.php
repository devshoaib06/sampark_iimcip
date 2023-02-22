@extends('dashboard.layouts.app')


@section('content_header')
<section class="content-header">
  <h1>
    
    Add New Reply Of Reply
    
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
      <a href="{{ route('post_reply.all') }}" class="btn btn-primary"> All Reply</a>
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
          <h3 class="box-title"> Add Reply Of Reply </h3>

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
          <form name="frm" id="frmx" action="@if( isset($data) ){{ route('post_reply.saveROR', array('id' => $id)) }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          
          <div class="row page_block">
            <div class="col-md-10">
              

              <div class="form-group">
                <label>Reply : </label>
                <textarea name="reply" id="reply" class="form-control" style="height: 100px;" placeholder="Enter Reply"></textarea>
              </div>

            </div>

          </div>


          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                @if( isset($data) )
                <input type="submit" class="btn btn-primary" value="Add Reply">
                <input type="hidden" id="table_id" value="{{ $id }}">
                
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

var editor_description = CKEDITOR.replace( 'reply', {
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