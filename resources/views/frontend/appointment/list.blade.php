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
<script>

  $(document).ready(function() {
  	//params : title, start, end, id, url,
    $('#calendar').fullCalendar({
    	/*header: {
	        left: 'prev,next today',
	        center: 'title',
	        right: 'month,agendaWeek,agendaDay,listWeek'
	      },*/
      defaultDate: '{{ date('Y-m-d') }}',//'2018-05-14',
      //navLinks: true, // can click day/week names to navigate views
      editable: true,
      eventLimit: true, // allow "more" link when too many events
      events: {{ $all_data}}
    });

  });

</script>
<section class="bodycont">
<div class="container">

<div class="innercont">
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