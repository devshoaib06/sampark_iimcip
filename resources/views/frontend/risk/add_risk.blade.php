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
        <div class="postCard manage-wrap">
            <div class="postWrap">
                <div class="pwdbox">
                    <h3>Add Risk</h3>
                    
					<form name="frm" id="frm" action="<?php echo url('risk/save_risk') ?>" method="POST" enctype="multipart/form-data" >
		

		
                    {{ csrf_field() }}
					
					<input type="hidden" id="risk_id" name="risk_id" value="">
                       
                        <div class="row">
                           
							
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>Title:<span class="requstar">*</span></label>
                                    <input type="text" class="form-control" name="title"  value=""/>
                                </div>

                                <div class="form-group">
                                    <label>Status:<span class="requstar">*</span></label>
                                     <select class="form-control" name="status">
                                        <option value="1">Open</option>
                                        <option value="0">Close</option>


                                    </select>
                                </div>
                            </div>
							
							
							<div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>Mitigation Strategy:<span class="requstar">*</span></label>
                                    <textarea class="form-control" name="mitigation_strategy" cols="50" rows="10" style="min-height: 126px;"></textarea>
                                </div>
                            </div>
											
							
							
							
							
							
							

                          
                            
                        </div>
                            
                        
                       
                       
                        
                    
                    <div class="mt-3">
                        <input type="submit" class="btn btn-primary" value="Save">
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
                        <iframe id="cartoonVideo" width="560" height="315" src="//www.youtube.com/embed/YE7VzlLtp-4" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>

</div>


<!--Question modal-->
@include('frontend.includes.add_question_modal')

@push('page_js')
<script>

$('#frm').validate({
    errorElement: 'span',
    errorClass: 'roy-vali-error',
    rules: {
       
        title: {
            required: true
        },
        mitigation_strategy: {
            required: true
        },
        
        
        
    },
    messages: {
        
        title: {
            required: 'Please enter title'
        },
        mitigation_strategy:{
            required: 'Please enter mitigation strategy',
            
        },
       
        
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



@endpush
@endsection
