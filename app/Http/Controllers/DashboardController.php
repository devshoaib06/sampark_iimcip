<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use App\Jobs\MailSendJob;
use App\Models\Users;
use App\Models\IndustryCategories;
use App\Models\Posts;
use Session;
use Auth;
use DB;
use Mail;

class DashboardController extends Controller
{

    public function login() {
    	return view('dashboard_login');
    }

    public function allSite()
    {
        return view('all_site');
    }

    public function loginAction(Request $request) {

    	$request->validate([
			
            'email_id' => 'required',
            'password' => 'required'
		],[
		
			'email_id.required' => 'Please enter email-id.',
			'password.required' => 'Please enter password.'
		]);

    	$email_id = trim($request->input('email_id'));
    	$password = md5(trim($request->input('password')));
    	$rm_me = trim($request->input('rm_me'));
    	$norPwd = trim($request->input('password'));

        $rerf_url = trim($request->input('rerf_url'));
        
        $validType = array(1, 2,4,5,6);
    	$loginUser = Users::where('email_id', '=', $email_id)
    	->where('password', '=', $password)->whereIn('user_type', $validType)->where('status', '=', '1')->first(); 

        //dd($loginUser);

    	if(!empty($loginUser)) {
    		
    		Auth::login($loginUser);
    		if( $rm_me == '1' ) {
    			setcookie("vinlec_admin_email", $email_id, time() + (86400 * 30));
                setcookie("vinlec_admin_password", $norPwd, time() + (86400 * 30));
    		} else {
    			unset($_COOKIE['vinlec_admin_email']);
                unset($_COOKIE['vinlec_admin_password']);
                setcookie("vinlec_admin_email", '', time() - 3600);
                setcookie("vinlec_admin_password", '', time() - 3600);
    		}

    		Session::put('ar_login_user_id', $loginUser->id);
            Session::put('is_ar_admin_logged_in', 'yes');
            Session::put('user_type_id', $loginUser->user_type);
             // echo "I am here"; die;
            return redirect()->route('dashboard')
            ->with('msg', 'Hi ' . $loginUser->first_name . ', Welcome to IIMCIP')
            ->with('msg_class', 'alert alert-info'); // redirect admin dashboard

    	} else {
    		return back()->with('msg', 'Sorry! Login Information Incorrect.');
    	}
    }

    public function logout() {

    	if(Session::has('ar_login_user_id')) { Session::forget('ar_login_user_id'); }
    	if(Session::has('is_ar_admin_logged_in')) { Session::forget('is_ar_admin_logged_in'); }
    	Auth::logout();
    	Session::flush();
    	return redirect()->route('dashboard_login');
    }

    public function index() 
    {

        //echo $id = Auth::user()->user_type; die;
        $DataBag = array();
        if(Auth::user()->user_type == 1){
         $DataBag['users'] = \App\Models\Users::where('status',1)->where('user_type',2)->whereNull('founder_id')->count();
         $DataBag['industryCategories'] = \App\Models\IndustryCategories::where('status', '=', '1')->count();
         $DataBag['posts'] = \App\Models\Posts::where('status', '!=', '3')->count();
     }else if(Auth::user()->user_type == 6){
        $ids_array = DB::table('member_mentor_rel')->where('mentor_id', '=', Auth::user()->id)->get()->toArray();
        $ids = array();
        foreach ($ids_array as $key => $value) {
            $ids[] = $value->member_id; 
        }
        //dd($ids);
        $DataBag['users']  = DB::table('users')->whereIn('id', $ids)
                  ->where('status', 1)
                  ->get()->count();
        }

        
    	return view('dashboard.index', $DataBag);
    }

    public function resetLink() {

        return view('pwd_reset_link');
    }

    public function sendLink(Request $request) {

        $email_id = trim( $request->input('email_id') );

        $ckEmail = Users::where('email_id', '=', $email_id)->first();
        if(!empty($ckEmail)) {
            $userFname = $ckEmail->first_name;
            $token = md5(microtime(TRUE).$email_id);
            $link = route('reset_pwd', array('token' => $token));
            $link = "<a href='".$link."' target='_blank'>". $link ."</a>";
            $empTemp = \App\Models\EmailTemplate::find(2);
            if( !empty($empTemp) ) {
                $emSubject = $empTemp->subject;
                $content = $empTemp->description;
                $content = str_replace("[FIRSTNAME]", $userFname, $content);
                $content = str_replace("[PWD_RESET_LINK]", $link, $content);
                
                // $emailData = array();
                // $emailData['subject'] = $emSubject;
                // $emailData['body'] = trim($content);
                // $emailData['to_email'] = $email_id;
                // $emailData['from_email'] = "karmicksol54@gmail.com";
                // $emailData['from_name'] = "Hola";
                // //$emailData['pdf'] = public_path('649808.pdf');

                // Mail::send('emails.accountVerification', ['emailData' => $emailData], function ($message) use ($emailData) {
                    
                //     //$message->attach($emailData['pdf'], ['as' => 'arindam.pdf', 'mime' => 'application/pdf']);

                //     $message->from($emailData['from_email'], $emailData['from_name']);

                //     $message->to($emailData['to_email'])->subject($emailData['subject']);
                // });

                $res = Users::where('email_id', '=', $email_id)->update(['remember_token' => $token]);

                $job = (new MailSendJob('emails.accountVerification', $emSubject, $email_id, trim($content)))->delay(5)->onQueue('default');
                // Async Job to send email
                dispatch($job);

                if( $res ) {
                    return back()->with('msg', 'Password Reset Link Send To Your Mail.');   
                } else {
                    return back()->with('msg', 'Mail Sending Error.');        
                }

            } else {
                return back()->with('msg', 'Mail Sending Error.');    
            }
        } else {
            return back()->with('msg', 'Sorry! Email-Id Not Registered.');
        }
    }

    public function resetPassword( $token ) {

        $usr = Users::where('remember_token', '=', $token)->first();
        if( !empty($usr) ) { 
            $dataBag = array();
            $dataBag['name'] = $usr->first_name;
            $dataBag['token'] = $token;
            return view('pwd_reset', $dataBag);
        } else {
            return redirect()->route('reset_link')->with('msg', 'Invalid Password Reset Link, Try Again');
        }
    }

    public function resetPasswordAction(Request $request, $token) {

        $res = Users::where('remember_token', '=', $token)->update( ['password' => trim($request->input('password')) ] );
        if($res) {
            return redirect()->route('dashboard_login')->with('msg', 'Password reset Successfully, Please Login.');
        } else {
            return back()->with('msg', 'Sorry! Server Error.');
        }
    }

    public function globalImageDelete() {

        if( isset($_GET['tab']) && isset($_GET['id']) && !isset($_GET['field']) && $_GET['tab'] != '' && $_GET['id'] != '' ) {

            $tab = trim($_GET['tab']);
            $id = trim($_GET['id']);

            DB::table( $tab )->where('id', '=', $id)->update(['image_id' => '0']);
        }

        if( isset($_GET['tab']) && isset($_GET['id']) && isset($_GET['field']) && $_GET['tab'] != '' && $_GET['id'] != '' && $_GET['field'] != '') {

            $tab = trim($_GET['tab']);
            $id = trim($_GET['id']);
            $field = trim($_GET['field']);

            DB::table( $tab )->where('id', '=', $id)->update([ $field => '']);
        }

        return back();
    }

public function bulkImport(Request $request) {
### import mentor ###
    $result = DB::table('users_temp')->where('status', 'Active')->where('user_type', 'pm')->get();
        //dd($result);
        foreach ($result as $key => $value_array) {
            $count = DB::table('users')->where('email_id', $value_array->user_email)->count();
            //echo $value_array->user_email; die;
             if ($count == 0) {
                 $data['timestamp_id'] = time();
                 $data['slug'] = '';
                 $data['user_type'] = 4;
                 $data['first_name'] = $value_array->individual_name;
                 $data['email_id'] = $value_array->user_email;
                 $data['contact_no'] = $value_array->phone;
                 $data['company_name'] = $value_array->name;
                 $data['password'] = $value_array->password;
                 $data['address'] = $value_array->address;
                 $data['image'] = $value_array->photo;
                 $data['linkedIn'] = $value_array->linkedin_url;
                 $data['status'] = 1;
                 DB::table('users')->insert($data);
             }
              if ($count == 1) {
                 $data['user_type'] = 4;
                 DB::table('users')->where('email_id', $value_array->user_email)->update($data);
             }

        }

       ### import mentor ###


         $result = DB::table('users_temp')->where('status', 'Active')->where('user_type', 'i')->get();
        //dd($result);
        foreach ($result as $key => $value_array) {
            $count = DB::table('users')->where('email_id', $value_array->user_email)->count();
            //echo $value_array->user_email; die;
             if ($count == 0) {
                 $data['timestamp_id'] = time();
                 $data['slug'] = '';
                 $data['user_type'] = 6;
                 $data['first_name'] = $value_array->individual_name;
                 $data['email_id'] = $value_array->user_email;
                 $data['contact_no'] = $value_array->phone;
                 $data['company_name'] = $value_array->name;
                 $data['password'] = $value_array->password;
                 $data['address'] = $value_array->address;
                 $data['image'] = $value_array->photo;
                 $data['linkedIn'] = $value_array->linkedin_url;
                 $data['status'] = 1;
                 DB::table('users')->insert($data);
             }
              if ($count == 1) {
                 $data['user_type'] = 6;
                 DB::table('users')->where('email_id', $value_array->user_email)->update($data);
             }

        }


    
    }

}
