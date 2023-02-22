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
                    <h3>Add Task</h3>
                    
					<form name="frm" id="frm" action="<?php echo url('create_tasks') ?>" method="POST" enctype="multipart/form-data" >
		

		
                    {{ csrf_field() }}
					
					<input type="hidden" id="risk_id" name="risk_id" value="">
                       
                        <div class="row">
                           
							
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Task Title:<span class="requstar">*</span></label>
                                   <input class="form-control" required="required" name="title" type="text">
                                </div>
                            </div>
							
							
							<div class="col-md-12">
                                <div class="form-group">
                                    <label>
Task Details:<span class="requstar">*</span></label>
                                    <textarea class="form-control" required="required" name="description" cols="50" rows="10"></textarea>
                                </div>
                            </div>
							
							<div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>Assign To:<span class="requstar">*</span></label>
                                     <select name="assigned_to" class="form-control" required="">
                                        <?php //var_dump($userList); die; ?>
                                        <option value="">Select</option>
                                        @foreach($userList as $member)
                                        <option value="{{$member->id}}" @if(isset($start_up_id) && ($member->id == $start_up_id)) selected @endif >{{$member->member_company}}</option>
                                       @endforeach
                                    </select>
                                </div>
                            </div>
											
							<div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>Deadline:<span class="requstar">*</span></label>
                                     <input class="form-control dead_line" id="dead_line" data-date-format="yyyy-mm-dd" data-link-format="yyyy-mm-dd" required="required" name="dead_line" type="text" value="">
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
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#dead_line" ).datepicker();
  } );
  </script>
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
