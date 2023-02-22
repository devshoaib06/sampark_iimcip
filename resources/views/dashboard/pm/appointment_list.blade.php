@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
       All Appoinments
      </h1>
    </section>  
@endsection

@section('content')
<section class="content">

  <div class="col-sm-12">
   <div class="row">
      <div class="col-sm-12">
         <div class="panel panel-default">
            <div class="panel-heading">
               <h3 class="panel-title">List     </h3>
            </div>
            <div class="panel-body">
               <div class="table-responsive prtfliotable">
                  <div>
                     <form method="POST" action="" accept-charset="UTF-8">
                        {{ csrf_field() }}
                        <div class="form-group col-sm-2">
                           <label>Startup</label>
                           <input class="form-control" placeholder="Name/Email" name="startup" type="text" value="">
                        </div>
                        <div class="form-group col-sm-2">
                           <label>Mentors</label>
                           <input class="form-control" placeholder="Name/Email" name="investor" type="text" value="">
                        </div>
                        <div class="form-group col-sm-3">
                           <label>From Date</label>
                           <input class="form-control form_date" placeholder="From Date" data-date-format="yyyy-mm-dd" data-link-format="yyyy-mm-dd" name="from_date" type="text" value="">
                        </div>
                        <div class="form-group col-sm-3">
                           <label>To Date</label>
                           <input class="form-control form_date" placeholder="To Date" data-date-format="yyyy-mm-dd" data-link-format="yyyy-mm-dd" name="to_date" type="text" value="">
                        </div>
                        <div class="form-group col-sm-2">
                           <label></label><br>
                           <button type="submit" class="btn btn-default" id="submitButton">Search</button>
                        </div>
                     </form>
                  </div>
                  <table class="table table-bordered">
                     <thead>
                        <tr>
                           <th style="width:33%">Startups</th>
                           <!--                <th style="width:30%">PM</th>               -->
                           <th style="width:33%">Mentors</th>
                           <th style="width:34%;">Appointment</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr style="background-color: #61a14d">
               
                <td>
                    <div class="PrtflioBox">
                        <div class="PrtflioBox_image">
                  <img src="https://iimciporg.karmickdev.com/public/entreprenaur_photo/1475649305-TALENTO LOGO FINAL.jpg" width="150px" height="150px" alt="" class="dp_img">
                        </div>
                        <div class="PrtflioBox_info">
                            <div class="PrtflioBox_name"><b> Talento Consulting Private Limited</b> </div>
                            <div class="PrtflioBox_email"><b>E:</b> aaggarwal@talento.in</div>
                            <div class="PrtflioBox_contact"><b>P:</b> 9871996097 </div>
                            <div class="PrtflioBox_contact">( Not approved )</div>
                        </div>
                    </div>
                </td>
                
                <td>
                    <div class="PrtflioBox">
                           <div class="PrtflioBox_image">
                   <img src="https://iimciporg.karmickdev.com/public/investor_profile/1477468680-Vinay2010N.jpg" width="150px" height="150px" alt="" class="dp_img">
                                                    
                        </div>
                        <div class="PrtflioBox_info">
                             
                            <div class="PrtflioBox_name"><b>Vinay Sharma</b>  </div>
                            <div class="PrtflioBox_email"><b>E:</b> vin_shar2001@yahoo.co.in </div>
                            <div class="PrtflioBox_contact"><b>P:</b> 9818549487 </div>
                            <div class="PrtflioBox_contact"> ( Not approved )</div>
                           
                        </div>
                                            </div>
   
                </td>                  
                <td>
                    <div class="PrtflioBox">
                                                <div class="PrtflioBox_info">
                             
                            <div class="PrtflioBox_name"><b>fsdfsdfsdfsdf</b>  </div>
                            <div class="PrtflioBox_email"><b>Date:</b> 2021-09-30 </div>
                            <div class="PrtflioBox_contact"><b>Time:</b> 18:00 </div>
                           
                        </div>
                                            </div>
   
                </td>                  
                
            </tr>
                     </tbody>
                  </table>
               </div>
               <div class="text-center">
                  <ul class="pagination">
                  </ul>
               </div>
            </div>
         </div>
         <script>
            function change_status(id)
            {
            $("#change_status-" + id).html('<img src="https://iimciporg.karmickdev.com/public / images / fancybox_loading.gif" />');
            $.post("user_status_change", {"id": id, "_token": "RiVIffKIUy6LFhHA4sGiTySp3cvKUEQRZ0uyaZz1"}).done(function (data) {
            if (data == "Active")
            {
            opposite_data = "Make as Inactive";
            }
            else
            {
            opposite_data = "Make as Active";
            }
            
            $("#status-" + id).html(data);
            $("#change_status-" + id).html(opposite_data);
            });
            }
            
         </script>
      </div>
   </div>
  </div>

</section>
@endsection

@push('page_js')

@endpush