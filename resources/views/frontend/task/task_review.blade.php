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
    <div style="clear:both;"></div>
    <div class="col-sm-12">
        <div class="postWrap my-3">
            <h2 class="list_heading">Add Remark</h2>

         
            <form method="POST" action="<?php echo url('mentor/create_review') ?>" accept-charset="UTF-8" id="portForm" enctype="multipart/form-data">



               {{ csrf_field() }}
               <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
               <input type="hidden" name="investor_id" value="<?php echo $pm_id; ?>">
               <input type="hidden" name="startup_id" value="<?php echo $start_up_id; ?>">
               <div class="form-group">



                    <div class="form-group">
                        <!--                            <label>Remark</label>-->
                        <textarea class="form-control" required="required" name="remark" cols="50" rows="10"></textarea>

                    </div>
                    <div style="clear:both;"></div>
                    <div class="form-group">
                        </br> 
                        <input type="submit" class="btn btn-small btn-info" name="submit" value="Add Remark">
               <!--  <a href="{{ route('task_list', array('uid' => $pm_id)) }}" class="btn btn-danger"> Back</a> -->

                <a href="{{ route('task_list', array('uid' => $start_up_id)) }}" class="btn btn-dark"><span class="glyphicon glyphicon glyphicon-list" aria-hidden="true"></span> Back</a>

                    </div>
                </div>     

            </form> 
            <!-- copy of input fields group -->

        

            <div style="clear:both;"></div>
        
            </br>
            <b>Task Title:</b> <?php echo $taskList->title; ?> <br>
            <b>Task Description:</b> <?php echo $taskList->description; ?> <br>
            <b>Task Deadline:</b> <?php echo $taskList->dead_line; ?> <br>
            <!--                      <b>Start up Response: </b> No Response <br>-->
            <br><br>
            <h2>Remark History</h2>
            <table class="table table-bordered">
                <thead>
                    <tr> 
                        <th width="60%">Remark</th>               
                        <th width="20%">Remark by</th>
                        <th width="20%">Remarks Added On</th>

                    </tr>
                </thead>
                <tbody>
                   @foreach($taskReview as $task)          
                   <tr>
                    <td>{{$task->remarks}}</td>
                    <td>{{$task->contact_name}}</td>
                    <td>{{$task->created_at}}</td>
                </tr>
                @endforeach                              
            </tbody>
            </table>
            </div>
        </div>
    <div>

</div>

<!--Question modal-->
@include('frontend.includes.add_question_modal')

@endsection



@push('page_js')

@endpush

