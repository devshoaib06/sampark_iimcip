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
            <div class="container">
                <div class="innercont postWrap mt-3">
                    <div class="whirepart">
                        <h2 class="list_heading">Appointment List </h2>
                        <div style="clear:both;"></div>
                        <div class="panel panel-default m_top20">
                            <div class="panel-body">
                                <div class="">
                                <!-- main body part -->
                                <ul class="available-box">
                                    <li><span style='background-color:#61a14d'></span> Available time.</li>
                                    <li><span style='background-color:#1468c4'></span> Scheduled Appointment.</li>
                                    <li><span style='background-color:#e4230d'></span> Time expired.</li>
                                </ul>
                                <div style="clear:both;padding-bottom: 25px;"></div>
                                    <div id="eventViewer"></div>
                                    <div id='calendar'></div>
                                    <div class="fc-event-inner"></div>
                                <!-- main body part end -->
                                </div>
                            </div>
                        </div>
                        <div class="text-center">

                        </div>   
                    </div>
                </div>
            </div>
   
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">
                
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">My Availability/Schedule Appointment</h4>
                    </div>
                    <div id="modal-body">
                      
                        
                    </div>
                  </div>
                  
                </div>
            </div>
            <div class="modal fade" id="myStartupModal" role="dialog">
                <div class="modal-dialog">
                
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Schedule Appointment</h4>
                    </div>
                    <div id="startup-modal-body">
                      
                        
                    </div>
                  </div>
                  
                </div>
            </div>
            <div class="modal fade" id="myAppointmentModal" role="dialog">
                <div class="modal-dialog" id="manage_modal">
                
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">My Availability/Schedule Appointment</h4>
                    </div>
                    <div >
                      
                        
                    </div>
                  </div>
                  
                </div>
              </div>
            <div class="modal fade" id="event_details" role="dialog">
                <div class="modal-dialog">
                
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Appointment Details</h4>
                    </div>
                    <div>
                    <div class="modal-body">
                        <div class="form-group"  id="event_body">
                           
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-small" data-dismiss="modal" style=" background-color: #4CAF50;">Close</button>
                        <button type="button" class="btn btn-small" data-dismiss="modal" style=" background-color: #4CAF50;" onclick="saveComment()">Save Comment1</button>
                  </div>

                
                </div>
                  </div>
                  
                </div>
            </div>
    </div>

<!--Question modal-->
@include('frontend.includes.add_question_modal')

@endsection



@push('page_js')

<link href="https://iimciporg.karmickdev.com/public/calender/fullcalendar.min.css" rel='stylesheet' />
<link href="https://iimciporg.karmickdev.com/public/calender/fullcalendar.print.min.css" rel='stylesheet' media='print' />
<script src="https://iimciporg.karmickdev.com/public/calender/lib/moment.min.js"></script>
<script src="https://iimciporg.karmickdev.com/public/calender/fullcalendar.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" charset="UTF-8"></script>
<script>
   
$(document).ready(function() {
    var calendar = $('#calendar').fullCalendar({
        //editable:true,
        header:{
            left:'',
            center:'prev,title,next',
            right:'month,agendaWeek,agendaDay,listWeek'
        },
        events: '{{URL::route('appointment.ajax_data_new')}}',
        selectable:true,
        selectHelper:true,
        eventLimit: true,
        <?php if(Auth::user()->user_type=='6' || Auth::user()->user_type=='2'){ ?>
        select: function(start, end, allDay){
            var startnew = moment(start).format('YYYY-MM-DD');
            var toDatenew = moment(new Date()).format('YYYY-MM-DD');
            //console.log(startnew +' space '+ toDatenew);
            if (startnew >= toDatenew) {
                var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                <?php if(Auth::user()->user_type=='6'){ ?>
                    var title = openForm(start,end);
                <?php }else{ ?>
                    var title = openStartupForm(start,end);
                <?php }?>
            }else{
                alert("Date already expired !! You can't schedule.");
            }
            
            
        },
        <?php } ?>
        // eventMouseover: function(event, element) {
        //     $(".tipclass" ).remove();
        //     var a = $('.fc-title', this).html();
        //     $('div:first', this).prepend("<span class='tipclass'>"+a+"</span>");
        // },

        // eventMouseout: function(event, element) {
        //     //$("#eventViewer").html('');
        // },
        eventClick:function(event){
            var availableDate = moment(event.apoointment_date).format('YYYY-MM-DD');
            var toDate = moment(new Date()).format('YYYY-MM-DD');
            
            if(event.type=='app'){
                viewEvent(event);
            }else if(event.type=='av' && event.availablity_status=='0'){
                alert('Already Booked');
            }else{
                if (availableDate >= toDate) {
                    if(event.id!=''){
                        if(event.type=='av' && event.availablity_status=='1'){
                            manageEvent(event);
                        }
                    }
                }else{
                    alert("Date already expired !! ");
                }
            }
        },

    });
});
function manageEvent(event){
    var id = event.id;
    if(id!=''){
        $('#myAppointmentModal').modal('show');
        $.ajax({
             url: "{{URL::route('appointment.get_appointment_modal')}}",
             method: 'get',
             data: {
                id: id
             },
             success: function(result){
                //console.log(result);
                $('#manage_modal').html('');
                $('#manage_modal').html(result);
             }
         });
            return true;
    }else{
        alert('Please select an event.');
    }
}

function openForm(formattedStart,formattedEnd){
    $('#myModal').modal('show');
     // alert("2");
    $.ajax({
         url: "{{URL::route('appointment.appointment_form_mentor')}}",
         method: 'get',
         data: {
            available_date: formattedStart
         },
         success: function(result){
            //console.log(result);
            $('#modal-body').html('');
            $('#modal-body').html(result);
         }
     });
     return true;
}

function openStartupForm(formattedStart,formattedEnd){
  //alert("1");
    $('#myStartupModal').modal('show');
    $.ajax({
         url: "{{URL::route('appointment.appointment_form_startup')}}",
         method: 'get',
         data: {
            available_date: formattedStart
         },
         success: function(result){
            //console.log(result);
            $('#startup-modal-body').html('');
            $('#startup-modal-body').html(result);
         }
     });
     return true;
}

function viewEvent(event){
    //console.log(event);
    //alert("123");
    $('#event_details').modal('show');
    if(event.id!=''){
        $.ajax({
            url: "{{URL::route('admin.view_appointment')}}",
            method: 'get',
            data: {
               appointment_id: event.id
            },
            success: function(result){
               //console.log(result);
               $('#event_body').html('');
               $('#event_body').html(result);
            }
        });    
    }else{
        $('#event_body').html('Appointment not found');
    }
    return true;
    /*var subject = event.subject;
    var mentor_name = event.investor_name;
    var startup_name = event.startup_name;
    var app_date = event.apoointment_date;
    var app_time = event.apoointment_time;
    
    
    var modalHtml = "<b>Subject : </b>"+subject+"<br><b>Mentor name : </b>"+mentor_name+"<br><b>Startup name : </b>"+startup_name+" "+"<br><b>Date : </b>"+app_date+"<br><b>Time : </b>"+app_time;
    $('#event_body').html(modalHtml);*/
}
</script>
<script>
function saveComment(){
    var comment = $('#comment').val();
    var app_id = $('#app_id').val();
    if(comment!=''){
        $.ajax({
            url: "{{URL::route('admin.savenote')}}",
            method: 'get',
            data: {
               app_id: app_id,
               comment:comment
            },
            success: function(result){
               //console.log(result);
               alert(result);
            }
        });    
    }else{
        alert('Please enter note.');
    }
}
</script>
<style type="text/css">
  .fc-event-hover {
  position: relative !important;
  height: 17px;
}
.fc-event-hover .fc-content {
  position: absolute !important;
  top: -1px;
  left: 0;
  background: red;
  z-index: 99999;
  width: auto;
  overflow: visible !important;
  background-color: #3a87ad;
  padding: 1px;
  border-radius: 2px;
}
.fc-content-skeleton tr td:last-child .fc-event-hover .fc-content {
  left: auto;
  right: 0;
}
</style>
<style>

.available-box{list-style:none; margin:0 0 50px; padding:0; clear:both;}
.available-box li{ float:left; margin-right:30px;}
.available-box span{ width:15px; height:15px; display:inline-block}
.fc-content span.tipclass {
    opacity: 0;
    position: absolute;
    left: 0;
    top: 16px;
    z-index: 999999;
    display: block;
    background: #000;
    padding: 3px;
    font-size: 11px;
    font-weight: bold;
    width: 100px;
    text-align: center;
    border-radius: 4px;
    white-space: normal;
}
.fc-content {
    position: relative;
    overflow: inherit !important; z-index: inherit !important;
}
.fc-content:hover span.tipclass {
    opacity: 1;
}
.fc-ltr .fc-basic-view .fc-day-top .fc-day-number {
    float: right;
    font-size: 17px;
    font-weight: bold;
}
</style>
@endpush

    