<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\AppointmentNote;
use App\Models\AppointmentAvailability;
use Session;
use Image;
use View;
use Auth;
use DB;
use Mail;
use Illuminate\Support\Str; 
use App\Models\Invitations;
use App\Models\Users;
use App\Jobs\MailSendJob;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;

class AppointmentController extends Controller {

    /**
     * Display a listing of the resource.
     * uses from admin
     * @return Response
     */

    public function index(){
        $sessionid = Session::get('userid');
	if (!isset($sessionid) && $sessionid==''){
            return Redirect::route('admin')->with('message','Please Login');
	}
        $appointment = new Appointment();
        $all_data = $appointment->getAppointment();
        return View::make('appointment.list')->with('all_data',json_encode($all_data));

    }

    public function ajax_list(){
        if(\Auth::check()){
            $appointment = new Appointment();
            $all_data = $appointment->getAppointment();
            return View::make('appointment.ajax_list')->with('all_data',json_encode($all_data));
        }else{
            return Redirect::route('home')->with('message','Please Login');
        }
    }
    
    public function ajax_data($start='', $end='', $a=''){
        if(\Auth::check()){
            $appointment = new Appointment();
            $start_day = Input::get('start');
            $end = Input::get('end');
            $all_data = $appointment->getAppointmentAjax($start,$end);
            return json_encode($all_data);
        }else{
            return Redirect::route('home')->with('message','Please Login');
        }
    }
    public function appointmentForm(){
        //echo 'hi';
        $startup_id = Input::get('startup_id');
        $portfolio_id = Input::get('portfolio_id');
        $investor_id = Input::get('investor_id');
        if(!empty($startup_id) && !empty($portfolio_id) && !empty($investor_id)){
            
            $startupDetails = Users::where('id', '=', $startup_id)->first();
            $startupname = $startupDetails['name'].'('.$startupDetails['user_email'].')';
            
            $investorDetails = Users::where('id', '=', $investor_id)->first();
            $investorname = $investorDetails['name'].'('.$investorDetails['user_email'].')';
            
            $data = array();
            $data['startupname'] = $startupname;
            $data['investorname'] = $investorname;
            $data['startup_id'] = $startup_id;
            $data['portfolio_id'] = $portfolio_id;
            $data['investor_id'] = $investor_id;
            echo View::make('admin.portfolio.appointmentForm')->with('data',$data);
        }else{
            echo 'First create relation between Startup and Invester';
        }
    }
    public function appointment_form_direct(){
        $startup_id = Input::get('startup_id');
        $portfolio_id = Input::get('portfolio_id');
        $investor_id = Input::get('investor_id');
        $available_date = Input::get('available_date');
        if(!empty($startup_id) && !empty($portfolio_id) && !empty($investor_id)){
            
            $startupDetails = Users::where('id', '=', $startup_id)->first();
            $startupname = $startupDetails['name'].'('.$startupDetails['user_email'].')';
            
            $investorDetails = Users::where('id', '=', $investor_id)->first();
            $investorname = $investorDetails['name'].'('.$investorDetails['user_email'].')';
            
            $data = array();
            $data['startupname'] = $startupname;
            $data['investorname'] = $investorname;
            $data['startup_id'] = $startup_id;
            $data['portfolio_id'] = $portfolio_id;
            $data['investor_id'] = $investor_id;
            $data['available_date'] = $available_date;
            //print_r($data);
            echo View::make('admin.portfolio.appointment_form_direct')->with('data',$data);
        }else{
            echo 'First create relation between Startup and Invester';
        }
    }
    
    
    public function saveAppointment(){
        $start_up_id = Input::get('startup_id');
        $investor_id = Input::get('investor_id');
        $created_by = Input::get('portfolio_id');
        $subject = Input::get('subject');
        $apoointment_date = Input::get('apoointment_date');
        $apoointment_time = Input::get('apoointment_time');
        //print_r($_POST);
        $appointment = new Appointment;
        $appointment->start_up_id = $start_up_id;
        $appointment->investor_id = $investor_id;
        $appointment->created_by = $created_by;
        $appointment->subject = $subject;
        $appointment->apoointment_date = $apoointment_date;
        $appointment->apoointment_time = $apoointment_time;
        $appointment->created_at = date('Y-m-d H:i:s');
        if($appointment->save()){
            $available_id = Input::get('available_id');
            if($available_id){
                $appointmentAvailability = AppointmentAvailability::find($available_id);
                $appointmentAvailability->availablity_status = '0';
                $appointmentAvailability->save();
            }
            require_once Config::get('app.base_url')."mailgun_latest/vendor/autoload.php";
            $client = new \GuzzleHttp\Client(['verify' => false,]);
            $domain = Config::get('app.mg_domain');
            $mgClient = new Mailgun\Mailgun(Config::get('app.mg_client'), new \Http\Adapter\Guzzle6\Client());
            $from=Config::get('app.from_email');
            
            $mail_temp=file_get_contents('public/mail_template/templ.php');
            $site_base_url =  Config::get('app.url').'/';
            
            $pmDetails = Users::where('id', '=', $created_by)->first();
            //$from = $pmDetails['user_email'];
            $mailSubject = "Appoinment Scheduled";
            //investor mail start
            $investorDetails = Users::where('id', '=', $investor_id)->first();
            $investorname = $investorDetails['individual_name'];
            $investor_mail = $investorDetails['user_email'];
            
            $inv_yes_link_arr = array('app_id' =>$appointment->id,'investor_id' =>$investor_id,'flag' =>'1');
            $inv_no_link_arr = array('app_id' =>$appointment->id,'investor_id' =>$investor_id,'flag' =>'0');
            $investor_yes_link = $site_base_url.'investor-appointment-approval/'.base64_encode(json_encode($inv_yes_link_arr));
            $investor_no_link = $site_base_url.'investor-appointment-approval/'.base64_encode(json_encode($inv_no_link_arr));
            
            $message="
            <table border='0'>
            <tr>
            <td>Hello ".$investorname." ,</td>
            </tr>
            <tr>
            <td>You have an new appoinment on : ".$apoointment_date." ".date('h:i a',strtotime($apoointment_time))."</td>
            </tr>
            <tr>
            <td>Subject : ".$subject."</td>
            </tr>
            <tr>
            <td>Please confirm <a href='".$investor_yes_link."'>Yes</a>&nbsp;<a href='".$investor_no_link."'>No</a></td>
            </tr>
            </table>";

            $mail_temp=str_replace("{{message}}",$message,$mail_temp);
            //echo '<pre>';
            //print_r($mail_temp);
            //echo 'Hello<br>';
            $mgClient->sendMessage($domain, array('from'=>$from, 'to'=> $investor_mail, 'subject' =>$mailSubject, 'html'=> $mail_temp));
            //investor mail end
            //startup mail start
            $startupDetails = Users::where('id', '=', $start_up_id)->first();
            $startupname = $startupDetails['name'];
            $startup_mail = $startupDetails['user_email'];
            
            $mail_temp_appointment=file_get_contents('public/mail_template/templ.php');
            $site_base_url =  Config::get('app.url').'/';
            
            $sp_yes_link_arr = array('app_id' =>$appointment->id,'start_up_id' =>$start_up_id,'flag' =>'1');
            $sp_no_link_arr = array('app_id' =>$appointment->id,'start_up_id' =>$start_up_id,'flag' =>'0');
            $startup_yes_link = $site_base_url.'startup-appointment-approval/'.base64_encode(json_encode($sp_yes_link_arr));
            $startup_no_link = $site_base_url.'startup-appointment-approval/'.base64_encode(json_encode($sp_no_link_arr));
            $message="
            <table border='0'>
            <tr>
            <td>Hello ".$startupname." ,</td>
            </tr>
            <tr>
            <td>You have an new appoinment on : ".$apoointment_date." ".date('h:i a',strtotime($apoointment_time))."</td>
            </tr>
            <tr>
            <td>Subject : ".$subject."</td>
            </tr>
            <tr>
            <td>Please confirm <a href='".$startup_yes_link."'>Yes</a>&nbsp;<a href='".$startup_no_link."'>No</a></td>
            </tr>
            </table>";

            $mail_temp_appointment=str_replace("{{message}}",$message,$mail_temp_appointment);
            //print_r($mail_temp_appointment);
            $mgClient->sendMessage($domain, array('from'=>$from, 'to'=> $startup_mail, 'subject' =>$mailSubject, 'html'=> $mail_temp_appointment));
            //startup mail end
        }
        return Redirect::route('admin.startup_lists_relation');
    }
    public function appointment_save_mentor(){
        $start_up_id = Auth::user()->id;
        $mentor_id = Input::get('investor_id');
        // echo $start_up_id.'<br>';
        // echo $mentor_id; die;
        $portfolioDetails = Users::leftJoin('member_pm_rel as rel','rel.member_id','=','users.id')
                ->select('users.id')
                ->where('rel.member_id','=',$start_up_id)
                ->first();
        //echo '<pre>';print_r($_POST); print_r($portfolioDetails); die();
        $portfolio_id = $portfolioDetails['id'];
        if(\Auth::check()){
            $currentUserId = Auth::user()->id;
         }else{
            $currentUserId = Session::get('userid');
         }
        $created_by = $currentUserId;//Session::get('userid');//Input::get('portfolio_id');
        $subject = Input::get('subject');
        $apoointment_date = Input::get('schedule_date');
        $apoointment_time = Input::get('schedule_apoointment_time'); 
        //print_r($_POST);
        $appointment = new Appointment;
        $appointment->start_up_id = $start_up_id;
        $appointment->investor_id = $mentor_id;
        $appointment->created_by = $created_by;
        $appointment->subject = $subject;
        $appointment->apoointment_date = $apoointment_date;
        $appointment->apoointment_time = $apoointment_time;
        $appointment->created_at = date('Y-m-d H:i:s');
        $appointment->save();
        // if($appointment->save()){
        //     $available_id = Input::get('available_id');
        //     if($available_id){
        //         $appointmentAvailability = AppointmentAvailability::find($available_id);
        //         $appointmentAvailability->availablity_status = '0';
        //         $appointmentAvailability->save();
        //     }
        //     // require_once Config::get('app.base_url')."mailgun_latest/vendor/autoload.php";
        //     // $client = new \GuzzleHttp\Client(['verify' => false,]);
        //     // $domain = Config::get('app.mg_domain');
        //     // $mgClient = new Mailgun\Mailgun(Config::get('app.mg_client'), new \Http\Adapter\Guzzle6\Client());
        //     // $from=Config::get('app.from_email');
            
        //     // $mail_temp=file_get_contents('public/mail_template/templ.php');
        //     // $site_base_url =  Config::get('app.url').'/';
            
        //     // $pmDetails = Users::where('id', '=', $portfolio_id)->first();
        //     // //$from = $pmDetails['user_email'];
        //     // $mailSubject = "Appoinment Scheduled";
        //     // //investor mail start
        //     // $investorDetails = Users::where('id', '=', $investor_id)->first();
        //     // $investorname = $investorDetails['individual_name'];
        //     // $investor_mail = $investorDetails['user_email'];
            
        //     // $inv_yes_link_arr = array('app_id' =>$appointment->id,'investor_id' =>$investor_id,'flag' =>'1');
        //     // $inv_no_link_arr = array('app_id' =>$appointment->id,'investor_id' =>$investor_id,'flag' =>'0');
        //     // $investor_yes_link = $site_base_url.'investor-appointment-approval/'.base64_encode(json_encode($inv_yes_link_arr));
        //     // $investor_no_link = $site_base_url.'investor-appointment-approval/'.base64_encode(json_encode($inv_no_link_arr));
            
        //     // $message="
        //     // <table border='0'>
        //     // <tr>
        //     // <td>Hello ".$investorname." ,</td>
        //     // </tr>
        //     // <tr>
        //     // <td>You have an new appoinment on : ".$apoointment_date." ".date('h:i a',strtotime($apoointment_time))."</td>
        //     // </tr>
        //     // <tr>
        //     // <td>Subject : ".$subject."</td>
        //     // </tr>
        //     // <tr>
        //     // <td>Please confirm <a href='".$investor_yes_link."'>Yes</a>&nbsp;<a href='".$investor_no_link."'>No</a></td>
        //     // </tr>
        //     // </table>";

        //     // $mail_temp=str_replace("{{message}}",$message,$mail_temp);
        //     // //echo '<pre>';
        //     // //print_r($mail_temp);
        //     // //echo 'Hello<br>';
        //     // $mgClient->sendMessage($domain, array('from'=>$from, 'to'=> $investor_mail, 'subject' =>$mailSubject, 'html'=> $mail_temp));
        //     // //investor mail end
        //     // //startup mail start
        //     // $startupDetails = Users::where('id', '=', $start_up_id)->first();
        //     // $startupname = $startupDetails['name'];
        //     // $startup_mail = $startupDetails['user_email'];
            
        //     // $mail_temp_appointment=file_get_contents('public/mail_template/templ.php');
        //     // $site_base_url =  Config::get('app.url').'/';
            
        //     // $sp_yes_link_arr = array('app_id' =>$appointment->id,'start_up_id' =>$start_up_id,'flag' =>'1');
        //     // $sp_no_link_arr = array('app_id' =>$appointment->id,'start_up_id' =>$start_up_id,'flag' =>'0');
        //     // $startup_yes_link = $site_base_url.'startup-appointment-approval/'.base64_encode(json_encode($sp_yes_link_arr));
        //     // $startup_no_link = $site_base_url.'startup-appointment-approval/'.base64_encode(json_encode($sp_no_link_arr));
        //     // $message="
        //     // <table border='0'>
        //     // <tr>
        //     // <td>Hello ".$startupname." ,</td>
        //     // </tr>
        //     // <tr>
        //     // <td>You have an new appoinment on : ".$apoointment_date." ".date('h:i a',strtotime($apoointment_time))."</td>
        //     // </tr>
        //     // <tr>
        //     // <td>Subject : ".$subject."</td>
        //     // </tr>
        //     // <tr>
        //     // <td>Please confirm <a href='".$startup_yes_link."'>Yes</a>&nbsp;<a href='".$startup_no_link."'>No</a></td>
        //     // </tr>
        //     // </table>";

        //     // $mail_temp_appointment=str_replace("{{message}}",$message,$mail_temp_appointment);
        //     // //print_r($mail_temp_appointment);
        //     // $mgClient->sendMessage($domain, array('from'=>$from, 'to'=> $startup_mail, 'subject' =>$mailSubject, 'html'=> $mail_temp_appointment));
            
        //     //startup mail end
        // }
        return Redirect::route('appointment.list');
    }
     public function appointment_save_startup(){ 
        $start_up_id = Input::get('startup_id');
        $mentor_id = Auth::user()->id;
        // echo $start_up_id.'<br>';
        // echo $mentor_id; die;
        $portfolioDetails = Users::leftJoin('member_pm_rel as rel','rel.member_id','=','users.id')
                ->select('users.id')
                ->where('rel.member_id','=',$start_up_id)
                ->first();
        //echo '<pre>';print_r($_POST); print_r($portfolioDetails); die();
        $portfolio_id = $portfolioDetails['id'];
        if(\Auth::check()){
            $currentUserId = Auth::user()->id;
         }else{
            $currentUserId = Session::get('userid');
         }
        $created_by = $currentUserId;//Session::get('userid');//Input::get('portfolio_id');
        $subject = Input::get('subject');
        $apoointment_date = Input::get('schedule_date');
        $apoointment_time = Input::get('schedule_apoointment_time'); 
        //print_r($_POST);
        $appointment = new Appointment;
        $appointment->start_up_id = $start_up_id;
        $appointment->investor_id = $mentor_id;
        $appointment->created_by = $created_by;
        $appointment->subject = $subject;
        $appointment->apoointment_date = $apoointment_date;
        $appointment->apoointment_time = $apoointment_time;
        $appointment->created_at = date('Y-m-d H:i:s');
        $appointment->save();
        // if($appointment->save()){
        //     $available_id = Input::get('available_id');
        //     if($available_id){
        //         $appointmentAvailability = AppointmentAvailability::find($available_id);
        //         $appointmentAvailability->availablity_status = '0';
        //         $appointmentAvailability->save();
        //     }
        //     // require_once Config::get('app.base_url')."mailgun_latest/vendor/autoload.php";
        //     // $client = new \GuzzleHttp\Client(['verify' => false,]);
        //     // $domain = Config::get('app.mg_domain');
        //     // $mgClient = new Mailgun\Mailgun(Config::get('app.mg_client'), new \Http\Adapter\Guzzle6\Client());
        //     // $from=Config::get('app.from_email');
            
        //     // $mail_temp=file_get_contents('public/mail_template/templ.php');
        //     // $site_base_url =  Config::get('app.url').'/';
            
        //     // $pmDetails = Users::where('id', '=', $portfolio_id)->first();
        //     // //$from = $pmDetails['user_email'];
        //     // $mailSubject = "Appoinment Scheduled";
        //     // //investor mail start
        //     // $investorDetails = Users::where('id', '=', $investor_id)->first();
        //     // $investorname = $investorDetails['individual_name'];
        //     // $investor_mail = $investorDetails['user_email'];
            
        //     // $inv_yes_link_arr = array('app_id' =>$appointment->id,'investor_id' =>$investor_id,'flag' =>'1');
        //     // $inv_no_link_arr = array('app_id' =>$appointment->id,'investor_id' =>$investor_id,'flag' =>'0');
        //     // $investor_yes_link = $site_base_url.'investor-appointment-approval/'.base64_encode(json_encode($inv_yes_link_arr));
        //     // $investor_no_link = $site_base_url.'investor-appointment-approval/'.base64_encode(json_encode($inv_no_link_arr));
            
        //     // $message="
        //     // <table border='0'>
        //     // <tr>
        //     // <td>Hello ".$investorname." ,</td>
        //     // </tr>
        //     // <tr>
        //     // <td>You have an new appoinment on : ".$apoointment_date." ".date('h:i a',strtotime($apoointment_time))."</td>
        //     // </tr>
        //     // <tr>
        //     // <td>Subject : ".$subject."</td>
        //     // </tr>
        //     // <tr>
        //     // <td>Please confirm <a href='".$investor_yes_link."'>Yes</a>&nbsp;<a href='".$investor_no_link."'>No</a></td>
        //     // </tr>
        //     // </table>";

        //     // $mail_temp=str_replace("{{message}}",$message,$mail_temp);
        //     // //echo '<pre>';
        //     // //print_r($mail_temp);
        //     // //echo 'Hello<br>';
        //     // $mgClient->sendMessage($domain, array('from'=>$from, 'to'=> $investor_mail, 'subject' =>$mailSubject, 'html'=> $mail_temp));
        //     // //investor mail end
        //     // //startup mail start
        //     // $startupDetails = Users::where('id', '=', $start_up_id)->first();
        //     // $startupname = $startupDetails['name'];
        //     // $startup_mail = $startupDetails['user_email'];
            
        //     // $mail_temp_appointment=file_get_contents('public/mail_template/templ.php');
        //     // $site_base_url =  Config::get('app.url').'/';
            
        //     // $sp_yes_link_arr = array('app_id' =>$appointment->id,'start_up_id' =>$start_up_id,'flag' =>'1');
        //     // $sp_no_link_arr = array('app_id' =>$appointment->id,'start_up_id' =>$start_up_id,'flag' =>'0');
        //     // $startup_yes_link = $site_base_url.'startup-appointment-approval/'.base64_encode(json_encode($sp_yes_link_arr));
        //     // $startup_no_link = $site_base_url.'startup-appointment-approval/'.base64_encode(json_encode($sp_no_link_arr));
        //     // $message="
        //     // <table border='0'>
        //     // <tr>
        //     // <td>Hello ".$startupname." ,</td>
        //     // </tr>
        //     // <tr>
        //     // <td>You have an new appoinment on : ".$apoointment_date." ".date('h:i a',strtotime($apoointment_time))."</td>
        //     // </tr>
        //     // <tr>
        //     // <td>Subject : ".$subject."</td>
        //     // </tr>
        //     // <tr>
        //     // <td>Please confirm <a href='".$startup_yes_link."'>Yes</a>&nbsp;<a href='".$startup_no_link."'>No</a></td>
        //     // </tr>
        //     // </table>";

        //     // $mail_temp_appointment=str_replace("{{message}}",$message,$mail_temp_appointment);
        //     // //print_r($mail_temp_appointment);
        //     // $mgClient->sendMessage($domain, array('from'=>$from, 'to'=> $startup_mail, 'subject' =>$mailSubject, 'html'=> $mail_temp_appointment));
            
        //     //startup mail end
        // }
        return Redirect::route('appointment.list');
    }
    public function appointment_save_direct(){
        #dd($_POST);
        $start_up_id = Input::get('startup_id');
        $investor_id = Input::get('investor_id');
        $created_by = Input::get('portfolio_id');
        $subject = Input::get('subject');
        $apoointment_date = Input::get('apoointment_date');
        $apoointment_time = Input::get('apoointment_time');
        //print_r($_POST);
        $appointment = new Appointment;
        $appointment->start_up_id = $start_up_id;
        $appointment->investor_id = $investor_id;
        $appointment->created_by = $created_by;
        $appointment->subject = $subject;
        $appointment->apoointment_date = $apoointment_date;
        $appointment->apoointment_time = $apoointment_time;
        $appointment->created_at = date('Y-m-d H:i:s');
        if($appointment->save()){
            $available_id = Input::get('available_id');
            if($available_id){
                $appointmentAvailability = AppointmentAvailability::find($available_id);
                $appointmentAvailability->availablity_status = '0';
                $appointmentAvailability->save();
            }
            require_once Config::get('app.base_url')."mailgun_latest/vendor/autoload.php";
            $client = new \GuzzleHttp\Client(['verify' => false,]);
            $domain = Config::get('app.mg_domain');
            $mgClient = new Mailgun\Mailgun(Config::get('app.mg_client'), new \Http\Adapter\Guzzle6\Client());
            $from=Config::get('app.from_email');
            
            $mail_temp=file_get_contents('public/mail_template/templ.php');
            $site_base_url =  Config::get('app.url').'/';
            
            $pmDetails = Users::where('id', '=', $created_by)->first();
            //$from = $pmDetails['user_email'];
            $mailSubject = "Appoinment Scheduled";
            //investor mail start
            $investorDetails = Users::where('id', '=', $investor_id)->first();
            $investorname = $investorDetails['individual_name'];
            $investor_mail = $investorDetails['user_email'];
            
            $inv_yes_link_arr = array('app_id' =>$appointment->id,'investor_id' =>$investor_id,'flag' =>'1');
            $inv_no_link_arr = array('app_id' =>$appointment->id,'investor_id' =>$investor_id,'flag' =>'0');
            $investor_yes_link = $site_base_url.'investor-appointment-approval/'.base64_encode(json_encode($inv_yes_link_arr));
            $investor_no_link = $site_base_url.'investor-appointment-approval/'.base64_encode(json_encode($inv_no_link_arr));
            
            $message="
            <table border='0'>
            <tr>
            <td>Hello ".$investorname." ,</td>
            </tr>
            <tr>
            <td>You have an new appoinment on : ".$apoointment_date." ".date('h:i a',strtotime($apoointment_time))."</td>
            </tr>
            <tr>
            <td>Subject : ".$subject."</td>
            </tr>
            <tr>
            <td>Please confirm <a href='".$investor_yes_link."'>Yes</a>&nbsp;<a href='".$investor_no_link."'>No</a></td>
            </tr>
            </table>";

            $mail_temp=str_replace("{{message}}",$message,$mail_temp);
            //echo '<pre>';
            //print_r($mail_temp);
            //echo 'Hello<br>';
            $mgClient->sendMessage($domain, array('from'=>$from, 'to'=> $investor_mail, 'subject' =>$mailSubject, 'html'=> $mail_temp));
            //investor mail end
            //startup mail start
            $startupDetails = Users::where('id', '=', $start_up_id)->first();
            $startupname = $startupDetails['name'];
            $startup_mail = $startupDetails['user_email'];
            
            $mail_temp_appointment=file_get_contents('public/mail_template/templ.php');
            $site_base_url =  Config::get('app.url').'/';
            
            $sp_yes_link_arr = array('app_id' =>$appointment->id,'start_up_id' =>$start_up_id,'flag' =>'1');
            $sp_no_link_arr = array('app_id' =>$appointment->id,'start_up_id' =>$start_up_id,'flag' =>'0');
            $startup_yes_link = $site_base_url.'startup-appointment-approval/'.base64_encode(json_encode($sp_yes_link_arr));
            $startup_no_link = $site_base_url.'startup-appointment-approval/'.base64_encode(json_encode($sp_no_link_arr));
            $message="
            <table border='0'>
            <tr>
            <td>Hello ".$startupname." ,</td>
            </tr>
            <tr>
            <td>You have an new appoinment on : ".$apoointment_date." ".date('h:i a',strtotime($apoointment_time))."</td>
            </tr>
            <tr>
            <td>Subject : ".$subject."</td>
            </tr>
            <tr>
            <td>Please confirm <a href='".$startup_yes_link."'>Yes</a>&nbsp;<a href='".$startup_no_link."'>No</a></td>
            </tr>
            </table>";

            $mail_temp_appointment=str_replace("{{message}}",$message,$mail_temp_appointment);
            //print_r($mail_temp_appointment);
            $mgClient->sendMessage($domain, array('from'=>$from, 'to'=> $startup_mail, 'subject' =>$mailSubject, 'html'=> $mail_temp_appointment));
            //startup mail end
        }
        //return Redirect::route(('admin/appointment_schedular/'.base64_encode($start_up_id).'/'.base64_encode($investor_id)));
        return Redirect::route('admin.appointment_list');
    }
    
    public function investorAppointmentApproval($link){
        $linkArr = json_decode(base64_decode($link));
        if(!empty($linkArr->investor_id)){
            $appointment = Appointment::find($linkArr->app_id);
            $appointment->investor_approval = $linkArr->flag;
            $appointment->save();
        }
        return Redirect::route('home');
    }

    public function appointment_list(){
      $sessionid = Auth::user()->id;;
     if (!isset($sessionid) && $sessionid=='')
      {
        return Redirect::route('admin')->with('message','Please Login');
      }
       $userID = Auth::user()->id; 


       // $startup_key=isset($_POST['startup'])? $_POST['startup']:''; //um2
       // $investor_key=isset($_POST['investor'])? $_POST['investor']:''; //um3
       // $from_date=isset($_POST['from_date'])? $_POST['from_date']:'';        
       // $to_date=isset($_POST['to_date'])? $_POST['to_date']:''; 
       
       
       //  $td = date('Y-m-d ');
       //  $all_entrepreneurs_relation = Appointment::leftJoin('users','users.id','=','appointment.created_by')
       //          ->leftJoin('start_up_pm_rel','start_up_pm_rel.start_up_id','=','users.id')
       //          ->leftJoin('users as u2','appointment.start_up_id','=','u2.id')
       //          ->leftJoin('users as u3','appointment.investor_id','=','u3.id')
       //          ->select('users.user_email', 'users.individual_name',
       //                  DB::raw("if(appointment.apoointment_date = '".$td."', 1,if(appointment.apoointment_date > '".$td."', 2,3)) as custom_order"),
       //                  'users.photo', 'users.phone','u2.name as pro_name','u2.user_email as pro_email','u2.phone as pro_phone','u2.photo as pro_photo','u3.individual_name as inv_name','u3.user_email as inv_email','u3.phone as inv_phone','u3.photo as inv_photo','u3.id as inv_id','u2.id as pro_id','users.id','appointment.subject','appointment.apoointment_date','appointment.apoointment_time','appointment.start_up_approval','appointment.investor_approval');
                
                
       //          if(isset($startup_key) && $startup_key!=''){
       //              $all_entrepreneurs_relation->where(function($qr)use($startup_key){
       //                  $qr->where('u2.name', 'LIKE',"%{$startup_key}%");
       //                  $qr->orWhere('u2.user_email', 'LIKE',"%{$startup_key}%");
       //              });                      
       //          }
       //          if(isset($investor_key) && $investor_key!=''){
       //              $all_entrepreneurs_relation->where(function($qrs)use($investor_key){
       //                  $qrs->where('u3.individual_name', 'LIKE',"%{$investor_key}%");
       //                  $qrs->orWhere('u3.user_email', 'LIKE',"%{$investor_key}%");
       //              });                      
       //          }
       //          if((isset($from_date) && $from_date!='') || (isset($to_date) && $to_date!='')){
       //               if((isset($from_date) && $from_date!='') && (isset($to_date) && $to_date!='')){
       //                  $all_entrepreneurs_relation->where(function($qrs)use($from_date,$to_date){
       //                      $qrs->whereBetween('appointment.apoointment_date', array($from_date, $to_date));
       //                  });
       //               }elseif(isset($from_date) && $from_date!=''){
       //                  $all_entrepreneurs_relation->where(function($qrs)use($from_date){
       //                      $qrs->where('appointment.apoointment_date', '>=',"{$from_date}");
       //                  }); 
       //               }elseif(isset($to_date) && $to_date!=''){
       //                   $all_entrepreneurs_relation->where(function($qrs)use($to_date){
       //                      $qrs->where('appointment.apoointment_date', '<=',"{$to_date}");
       //                  }); 
       //               }
                                         
       //          }
                 
       //          $userType = Session::get('user_type');
       //          if($userType=='a'){
                    
       //          }else{
       //              $all_entrepreneurs_relation=$all_entrepreneurs_relation->where('appointment.created_by',$userID);
       //          }
       //      $all_entrepreneurs_relation=$all_entrepreneurs_relation->orderBy('custom_order','asc')
       //              ->orderBy('appointment.apoointment_date','asc')
       //              ->orderBy('appointment.apoointment_time','asc')
       //              ->paginate(15);
            
       //    $porfolio_details = User::where('user_type', 'pm')->where('status','Active')->get();
       //  $data = [
       //      'all_entrepreneurs' => $all_entrepreneurs_relation,
       //      'startup_key' =>$startup_key,
       //      'investor_key' =>$investor_key,
       //      'from_date' =>$from_date,
       //      'to_date' =>$to_date,
       //      'porfolio_details'=>$porfolio_details
            
       //  ];

        $data = array();

    return $returnHTML = View::make('dashboard.pm.appointment_list')->with($data);
}

    public function startupAppointmentApproval($link){
        $linkArr = json_decode(base64_decode($link));
        if(!empty($linkArr->start_up_id)){
            $appointment = Appointment::find($linkArr->app_id);
            $appointment->start_up_approval = $linkArr->flag;
            $appointment->save();
        }
        return Redirect::route('home');
    }
    
    public function ajax_list_new(){
        if(\Auth::check()){
            $appointment = new Appointment();
            $all_data = $appointment->getAppointment();
            return View::make('frontend.appointment.ajax_list_new')->with('all_data',json_encode($all_data));
        }else{
            return Redirect::route('home')->with('message','Please Login');
        }
    }

    public function ajax_data_new($start='', $end='', $a=''){
        if(\Auth::check()){
            $appointment = new Appointment();
            $start = Input::get('start');
            $end = Input::get('end');
            $all_data = array();
            $all_data = $appointment->getAppointmentAjax($start,$end);
           // $available_appointment = $appointment->getAvailabilityAjax($start,$end);;
           // $marged_data = array_merge($all_data,$available_appointment);
            //$marged_data = array();
            //dd(count($marged_data));
            return json_encode($all_data);
        }else{
            return Redirect::route('home')->with('message','Please Login');
        }
    }
    public function appointmentFormMentor(){
        //echo 'hi';
        $available_date = Input::get('available_date');
         if(\Auth::check()){
            $mentor_id = Auth::user()->id;
         }else{
            $mentor_id = Session::get('userid');
         }
//        $start_inv_rel = StartUpInvestorRel::select('start_up_id')->where('investor_id','=',$mentor_id)->get()->toArray();
        $start_inv_rel = Users::leftJoin('member_mentor_rel as rel','rel.member_id','=','users.id')
                ->select('users.id','users.member_company')
                ->where('rel.mentor_id','=',$mentor_id)
                ->get();
        if(!empty($available_date)){            
            $data = array();
            $data['start_inv_rel'] = $start_inv_rel;
            $data['available_date'] = $available_date;
            echo View::make('frontend.appointment.appointmentFormMentor')->with('data',$data);
        }else{
            echo 'Click date from this month only';
        }
    }
    public function appointmentFormStartup(){
        //echo 'hi';
        $available_date = Input::get('available_date');
         if(\Auth::check()){
            $startup_id = Auth::user()->id;
         }else{
            $startup_id = Session::get('userid');
         }
//        $start_inv_rel = StartUpInvestorRel::select('start_up_id')->where('investor_id','=',$mentor_id)->get()->toArray();
        $start_inv_rel = Users::leftJoin('member_mentor_rel as rel','rel.mentor_id','=','users.id')
                ->select('users.id','users.first_name')
                ->where('rel.member_id','=',$startup_id)
                ->get();
        if(!empty($available_date)){            
            $data = array();
            $data['start_inv_rel'] = $start_inv_rel;
            $data['available_date'] = $available_date;

            //dd($data);
            echo View::make('frontend.appointment.appointmentFormStartup')->with('data',$data);
        }else{
            echo 'Click date from this month only';
        }
    }
    public function saveAvailability(){
        if(\Auth::check()){
            $available_date = Input::get('available_date');
            $apoointment_time = Input::get('apoointment_time');
            $apoointment_time_end = Input::get('apoointment_time_end');
            $user_id = Auth::user()->id;
            $available_date = strtr($available_date, '/', '-');
            $org_date = date('Y-m-d', strtotime($available_date));
            if(!empty($apoointment_time) && !empty($apoointment_time_end) && !empty($org_date)){
                $appointmentAvailability = new AppointmentAvailability;
                $appointmentAvailability->created_by = $user_id;
                $appointmentAvailability->availability_date = $org_date;
                $appointmentAvailability->avalibility_time_start = $apoointment_time;
                $appointmentAvailability->avalibility_time_end = $apoointment_time_end;
                $appointmentAvailability->created_at = date('Y-m-d h:i:s');
                $appointmentAvailability->save();
            }
            
            return Redirect::route('appointment.list');
        }else{
            return Redirect::route('home')->with('message','Please Login');
        }
    }
    public function saveEditedAvailability(){
        if(\Auth::check()){
            $id = Input::get('id');
            //dd($id);
            $apoointment_time = Input::get('apoointment_time');
            $apoointment_time_end = Input::get('apoointment_time_end');
            
            if(!empty($apoointment_time) && !empty($apoointment_time_end)){
                $appointmentAvailability = AppointmentAvailability::find($id);
                $appointmentAvailability->avalibility_time_start = $apoointment_time;
                $appointmentAvailability->avalibility_time_end = $apoointment_time_end;
                $appointmentAvailability->updated_at = date('Y-m-d h:i:s');
                echo $appointmentAvailability->save();
            }
            
            return 1;// Redirect::route('appointment.list');
        }else{
            return Redirect::route('home')->with('message','Please Login');
        }
    }
    
    public function delete_availability(){
        $id = Input::get('id');
        $appointmentAvailability = AppointmentAvailability::find($id);
        $appointmentAvailability->delete();
        return Redirect::route('appointment.list');
    }
    public function confirm_availability(){
        $startup_id = Auth::user()->id;
        $app_avail_id = Input::get('id');
        $prev_record = AppointmentAvailabilityRelation::where('startup_id','=',$startup_id)->where('app_avail_id','=',$app_avail_id)->get();
        if(count($prev_record)>0){
            AppointmentAvailabilityRelation::where('startup_id','=',$startup_id)->where('app_avail_id','=',$app_avail_id)->delete();
        }
        $appointmentAvailability = new AppointmentAvailabilityRelation;
        $appointmentAvailability->startup_id =  $startup_id;
        $appointmentAvailability->app_avail_id = $app_avail_id;
        $appointmentAvailability->created_at = date('Y-m-d h:i:s');
        $appointmentAvailability->save();
        
        return Redirect::route('appointment.list');
    }

    public function getAppointmentModal(){
        //echo 'hi';
        $id = Input::get('id');
        $AppData = AppointmentAvailability::where('id','=',$id)->first();
        if(\Auth::check()){
            $mentor_id = Auth::user()->id;
         }else{
            $mentor_id = Session::get('userid');
         }
//        $start_inv_rel = StartUpInvestorRel::select('start_up_id')->where('investor_id','=',$mentor_id)->get()->toArray();
        $start_inv_rel = User::leftJoin('start_up_investor_rel as rel','rel.start_up_id','=','users.id')
                ->select('users.id','users.name')
                ->where('rel.investor_id','=',$mentor_id)
                ->get();
        if(!empty($AppData)){
            $data = array();
            $data['start_inv_rel'] = $start_inv_rel;
            $data['appData'] = $AppData;
            echo View::make('admin.portfolio.getAppointmentModal')->with('data',$data);
        }else{
            echo 'Some error found';
        }
    }
    
    public function view_appointment(){
        $appointment_id = Input::get('appointment_id');
        $appointmentDetails = Appointment::where('id', '=', $appointment_id)->first();
        
       // print_r($appointmentDetails); 
        //die;
        $modalHtml = "No appointment found.";
        $data = array();
           $investor_name = Users::where('id', '=', $appointmentDetails->investor_id)->first(); 

          
           

            $startup_name = Users::where('id', '=', $appointmentDetails->start_up_id)->first();
            if(\Auth::check()){
                $user_id = Auth::user()->id;
             }else{
                $user_id = Session::get('userid');
             }
            $app_note = AppointmentNote::where('appointment_id', '=', $appointment_id)->where('added_by','=',$user_id)->orderBy('id','desc')->get();
            
        $pm_name = Users::leftJoin('member_pm_rel as rel','rel.member_id','=','users.id')
                ->leftJoin('users as user2','user2.id','=','rel.pm_id')
                ->select('user2.first_name')
                ->where('rel.member_id','=',$appointmentDetails->start_up_id)
                ->first();
        
        
        $data['appointmentDetails'] = $appointmentDetails;
        $data['pm_name'] = $pm_name->first_name;
        $data['startup_name'] = $startup_name->member_company;
        $data['investor_name'] = $investor_name->first_name;
        $data['app_note'] = $app_note;

        //dd($data);
        
    
        
        //echo $modalHtml;
        echo View::make('frontend.appointment.viewAppointment')->with('data',$data);
    }
    public function savenote(){
        $app_id = Input::get('app_id');
        $comment = Input::get('comment');
        if(\Auth::check()){
            $user_id = Auth::user()->id;
         }else{
            $user_id = Session::get('userid');
         }
         if(!empty($app_id) && !empty($comment) && !empty($user_id)){
            $appointmentNote = new AppointmentNote;
            $appointmentNote->added_by = $user_id;
            $appointmentNote->comment = $comment;
            $appointmentNote->appointment_id = $app_id;
            $appointmentNote->created_at = date('Y-m-d H:i:s');
            if($appointmentNote->save()){
                echo 'Note saved.';
            }else{
                echo 'Not saved';
            }
         }else{
             echo 'Not saved';
         }
    }
}
