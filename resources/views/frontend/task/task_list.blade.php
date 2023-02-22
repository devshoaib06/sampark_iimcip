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
        <div class="postWrap mt-3">
            <form name="frmx_src" action="{{ route('front.mentor.startup') }}" method="GET">
            <div class="postWrap my-3 search-alpha1">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <strong>Task List: </strong>
                                    </div>
                                </div>
                             
                        </div>

            </form>
            <table class="table table-bordered" width="50%">
            <thead>
                <tr> 
                    <th width="50%">PM</th>
                    <th width="50%">Startup</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                 <td>
                    <div class="PrtflioBox">
                        <div class="PrtflioBox_image">
                            <img src="https://iimciporg.karmickdev.com/public/portfolio_manager_photo/1552042034-soujitdas.jpg" alt="" width="100">


                        </div>
                        <div class="PrtflioBox_info">

                            <div class="PrtflioBox_name"><b>Soujit Das</b></div>
                            <div class="PrtflioBox_email"><b>E: </b>soujit.das@iimcip.org</div>
                            <div class="PrtflioBox_contact"><b>P: </b>9836099389</div>

                        </div>

                    </div>
                </td>
                <td>
                    <div class="PrtflioBox">
                        <div class="PrtflioBox_image">

                            <a href="" target="_blank">
                                <?php $image_path = "https://karmickdev.com/iimcip-network/public/uploads/user_images/thumb/";
             // echo  $startup_image = $image_path . $startup_details[0]['image']; 

               // echo $startup_details[0]['image'];



                                ?>

                                <img src="" alt="" class="dp_img">
                            </a>

                        </div>
                        <div class="PrtflioBox_info">
                            <div class="PrtflioBox_name"><b> <a href="" target="_blank">
                                <?php 
                               // echo '<pre>'; print_r($startup_details); 
                                echo $startup_details[0]['member_company'];
                            ?></a></b> </div>
                            <div class="PrtflioBox_email"><b>E:</b> <?php echo $startup_details[0]['email_id']; ?>  </div>
                            <div class="PrtflioBox_contact"><b>P:</b> <?php echo $startup_details[0]['mobile']; ?> </div>
                        </div>
                    </div>
                </td>


            </tr></tbody></table>
            <ul class="available-box">
                <li><span style="background-color:#fff;border:1px solid #ddd"></span> Read.</li>
                <li><span style="background-color:#FFFF66"></span> Unread.</li>
                <li><span style="background-color:#ccc"></span> Completed.</li>
                <li><span style="background-color:red"></span> Deadline Crossed.</li>
            </ul>   
            <table class="table table-bordered">
               <thead>
                   <tr> 

                    <th width='15%'>Task title</th>               
                    <th width='33%'>Task Description</th>
                    <th width='13%'>Task Created On</th>
                    <th width='12%'>Deadline</th>
                    <th width='13%'>Assigned By</th>
                    <th >Assigned To</th>
                    <th width='8%'>Remarks</th>
                    <th width='6%'>Completed?</th>

                </tr>
            </thead>
            <tbody>
                @if($taskHistory->count() !=0)
                @foreach($taskHistory as $task)
                <?php 
                            //echo '<pre>'; print_r($task); die;
                $dead_line_cross =false;
                if(strtotime($task['dead_line']) < strtotime(date('Y-m-d'))){ $dead_line_cross=true;}

                ?>
                <tr <?php if($task['is_complete']=='1'){echo 'style=background:#ccc';}elseif($dead_line_cross==true){echo 'style=background:red';}elseif($task['pm_view']=='0'){echo 'style=background:#FFFF66';}?>>
                    <td>{{$task['title']}}</td>
                    <td>{{$task['description']}}</td>
                    <td><?php echo date('jS M Y', strtotime($task['created_at'])); ?></td>
                    <td><div id="deal_line_<?php echo $task['id']?>" ><?php echo date('jS M Y',strtotime($task['dead_line'])).' </div> '; ?>

                </td>
                <td>{{$task['assigned_by']}} </td>
                <td>@if($task['assigned_to']) {{$task['assigned_to']}} @endif</td>

                <td><a href="{{ URL::route('mentor.review_task',array('task_id'=>$task['id'],'relation' =>$startup_details[0]['id'])) }}" class="btn btn-success"><span class="glyphicon glyphicon glyphicon-triangle-right" aria-hidden="true"></span> Remarks</a></td>
                <td>


                    <input type="hidden" name="task_id" value="{{$task['id']}}">
                    <select name="isComplete" class="form-control" onchange="is_complete('{{$task['id']}}')">
                        <option value="0" <?php if ($task['is_complete'] == 0) {
                            echo 'selected';
                        } ?>>No</option>
                        <option value="1" <?php if ($task['is_complete'] == 1) {
                            echo 'selected';
                        } ?>>Yes</option>                      
                    </select>
                    <!--                    <input type="submit" class="btn btn-small btn-info form-control" value="Update">-->

                </td>
            </tr>
            @endforeach
            @else
            <tr><td colspan="8" align='center'> Task Not Found</td></tr>
            @endif

            </tbody>
         </table>
        </div>
        
        </div>
        








</div>

<!--Question modal-->
@include('frontend.includes.add_question_modal')

@endsection



@push('page_js')
<script type="text/javascript">
    function is_complete(task_id){
     conf = confirm('Are you sure to change the task status?');
     if (conf){  
                                //  $('#task_form_' + task_id).submit();
                                        //alert(task_id);
                                        $.ajax({
                                            type : "POST",
                                            url : "{{ route('update_complete_task') }}",
                                            data : {
                                                "taskID" : task_id,
                                                "_token" : "{{ csrf_token() }}"
                                            },
                                            success: function(scatJson) {
                                                        //alert("1");
                                                        console.log(scatJson);
                                                        alert("Task Status Changed");
                                                        window.location.href=window.location.href;
                                                    }
                                                });
                                    }
                                }  



                            </script>
                            <style>

                            .available-box{list-style:none; margin:0 0 50px; padding:0; clear:both;}
                            .available-box li{ float:left; margin-right:30px;}
                            .available-box span{ width:15px; height:15px; display:inline-block}
                        </style>
                        @endpush

