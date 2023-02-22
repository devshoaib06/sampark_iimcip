
<link href="{{asset('public/datepicker/css/bootstrap-datetimepicker.min.css')}}" rel='stylesheet' />
<div class="modal-body">      
    <input type="radio" name="chooseOption" value="1" checked onclick="radioControl(this)"> My Availability<br>
    <input type="radio" name="chooseOption" value="2" onclick="radioControl(this)"> Schedule Appointment<br>
</div><input type="hidden" name="available_date" id="available_date" value="{{ $data['available_date'] }}">
 <div id="my_availability">
    <form method="POST" action="{{ route('appointment.save_availability') }}" accept-charset="UTF-8" enctype="multipart/form-data">

         {{ csrf_field() }}

    <div class="modal-body">                
        <input type="hidden" name="schedule_date" id="schedule_date" value="{{ $data['available_date'] }}">
        <div class="row">
        <div class="form-group col-md-6">
            <!--<label>Available Time</label><br>-->
            From : <input class="form-control form_time" id="apoointment_time" data-date-format="hh:ii" data-link-format="hh:ii" required="required" name="apoointment_time" type="text" value="">

        </div>
        <div class="form-group col-md-6">                    
            To : <input class="form-control form_time" id="apoointment_time_end" data-date-format="hh:ii" data-link-format="hh:ii" required="required" name="apoointment_time_end" type="text" value="">
        </div>
    </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-small" data-dismiss="modal" style=" background-color: #4CAF50;">Close</button>
        <button type="submit" class="btn btn-small" onclick="save_form()" data-dismiss="modal" value="save" style=" background-color: #4CAF50;">Save</button>
    </div>            
    </form>
 </div>
<div id="schedule_appointment" style="display:none">
        <div class="modal-body">
            <form method="POST" action="{{ route('appointment.appointment_save_startup') }}" accept-charset="UTF-8" id="saveAppointment" enctype="multipart/form-data">
              {{ csrf_field() }}
         <input type="hidden" name="schedule_date" id="schedule_date" value="{{ $data['available_date'] }}">


            <div class="row">
                <input type="hidden" name="investor_id" value="101">
                <div class="form-group col-md-6">
                    Startup Name :
                    <select class="form-control" id="startup_id" name="startup_id">
                         @foreach($data['start_inv_rel'] as $member)
                                        <option value="{{$member->id}}">{{$member->member_company}}</option>
                         @endforeach 
                    </select>
                </div>
                <div class="form-group col-md-6">
                    Appointment Time : <input class="form-control form_time" id="schedule_apoointment_time" data-date-format="hh:ii" data-link-format="hh:ii" required="required" name="schedule_apoointment_time" type="text" value="">
                </div>
                <div class="form-group col-md-12">
                    Subject :
                    <textarea id="subject" name="subject" class="form-control" rows="5"></textarea>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
            <button type="button" class="btn btn-small" data-dismiss="modal" style=" background-color: #4CAF50;">Close</button>
        <button type="submit" class="btn btn-small" onclick="saveAppointment()" data-dismiss="modal" value="save" style=" background-color: #4CAF50;">Save</button>
      </div>
<script src="{{asset('public/datepicker/js/bootstrap-datetimepicker.js')}}" charset="UTF-8">
    
</script>  
<script type="text/javascript">
    $('#schedule_apoointment_time,#apoointment_time,#apoointment_time_end').timepicker({ 'scrollDefault': 'now' });
    
    function radioControl(data){//schedule_appointment, my_availability
        if(data.value==2){
            $("#my_availability").hide();
            $("#schedule_appointment").show();
        }else if(data.value==1){
            $("#my_availability").show();
            $("#schedule_appointment").hide();
        }
    }
    // $('#apoointment_time').datetimepicker({
    //     //language:  'en',
    //     //weekStart: 1,
    //     //todayBtn:  1,
    //     useCurrent: false,
    //     autoclose: 1,
    //     todayHighlight: 0,
    //     startView: 1,
    //     minView: 0,
    //     maxView: 1,
    //     forceParse: 0
    // });
    // $('#apoointment_time_end').datetimepicker({
    //     //language:  'en',
    //     //weekStart: 1,
    //     //todayBtn:  1,
    //     useCurrent: false,
    //     autoclose: 1,
    //     todayHighlight: 0,
    //     startView: 1,
    //     minView: 0,
    //     maxView: 1,
    //     forceParse: 0
    // });
    function saveAppointment(){
        //alert("submit");
        $("#saveAppointment").submit();
    }
    function save_form(){
        var apoointment_time = $('#apoointment_time').val();
        var apoointment_time_end = $('#apoointment_time_end').val();
        var available_date = $('#available_date').val();
        if(apoointment_time!='' && apoointment_time_end!=''){
            $.ajax({
                url: "{{URL::route('appointment.save_availability')}}",
                method: 'post',
                data: {
                   apoointment_time: apoointment_time,
                   apoointment_time_end: apoointment_time_end,
                   available_date: available_date,
                   "_token" : "{{ csrf_token() }}"
                },
                success: function(result){
                   window.location="{{URL::route('appointment.list')}}";
                }
            });
        }else{
            alert('Start and end time required.');
        }
    }
</script>
    