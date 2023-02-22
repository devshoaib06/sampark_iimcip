<div class="row">
    <div class="form-group col-md-6">
        <div class="form-group">
            <input type="hidden" name="app_id" id="app_id" value="{{$data['appointmentDetails']->id}}">
            <?php 
            $subject = isset($data['appointmentDetails']->subject)?$data['appointmentDetails']->subject:$data['appointmentDetails']->subject;

            // $mentor_name = isset($data['investor_name']->individual_name)?$data['investor_name']->individual_name:'';
            // $pm_name = isset($data['pm_name']->individual_name)?$data['pm_name']->individual_name:'';
            // $startup_name = isset($data['startup_name']->name)?$data['startup_name']->name:'';


            $app_date = isset($data['appointmentDetails']->apoointment_date)?$data['appointmentDetails']->apoointment_date:'';
            $app_time = isset($data['appointmentDetails']->apoointment_time)?$data['appointmentDetails']->apoointment_time:'';

            ?>
            <b>Agenda:</b><?php echo $subject; ?><br>
            <b>Mentor:</b><?php echo $data['investor_name']; ?><br>
            <b>Portfolio Manager:</b><?php echo $data['pm_name']; ?><br>
            <b>Startup name:</b><?php echo $data['startup_name']; ?><br>
            <b>Date:</b><?php echo date('jS M Y',strtotime($app_date)); ?><br>
            <b>Time:</b><?php echo date('h:i a',strtotime($app_time)); ?>
        </div>
       
    </div>
    <div class="form-group col-md-6">
        <b>Make Note :</b><textarea rows="7" class="form-control" name="comment" id="comment"></textarea>
    </div>
</div>
<?php if(isset($data['app_note']) && count($data['app_note'])>0){ ?>
<div class="scrolldiv">
    <div class="">
        <div class="form-group">
           <b>Note :</b>
        
            <div class="note-section">
                <?php foreach($data['app_note'] as $notes){ ?>
                <div class="notes-panel">
                    <span class="note-date"><?php echo date('jS M Y h:i a',strtotime($notes['created_at'])); ?> : </span>
                    <span class="note-subject"><?php echo $notes['comment']; ?></span>
                </div>
                <?php } ?>
            </div>
        
       
        </div>
        
    </div>
</div>
 <?php } ?>
<style>
.scrolldiv {
    overflow: auto;
    height: 120px;
}
span.note-date {
    font-weight: bold;
    padding-right: 10px;
}
note-date {
    font-weight: bold;
}
</style>