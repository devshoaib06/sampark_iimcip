@push('page_css')
<style type="text/css">
/* .select2-container--default .select2-selection--multiple {
    background-color: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.1);
    -webkit-border-radius: 2px;
    border-radius: 2px;
    cursor: text;
    height: 42px;
}
.select2-container--default .select2-search--inline .select2-search__field {
    width:initial !important;
} */
</style>
@endpush

@php 
    $profileImage = asset('public/front_end/images/profile-pic.png');
    if(Auth::user()->image != '' && Auth::user()->image != null) {
        $profileImage = asset('public/uploads/user_images/thumb/'. Auth::user()->image);
    }
@endphp

@php 
    $postGuide = DB::table('post_guidlines')->first();
@endphp




<div class="modal fade" id="questionModal">
    <div class="modal-dialog">
        <div class="modal-content">
        <form name="frmxq" id="frmxq" action="{{ route('front.user.savepost') }}" method="POST" enctype="multipart/form-data" >
        {{ csrf_field() }}
            <div class="modal-header">
                <h5 class="modal-title" id="question">Your Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="questionTips">
                    <strong>Tips on making good post quickly</strong><br>
                    @if( isset($postGuide) ){!!$postGuide->post_guide!!}@endif
                </div>
                <div class="questionAsked">
                    <img src="{{ $profileImage }}" />
                    <a href="javascript:void(0);">{{ Auth::user()->contact_name }}</a>
                </div>
                <div class="incatdd">
                    <label>Choose Industry Verticals</label>
                    @if(isset($industry_category))
                        <select name="industry_category_id[]" id="indusCatIds" class="indusCatIds" multiple="multiple" style="width: 100%;" required="required">
                            <option value="All" {{request('industry_category')== "all" ? 'selected' : '' }}>All</option>
                            @foreach($industry_category as $ic)
                                <option value="{{ $ic->id }}">{{ $ic->industry_category }}</option>
                            @endforeach
                            
                        </select>
                    @endif
                </div>
                <div class="incatdd">
                    <label>Choose Category</label>
                    @if(isset($category))
                        <select name="category_id[]" id="catIds" class="indusCatIds" multiple="multiple" style="width: 100%;">
                            <option value=""></option>
                            @foreach($category as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="incatdd">
                    <label>Add Video</label>
                    <input type="text" class="form-control" name="video_link" placeholder="Enter YouTube or Vimeo Video Link"/>
                </div>
                <div class="questionBox">
                    <div style="margin-top: 15px;">
                        <input type="text" name="post_title" class="form-control" placeholder=" Post Title or Heading" required="required" />
                        <textarea class="form-control" name="post_info" id="post_info" placeholder="Post in details" required="required" data-error-container="#postInfo_error"></textarea>
                        <div id="postInfo_error"></div>
                        <!-- <select name="post_type" class="form-control" id="addPostType">
                            <option value="1">Public</option>
                            <option value="2">Private</option>
                        </select> -->
                    </div>
                    <div class="privateMemberBoxDiv" style="display: none;">
                        <select name="private_member_id" id="privateMemberId" class="form-control select2 private_member_id" style="width: 100%;">
                            <option value="">Select user</option>
                            @php $authID = Auth::user()->id; @endphp
                            @if(isset($privateMember) && !empty($privateMember))
                                @foreach($privateMember as $pm)
                                    @if($pm->id != $authID)
                                        <option value="{{ $pm->id }}">{{ $pm->contact_name }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
				
				 <div class="incatdd">
                    <label>Add Image</label>
                  
					<input type="file" name="image" class="form-control">
					
					{{--<span class="errormsg text-danger">{{ image }} </span> --}}
                  
                </div>
								
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit Post</button>
            </div>
        </form>
        </div>
    </div>
</div>

@push('page_js')

           
<script>
$(document).ready(function () {
    $('#addPostType').on('change', function() {
        if($(this).val() == '2') {
            $('.privateMemberBoxDiv').css({
                'display': 'block'
            });
        } else {
            $('.privateMemberBoxDiv').css({
                'display': 'none'
            });
            $('.private_member_id').val('').trigger('change');
            $('#privateMemberId-error').text('');
        }
    });
    $('body').on('change', '.select2', function() {
        if($(this).val() != '') {
            $('#' + $(this).attr('id') + '-error').text('');
        }
    });
});
// var editorPostInfo = CKEDITOR.replace( 'post_info', {
//   customConfig: "{{ asset('public/assets/ckeditor/minipost_config.js') }}",
// } );

var Qfm = $('#frmxq');
    
    
//    CKEDITOR.replace( 'post_info', {
//    removeButtons: 'Cut,Copy,Paste,Undo,Redo,Anchor'
//});
    
Qfm.on('submit', function() {
  CKEDITOR.instances.post_info.updateElement();
});
Qfm.validate({
    errorElement: 'span',
    errorClass : 'roy-vali-error',
    ignore: [],
    rules: {
        post_title: {
            required: true,
        },
        post_info: {
            required: true
        },
        private_member_id: {
            required: function() {
                return $("#addPostType").val() == '2';
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
    errorPlacement: function(error, element) {
        if (element.hasClass('indusCatIds')) {
            error.insertAfter(element.parent());
        } else if (element.hasClass('select2')) { 
            error.insertAfter(element.parent());
        } else if (element.attr("data-error-container")) { 
            error.appendTo(element.attr("data-error-container"));
        } else {
            error.insertAfter(element); 
        }
    }
});
</script>
@endpush