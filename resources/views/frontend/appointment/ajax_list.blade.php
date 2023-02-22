@extends('layouts.default')
@section('title')
    Investors/ Mentors
@stop

@section('content')



<link href="{{asset('public/calender/fullcalendar.min.css')}}" rel='stylesheet' />
<link href="{{asset('public/calender/fullcalendar.print.min.css')}}" rel='stylesheet' media='print' />
<script src="{{asset('public/calender/lib/moment.min.js')}}"></script>
<script src="{{asset('public/calender/lib/jquery.min.js')}}"></script>
<script src="{{asset('public/calender/fullcalendar.min.js')}}"></script>
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
<script>
   
  $(document).ready(function() {
   var calendar = $('#calendar').fullCalendar({
    editable:true,
    header:{
     left:'',
     center:'prev,title,next',
     right:'month,agendaWeek,agendaDay,listWeek'
    },
    events: '{{URL::route('appointment.ajax_data')}}',
    selectable:true,
    selectHelper:true,
    eventLimit: true,

    /*select: function(start, end, allDay)
    {
     var title = prompt("Enter Event Title");
     if(title)
     {
      var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
      var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
      $.ajax({
       url:"insert.php",
       type:"POST",
       data:{title:title, start:start, end:end},
       success:function()
       {
        calendar.fullCalendar('refetchEvents');
        alert("Added Successfully");
       }
      });

     }
    },*/
    /*eventMouseover: function(event, jsEvent, view) {
    $('.fc-event-inner', this).append('<div id=\"'+event.id+'\" class=\"hover-end\">'+$.fullCalendar.formatDate(event.end, 'h:mmt')+'</div>');
    },
    eventMouseout: function(event, jsEvent, view) {
        $('#'+event.id).remove();
    }*/
    //editable:true,
    /*eventResize:function(event)
    {
     var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
     var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
     var title = event.title;
     var id = event.id;
     $.ajax({
      url:"update.php",
      type:"POST",
      data:{title:title, start:start, end:end, id:id},
      success:function(){
       calendar.fullCalendar('refetchEvents');
       alert('Event Update');
      }
     })
    },

    eventDrop:function(event)
    {
     var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
     var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
     var title = event.title;
     var id = event.id;
     $.ajax({
      url:"update.php",
      type:"POST",
      data:{title:title, start:start, end:end, id:id},
      success:function()
      {
       calendar.fullCalendar('refetchEvents');
       alert("Event Updated");
      }
     });
    },

    eventClick:function(event)
    {
     if(confirm("Are you sure you want to remove it?"))
     {
      var id = event.id;
      $.ajax({
       url:"delete.php",
       type:"POST",
       data:{id:id},
       success:function()
       {
        calendar.fullCalendar('refetchEvents');
        alert("Event Removed");
       }
      })
     }
    },*/

   });
   /* $('.fc-event').mouseover(function() {
        $(this).addClass('fc-event-hover');
      });
      $('.fc-event').mouseleave(function() {
        $(this).removeClass('fc-event-hover');
      });*/
  });
   
  </script>
<section class="bodycont">
<div class="container">

<div class="innercont postWrap mt-3">
<div class="whirepart">
<h2 class="list_heading">Appointment List </h2>
		
		
<div style="clear:both;"></div>
<div class="panel panel-default m_top20">
<div class="panel-body">
<div class="row">
<!-- main body part -->
<ul class="available-box">
    <li><span style='background-color:#61a14d'></span> Available time.</li>
    <li><span style='background-color:#1468c4'></span> Scheduled Appointment.</li>
    <li><span style='background-color:#e4230d'></span> Time expired.</li>
</ul>
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
    
</section>
<style>
.available-box{list-style:none; margin:0 0 50px; padding:0; clear:both;}
.available-box li{ float:left; margin-right:30px;}
.available-box span{ width:15px; height:15px; display:inline-block}
</style>

@stop