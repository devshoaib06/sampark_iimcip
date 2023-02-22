
<link href="{{asset('public/datepicker/css/bootstrap-datetimepicker.min.css')}}" rel='stylesheet' />

<input type="hidden" name="available_date" id="available_date" value="{{ $data['available_date'] }}">
 
<div id="schedule_appointment" >
        <div class="modal-body">
            <?php //print_r($data['start_inv_rel']);?> 
            <form method="POST" action="{{ route('appointment.appointment_save_mentor') }}" accept-charset="UTF-8" id="saveAppointment" enctype="multipart/form-data">
                {{ csrf_field() }}
            <input type="hidden" name="schedule_date" id="schedule_date" value="{{ $data['available_date'] }}">
            <div class="row">
                <input type="hidden" name="startup_id" value="">
                <div class="form-group col-md-6">
                    Mentor Name :
                    <select class="form-control" id="investor_id" name="investor_id">
                                     @foreach($data['start_inv_rel'] as $member)
                                        <option value="{{$member->id}}">{{$member->first_name}}</option>
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
        <button type="submit" class="btn btn-smallbtn btn-small" onclick="saveAppointment()" data-dismiss="modal" value="save" style=" background-color: #4CAF50;">Save</button>
      </div>
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.css" rel='stylesheet' />
  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.js" charset="UTF-8"></script> 
<script type="text/javascript">
    
    

    $('#schedule_apoointment_time').timepicker({ 'scrollDefault': 'now' });
    function saveAppointment(){
        //alert("submit form");
        $("#saveAppointment").submit();
    }
    
</script>
    