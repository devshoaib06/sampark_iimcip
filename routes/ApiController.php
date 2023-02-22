<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Users;
use App\Models\Stages;
use App\Models\IndustryCategories;
use App\Models\EmailTemplate;
use App\Models\MemberBusiness;
use App\Models\PostIndustry;
use App\Models\Posts;
use App\Models\PostReply;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Image;
use Auth;
use DB;
use Session;
use PHPMailer\PHPMailer\PHPMailer;
use Carbon\Carbon;
use Validator;
use Mail;
//use File;

class ApiController extends Controller {

    public function __construct() {
        $lang = Session::get('variableLocale');
        if ($lang != null) {
            Session::put('variableLocale', 'en');
            \App::setLocale($lang);
        }
    }
	
	public function getIndustryCategory(Request $request) {

        $records = array();

        $result = IndustryCategories::select('id','industry_category')
                        ->where('status', '=', '1')
                        ->orderBy('industry_category', 'asc')->get();

        $records["details"] = $result;
        $records["message"] = "successful";
        $records["success"] = true;
        $records["success_bool"] = 1;

        echo json_encode($records);
    }



    public function getAllPosts(Request $request) {
        $records = array();
        $user= array();
        $timestamp_id = $request->input('timestamp_id');
        if(!empty($timestamp_id)){
        $industry_type = $request->input('industry_type');
        $user = Users::where('timestamp_id', '=', $timestamp_id)->first();
        $memberBusiness = MemberBusiness::where('member_id', '=', $user->id)->pluck('industry_category_id')->toArray();
        //$memberBusinessStr = implode(',', $memberBusiness);
        //echo $memberBusinessStr; exit;
        #dd($memberBusiness);
        $result = array();
        if(!empty($user->id)){
            $uid= $user->id;
            //echo $user->id; exit;
                   if($industry_type == '0'){


        		    $result = DB::table('post_master')
                            ->select('post_master.*')
                            ->join('post_industry', 'post_master.id', '=', 'post_industry.post_id')
                        ->where(function ($query)  use($uid) {
                        $query->where('post_master.private_member_id', '=', $uid)
                            ->where('post_master.post_type', '=', '2')
                            ->where('post_master.status', '=', '1');
                        })->orWhere(function($query){
                            $query->where('post_master.status', '=', '1')
                                ->where('post_master.post_type', '=', '1');   
                        })
                        ->groupBy('post_master.id','post_master.post_title','post_master.post_info','post_master.member_id','post_master.post_type','post_master.private_member_id','post_master.status','post_master.created_at','post_master.updated_at')
                        ->orderBy('created_at', 'dasc')->get();
                    }else{
                        $result = DB::table('post_master')
                            ->select('post_master.*')
                             ->join('post_industry', 'post_master.id', '=', 'post_industry.post_id')
                        ->where(function ($query)  use($uid,$memberBusiness) {
                        $query->where('post_master.private_member_id', '=', $uid)
                            ->whereIn('post_industry.post_id', $memberBusiness)
                            ->where('post_master.post_type', '=', '2')
                            ->where('post_master.status', '=', '1');
                        })->orWhere(function($query){
                            $query->where('post_master.status', '=', '1')
                                ->where('post_master.post_type', '=', '1');   
                        })
                         ->groupBy('post_master.id','post_master.post_title','post_master.post_info','post_master.member_id','post_master.post_type','post_master.private_member_id','post_master.status','post_master.created_at','post_master.updated_at')
                        ->orderBy('post_master.created_at', 'dasc')->get();
                    }
        
                //dd($result);

        if(!empty($result)){
        	$i=0;
        	foreach($result as $r){
        		$result[$i]->post = html_entity_decode($r->post_info);

        		$postReply = PostReply::where('post_id', '=', $r->id)->count();
        		$userCom = Users::where('id', '=', $r->member_id)->first();
        		$result[$i]->replyCount = $postReply;
        		$result[$i]->member_company = $userCom->member_company;

        		// echo  $r->member_id."____".$userCom->stage_id;

        		// exit;
        		$stageArr = Stages::where('id', '=', $userCom->stage_id)->first();
                if(!empty($stageArr)){
        		$result[$i]->stage = $stageArr->stage_name;
                }else{
                  $result[$i]->stage = '';  
                }




        		$result[$i]->posted_user_image = 'public/uploads/user_images/original/'.$userCom->image;
        		$result[$i]->posted_by = $userCom->contact_name;
        		$result[$i]->posted_at = date('d M, Y', strtotime($r->created_at));
        		//$result[$i]->posted_at = str_replace('-', '/', $result[$i]->posted_at);
        		if($r->post_type =='1'){
        			$result[$i]->post_type = 'Public';
        			$result[$i]->assign_to = "";
        		}else{
        			$result[$i]->post_type = 'Private';
        			$userAssign = array();
        			$userAssign = Users::where('id', '=', $r->private_member_id)->first();


        			$result[$i]->assign_to = $userAssign->contact_name;
        		}





        		$result[$i]->memberBusiness= DB::table('member_business')
				->select('industry_category_id', 'industry_category','industry_category.id')
				 ->join('industry_category', 'member_business.industry_category_id', '=', 'industry_category.id')
				 ->where("industry_category.status", "=", 1)
				 ->where("member_id", "=", $userCom->id)->get();


        		$i++;
        	}
        }            
        //dd($result);
        $records["details"] = $result;
        $records["message"] = "successful";
        $records["success"] = true;
        $records["success_bool"] = 1;

        echo json_encode($records);
    }else{
            $records["details"] = array();
            $records["message"] = "User id wrong";
            $records["success"] = false;
            $records["success_bool"] = 1;

            echo json_encode($records);
    }
}else{
            $records["details"] = array();
            $records["message"] = "User id require";
            $records["success"] = false;
            $records["success_bool"] = 1;

            echo json_encode($records);
}

        
    }


    public function getPostDetl(Request $request) {

        $records = array();

        $timestamp_id = $request->input('timestamp_id');
        $post_id = $request->input('post_id'); 

        if(!empty($post_id)){
        if(!empty($timestamp_id)){
        $user = Users::where('timestamp_id', '=', $timestamp_id)->first();
    	}
        $result = Posts::select('*')
                        ->where('status', '=', '1')
                        ->where('id', '=', $post_id)->first();
                        
        $postReply = PostReply::where('post_id', '=', $post_id)->count();


        $userCom = Users::where('id', '=', $result->member_id)->first();

        //dd($userCom);
        // echo $userCom->contact_name; 
        // exit;
        $result->replyCount = $postReply;
        $result->post = html_entity_decode($result->post_info);
        $result->member_company = $userCom->member_company;
        $result->posted_user_image = 'public/uploads/user_images/original/'.$userCom->image;
        $result->posted_by = $userCom->contact_name;
        $result->posted_at = date('d M, Y', strtotime($result->created_at));
        //$result->posted_at = str_replace('-', '/', $result->posted_at);
        if($result->post_type =='1'){
        	$result->post_type = 'Public';
        	$result->assign_to = "";
        }else{
        	$result->post_type = 'Private';
        	$userAssign = array();
        	$userAssign = Users::where('id', '=', $result->private_member_id)->first();
            //dd($userAssign);
            if(!empty($userAssign->contact_name)){
        	$result->assign_to = $userAssign->contact_name;
                }else{
                   $result->assign_to = ""; 
                }
        	}

        
        // $result = PostReply::select('*')
        //            ->where('status', '=', '1')
        //            ->where('post_id', '=', $post_id)
        //            ->orderBy('reply_text', 'asc')->get();
       
                 
        // $result->company=$user->member_company;
        // $result->replied_at=date('d/m/Y h:i', strtotime($user->member_company));
        $result->replyCnt=$postReply;
        //dd($result);  
        $records["details"] = $result;
        $records["message"] = "successful";
        $records["success"] = true;
        $records["success_bool"] = 1;

        echo json_encode($records);
    	}else{
    	$records["details"] = array();
        $records["message"] = "Post id is require";
        $records["success"] = false;
        $records["success_bool"] = 1;

        echo json_encode($records);
    	}

        // dd($result);
        // exit;
       
    }
    

    public function getStartupstage(Request $request) {

        $records = array();

        $result = Stages::select('id','stage_name')
                        //->where('status', '=', '1')
                        ->orderBy('stage_name', 'asc')->get();

        $records["details"] = $result;
        $records["message"] = "successful";
        $records["success"] = true;
        $records["success_bool"] = 1;

        echo json_encode($records);
    }

    
    public function userList(Request $request) {
    	if ($request->isMethod('post')) { 
    		$timestamp_id = $request->input('timestamp_id');

    		if(!empty($timestamp_id)){
    		$result = Users::select('id','contact_name')
                        ->where('status', '=', '1')
                        ->where('user_type', '=', '2')
                        ->where('timestamp_id', '<>', $timestamp_id)
                        ->orderBy('contact_name', 'asc')->get();
            }else{
                    	$result = Users::select('id','contact_name')
                        ->where('status', '=', '1')
                        ->where('user_type', '=', '2')
                        ->orderBy('contact_name', 'asc')->get();
            }

	        $records["details"] = $result;
	        $records["message"] = "successful";
	        $records["success"] = true;
	        $records["success_bool"] = 1;

	        echo json_encode($records);


    	}
    }


    public function getComment(Request $request) {
    	if ($request->isMethod('post')) { 
    		$timestamp_id = $request->input('timestamp_id');
    		$post_id = $request->input('post_id');

    		if(!empty($timestamp_id)){
    			$result = PostReply::select('*')
                    ->where('status', '=', '1')
                    ->where('post_id', '=', $post_id)
                    ->orderBy('reply_text', 'asc')->get();
    		}else{
    			$result = PostReply::select('*')
                    ->where('status', '=', '1')
                    ->where('post_id', '=', $post_id)
                    ->orderBy('reply_text', 'asc')->get();
    		}

    		if(!empty($result)){
    			$i=0;
    			foreach($result as $r){
    			$commentsUser= Users::where('id', '=', $r->replied_by)->first();	
	    		$result[$i]->posted_by_name =$commentsUser->contact_name;

	    		$result[$i]->posted_at = date('d M, Y', strtotime($r->created_at));
        		//$result[$i]->posted_at = str_replace('-', '/', $result[$i]->posted_at);

	    		}

	    		//dd($result);
	    	$records["details"] = $result;
		    $records["message"] = "successful";
		    $records["success"] = true;
		    $records["success_bool"] = 1;
	    
    		}else{
    		$records["details"] = array();
	        $records["message"] = "There are no reply";
	        $records["success"] = false;
	        $records["success_bool"] = 1;		
    		}

    		

	        echo json_encode($records);


    	}
    }



    public function getCommentReply(Request $request) {
    	if ($request->isMethod('post')) { 
    		$timestamp_id = $request->input('timestamp_id');
    		//$post_id = $request->input('post_id');
    		$comment_id = $request->input('comment_id');

    		if(!empty($timestamp_id)){
    			$result = PostReply::select('*')
                    ->where('status', '=', '1')
                    ->where('replied_on', '=', $comment_id)
                    ->orderBy('reply_text', 'asc')->get();
    		}else{
    			$result = PostReply::select('*')
                    ->where('status', '=', '1')
                    ->where('replied_on', '=', $comment_id)
                    ->orderBy('reply_text', 'asc')->get();
    		}


    		//dd($result);

    		if(!empty($result)){
    			$i=0;
    			foreach($result as $r){
    			$commentsUser= Users::where('id', '=', $r->replied_by)->first();	
	    		$result[$i]->posted_by_name =$commentsUser->contact_name;

	    		$result[$i]->posted_at = date('d M, Y', strtotime($r->created_at));
        		//$result[$i]->posted_at = str_replace('-', '/', $result[$i]->posted_at);

	    		}

	    		//dd($result);
	    	$records["details"] = $result;
		    $records["message"] = "successful";
		    $records["success"] = true;
		    $records["success_bool"] = 1;
	    
    		}else{
    		$records["details"] = array();
	        $records["message"] = "There are no reply";
	        $records["success"] = false;
	        $records["success_bool"] = 1;		
    		}

    		

	        echo json_encode($records);


    	}
    }


    public function loginAction(Request $request) {

        $records = array();
		
        if ($request->isMethod('post')) {        
        
			$arr_ret = array();   	
			
			$email_username = $request->input('email_username');
			$password = md5(trim($request->input('password')));	
			$deviceToken = $request->input('deviceToken');
						
			if (filter_var($email_username, FILTER_VALIDATE_EMAIL)) {
                $user = Users::where('email_id', '=', $email_username)
                        ->where('password', '=', $password)
                        ->where('status', '1')
                        //->where('user_type', '5')
                        ->first();


                if (!empty($user)) {

                    Auth::login($user);
                    //echo Auth::user()->email_id;die;                    
                    
					$user_id = Auth::user()->id;
					$current_login = Auth::user()->current_login;

					$update_data = array(
						'current_login' => date('Y-m-d H:i:s'),
						'last_login' => $current_login,
						'deviceToken' => $deviceToken,
					);
					$userDetails = Users::find($user_id);
					//dd($update_data);
					//$userDetails->update($update_data);

					$res = Users::where('id', '=', $user_id)->update($update_data);

					$userDetails->img_with_path = "public/uploads/user_images/original/".$userDetails->image;
					

					//dd($userDetails);
					$records["details"] = "Login successful";
					$records["userDetails"] = $userDetails;
					$records["error"] = '';    
					$records["message"] = "successful";
					$records["success"] = true;
					$records["success_bool"] = 1;
					
                    
                } else {
                    
					$records["details"] = "Invalid Credentials";
					$records["error"] = '';
					$records["message"] = "failed";
					$records["success"] = false;
					$records["success_bool"] = 1;
					
                }
            } else {
                $user = Users::where('username', '=', $email_username)
                        ->where('password', '=', $password)
                        ->where('status', '1')
                        ->where('user_type', '2')
                        ->first();

                if (!empty($user)) {

                    Auth::login($user);
                    //echo Auth::user()->username;die;					
					
					$user_id = Auth::user()->id;
					$current_login = Auth::user()->current_login;

					$update_data = array(
						'current_login' => date('Y-m-d H:i:s'),
						'last_login' => $current_login,
						'deviceToken' => $deviceToken,
					);
					$userDetails = Users::find($user_id);
					//$userDetails->update($update_data);
					$res = Users::where('id', '=', $user_id)->update($update_data);
										
					$userDetails->img_with_path = "public/uploads/user_images/original/".$userDetails->image;
					

					//dd($userDetails);					
                    $records["details"] = "Login successful";
					$records["userDetails"] = $userDetails;
					$records["error"] = '';    
					$records["message"] = "successful";
					$records["success"] = true;
					$records["success_bool"] = 1;
                    
                    
                } else {
					
					$records["details"] = "Invalid Credentials";
					$records["error"] = '';
					$records["message"] = "failed";
					$records["success"] = false;
					$records["success_bool"] = 1;
                    
                }
            }
			
			
		}else{
			
			$records["details"] = "Login failed";
            $records["error"] = '';
            $records["message"] = "failed";
            $records["success"] = false;
            $records["success_bool"] = 1;
			
		}

        echo json_encode($records);
    }
	
	public function logoutAction(Request $request) {

        $records = array();
		
        if ($request->isMethod('post')) {        
        
			$arr_ret = array();   	
			
			$id = $request->input('user_id');   
                
			$update_data = array(
				'deviceToken' => '',
			);
			$userDetails = Users::find($id);
			//dd($userDetails);
			//$userDetails->update($update_data);
			$res = Users::where('id', '=', $id)->update($update_data);

			$records["details"] = "Logout successful";			
			$records["error"] = '';    
			$records["message"] = "successful";
			$records["success"] = true;
			$records["success_bool"] = 1;                   
               
			
		}else{
			
			$records["details"] = "Logout failed";
            $records["error"] = '';
            $records["message"] = "failed";
            $records["success"] = false;
            $records["success_bool"] = 1;
			
		}

        echo json_encode($records);
    }

    

   
 
		

    public function resetPassword(Request $request){
    		$email_id = $request->input('email_id');

    		$user = Users::where('email_id', '=', $email_id)->first();
    		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    		$strength='8';

    		$input_length = 8;
		    $random_string = '';
		    for($i = 0; $i < $strength; $i++) {
		        $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
		        $random_string .= $random_character;
		    }

    		$password = $random_string;
    		
        	$records = array();

        	if(!empty($user)){
        	

			

	        	$template_data = EmailTemplate::where('id', '=', '2')->first();
				//dd($template_data);
				$content = $template_data->description;

				
				$content = str_replace("{{email}}", $email_id, $content);
				$content = str_replace("{{username}}", $user->contact_name, $content);
				$content = str_replace("{{password}}", $password, $content);
				//$content = str_replace("{{link}}", $url, $content);



	   //      	$emailData = array();
				// $emailData['subject'] = 'Reset Password';
				// $emailData['body'] = $content;
				// $emailData['to_email'] = $email_id;
				// $emailData['from_email'] ='admin@iimcip.com';
				// $emailData['from_name'] = 'IIMCIP';
				//echo "<pre>"; print_r($emailData); die;

				// Mail::send('emails.accountVerification', ['emailData' => $emailData], function ($message) use ($emailData) {

				// 	$message->from($emailData['from_email'], $emailData['from_name']);

				// 	$message->to($emailData['to_email'])->subject($emailData['subject']);
				// 	$message->bcc('karmickdeveloper77@gmail.com')->subject($emailData['subject']);
					
				// });

				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				// More headers
				$headers .= 'From: <admin@iimcip.com>' . "\r\n";
				$headers .= 'Cc: karmickdeveloper77@gmail.com' . "\r\n";

				mail($email_id,'Reset Password',$content,$headers);

				$update_data = array(
						'password' => md5($password),
					);
				$res = Users::where('id', '=', $user->id)->update($update_data);
				$records["details"] = array();
		        $records["message"] = "successful";
		        $records["success"] = true;
		        $records["success_bool"] = 1;
            }
            else{
            $records["details"] = array();
	        $records["message"] = "Email id does not match";
	        $records["success"] = false;
	        $records["success_bool"] = 1;
            }

	        

	        echo json_encode($records);

    }



    public function saveUser(Request $request) {
    	$records = array();
  //   	$request->validate([
			
  //           'email_id' => 'required|email|unique:users,email_id'
		// ],[
		
		// 	'email_id.unique' => 'This Email-id Already Exist, Try Another.'
		// ]);
    	$email = trim($request->input('email_id'));

    	$UserWithEmail = Users::where('email_id', '=', $email)->first();

    		
    		if(!empty($UserWithEmail)){

    			$records["details"] = array();
		        $records["message"] = "Email already exist";
		        $records["success"] = false;
		        $records["success_bool"] = 1;

		        echo json_encode($records);
		        exit;
    		}else{

    	$Users = new Users;
    	$Users->timestamp_id = md5(microtime(TRUE));
    	$Users->member_company = trim($request->input('member_company'));
    	$Users->contact_name = trim($request->input('contact_name'));
    	$Users->email_id = trim($request->input('email_id'));
    	$Users->contact_no = trim($request->input('contact_no'));
    	$Users->password = md5(trim($request->input('password')));
        $Users->user_type = 2;


        if( $request->hasFile('image') ) {

                $image = $request->file('image');
                $real_path = $image->getRealPath();
                $file_orgname = $image->getClientOriginalName();
                $file_size = $image->getClientSize();
                $file_ext = strtolower($image->getClientOriginalExtension());
                $file_newname = "user"."_".time().".".$file_ext;


                if($file_size < (1048578*5)){
                $destinationPath = public_path('/uploads/user_images');
                $original_path = $destinationPath."/original";
                $thumb_path = $destinationPath."/thumb";
                
                $img = Image::make($real_path);
                $img->resize(150, 150, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumb_path.'/'.$file_newname);

                $image->move($original_path, $file_newname);
                

                $Users->image = $file_newname;

                }else{
	          	$records["details"] = array();
		        $records["message"] = "Image size is too large. It should be under 5 mb";
		        $records["success"] = false;
		        $records["success_bool"] = 1;

		        echo json_encode($records);
		        exit;
	          }
            }



        $Users->mobile = trim($request->input('mobile'));
        $Users->stage_id = trim($request->input('stage_id'));
        $Users->country = trim($request->input('country'));
        $Users->city = trim($request->input('city'));
        $Users->website = trim($request->input('website'));
        $Users->legal_status = trim($request->input('legal_status'));
        $Users->profile_info = trim($request->input('profile_info'));

        //dd(Auth::user());
        //$Users->created_by = Auth::user()->id;

    	if( $Users->save() ) {
            $industry_idarr = $request->input('industry_id');
            // echo $industry_idarr;
            // exit;
            if(!empty($industry_idarr)){
            $industry_ids = explode(',', $industry_idarr);
             }
            if(!empty($industry_ids)){
                foreach($industry_ids as $ii){
                    $member_id = $Users->id;
                    $MemberBusiness = new MemberBusiness;
                    $MemberBusiness->industry_category_id = $ii;
                    $MemberBusiness->status = '1';
                    $MemberBusiness->member_id = $member_id;
                    $MemberBusiness->save();
                    }
                }
    		//return back()->with('msg_class', 'alert alert-success')->with('msg', 'New User Created Succesfully.');
            

        	$records["details"] = $Users;    
            $records["message"] = "successful";
	        $records["success"] = true;
	        $records["success_bool"] = 1;

	        echo json_encode($records);

	    	} 
	    	else {
	    		// return back()->with('msg_class', 'alert alert-danger')
	    		// ->with('msg', 'Something Went Wrong.');
	    		$records["details"] = array();
	    		$records["message"] = "Sorry! Something Went Wrong.";
		        $records["success"] = false;
		        $records["success_bool"] = 0;

		        echo json_encode($records);
	    	}
    	}
    }


    public function updateUser(Request $request) {
    	$records = array();
        // dd($_POST);

        $user_timestamp_id = $request->input('timestamp_id');
        //exit;

    	$User = Users::where('timestamp_id', '=', $user_timestamp_id)->first();



    	if( !empty($User) ) {


    		$email = trim($request->input('email_id'));

    		$UserWithEmail = Users::where('timestamp_id', '<>', $user_timestamp_id)->where('email_id', '=', $email)->first();

    		
    		if(!empty($UserWithEmail)){

    			$records["details"] = array();
		        $records["message"] = "Email already exist";
		        $records["success"] = false;
		        $records["success_bool"] = 1;

		        echo json_encode($records);
		        exit;
    		}else{

            

    		$updateData = array();
    		if(!empty($request->input('member_company'))){
    		$updateData['member_company'] = trim($request->input('member_company'));
    		}
    		if(!empty($request->input('contact_name'))){
    		$updateData['contact_name'] = trim($request->input('contact_name'));
    		}
    		if(!empty($request->input('email_id'))){
    		$updateData['email_id'] = trim($request->input('email_id'));
    		}
    		if(!empty($request->input('contact_no'))){	
    		$updateData['contact_no'] = trim($request->input('contact_no'));
    		}
    		if(!empty($request->input('mobile'))){
    		$updateData['mobile'] = trim($request->input('mobile'));
    		}
    		if(!empty($request->input('stage_id'))){
    		$updateData['stage_id'] = trim($request->input('stage_id'));
    		}
    		if(!empty($request->input('country'))){
            $updateData['country'] = trim($request->input('country'));
        	}
        	if(!empty($request->input('city'))){
            $updateData['city'] = trim($request->input('city'));
        	}
        	if(!empty($request->input('website'))){
            $updateData['website'] = trim($request->input('website'));
        	}
        	if(!empty($request->input('legal_status'))){
            $updateData['legal_status'] = trim($request->input('legal_status'));
        	}
        	if(!empty($request->input('profile_info'))){
            $updateData['profile_info'] = trim($request->input('profile_info'));
        	}

    		$updateData['updated_at'] = date('Y-m-d H:i:s');

    		if( $request->hasFile('image') ) {

	    		$image = $request->file('image');
	    		$real_path = $image->getRealPath();
	            $file_orgname = $image->getClientOriginalName();
	            $file_size = $image->getClientSize();
	            $file_ext = strtolower($image->getClientOriginalExtension());
	            $file_newname = "user"."_".time().".".$file_ext;


	            if($file_size < (1048578*5)){
	            $destinationPath = public_path('/uploads/user_images');
	            $original_path = $destinationPath."/original";
	            $thumb_path = $destinationPath."/thumb";
	            
	            $img = Image::make($real_path);
	        	$img->resize(150, 150, function ($constraint) {
			    	$constraint->aspectRatio();
				})->save($thumb_path.'/'.$file_newname);

	        	$image->move($original_path, $file_newname);
	        	if(!empty($request->input('file_newname'))){
	        	$updateData['image'] = $file_newname;
	        	}
	        	$res = Users::where('timestamp_id', '=', $user_timestamp_id)->update($updateData);	


                if( $res ) {
                $industry_idarr = $request->input('industry_id');
                if(!empty($industry_idarr)){
                $industry_ids = explode(',', $industry_idarr);
                }
                if(!empty($industry_ids)){
                    MemberBusiness::where('member_id', '=', $User->id)->delete();
                    foreach($industry_ids as $ii){
                        $member_id = $User->id;
                        $MemberBusiness = new MemberBusiness;
                        $MemberBusiness->industry_category_id = $ii;
                        $MemberBusiness->status = '1';
                        $MemberBusiness->member_id = $member_id;
                        $MemberBusiness->save();
                        }
                    }

                $User = Users::where('timestamp_id', '=', $user_timestamp_id)->first();

                $records["details"] = $User;
                $records["message"] = "successful";
                $records["success"] = true;
                $records["success_bool"] = 1;

                echo json_encode($records);
            } else {
                $records["details"] = array();
                $records["message"] = "Something Went Wrong.";
                $records["success"] = false;
                $records["success_bool"] = 1;

                echo json_encode($records);
            }


	          }else{


	          	$records["details"] = array();
		        $records["message"] = "Image size is too large. It should be under 5 mb";
		        $records["success"] = false;
		        $records["success_bool"] = 1;

		        echo json_encode($records);
		        exit;
	          }
	    	}
	    }




	    	
	    	
    	} else {
    			$records["details"] = array();
		        $records["message"] = "Porvidev user id is wrong";
		        $records["success"] = false;
		        $records["success_bool"] = 1;

		        echo json_encode($records);
    	}
    }

    public function userDetl(Request $request) {

    	$records = array();
    	$user_timestamp_id = $request->input('timestamp_id');
    	//$user = Users::where('timestamp_id', '=', $user_timestamp_id)->first();



    	$user = DB::table('users')
			->select('users.id', 'member_company','user_type','timestamp_id','contact_name','email_id','contact_no','image','mobile','stage_id','stage_name','country','city','website','legal_status','profile_info')
			 ->join('stage', 'users.stage_id', '=', 'stage.id')
			 ->where("status", "=", 1);		
		 
		if(!empty($user_timestamp_id)){
			$user = $user->where("timestamp_id", "=", $user_timestamp_id); 
		}
		
		$user = $user->orderBy('member_company', 'asc')->get()->first();
		//dd($user);
		$destinationPath = 'public/uploads/user_images/original/'; 
	    //$original_path = $destinationPath."/original";
	    //echo $original_path.$user->image;


	   	$memberBusiness= DB::table('member_business')
			->select('industry_category_id', 'industry_category','industry_category.id')
			 ->join('industry_category', 'member_business.industry_category_id', '=', 'industry_category.id')
			 ->where("industry_category.status", "=", 1)
			 ->where("member_id", "=", $user->id)->get();	

	   	//MemberBusiness::where('member_id', '=', $user->id)->get();
		// echo $user->id;	 
	 //   	dd($memberBusiness);

		$user->image_with_path = $destinationPath.$user->image;
        $records["details"] = $user;
        $records['memberBusiness'] = $memberBusiness;
        //$records["image_path"] = $original_path;
        $records["message"] = "successful";
        $records["success"] = true;
        $records["success_bool"] = 1;
        //dd($records);


        echo json_encode($records);
    }

    public function addPost(Request $request){

    	$timestamp_id = $request->input('timestamp_id');
        #$post_id = $request->input('post_id'); 

        $user = Users::where('timestamp_id', '=', $timestamp_id)->first();
        $records = array();

        if(!empty($user->id)){
        	$Posts = new Posts;
        	$Posts->post_title = $request->input('post_title');
        	$Posts->post_info = $request->input('post_info');
        	$Posts->member_id = $user->id;
        	$Posts->post_type = $request->input('post_type'); //2=>private, 1=>public
        	$Posts->private_member_id = $request->input('assign_to');
        	$Posts->status = '1';

        	$Posts->save();
            // echo $Posts->id;
            // exit;
        	$industry_idarr = $request->input('industry_id');
                if(!empty($industry_idarr)){
                $industry_ids = explode(',', $industry_idarr);
                }
            if(!empty($industry_ids)){
                foreach($industry_ids as $ii){
                    $member_id = $user->id;
                    $PostIndustry = new PostIndustry;
                    $PostIndustry->industry_category_id = $ii;
                    $PostIndustry->status = '1';
                    $PostIndustry->post_id = $Posts->id;
                    $PostIndustry->save();
                    }
                }
        	$records["details"] = array();
	        $records["message"] = "Post Added successfully";
	        $records["success"] = true;
	        $records["success_bool"] = 1;

	        echo json_encode($records);

        }else{
        	$records["details"] = array();
	        $records["message"] = "User credentials does not match";
	        $records["success"] = false;
	        $records["success_bool"] = 1;

	        echo json_encode($records);	
        }	


    }


    public function editPost(Request $request){

    	$timestamp_id = $request->input('timestamp_id');
        $post_id = $request->input('post_id'); 

        $user = Users::where('timestamp_id', '=', $timestamp_id)->first();
        $post = Posts::where('id', '=', $post_id)->first();
        $postReply = PostReply::where('post_id', '=', $post_id)->first();

        if(!empty($user->id)){
        	if($post->member_id == $user->id){
        		if(empty($postReply)){
        			$editPosts = array();
        			if(!empty($request->input('post_title'))){
		        	$editPosts['post_title'] = $request->input('post_title');
		        	}
		        	if(!empty($request->input('post_info'))){
		        	$editPosts['post_info'] = $request->input('post_info');
		        	}

		        	if(!empty($request->input('post_type'))){
		        	$editPosts['post_type'] = $request->input('post_type'); //1=>private, 2=>public
		        	}
		        	if(!empty($request->input('assign_to'))){
		        	$editPosts['private_member_id']= $request->input('assign_to');
		        	}
		        	
		        	//dd($editPosts);
		        	$res = Posts::where('id', '=', $post_id)->update($editPosts);

                    if(!empty($request->input('industry_id'))){
		        	$industry_idarr = $request->input('industry_id');
                    if(!empty($industry_idarr)){
                    $industry_ids = explode(',', $industry_idarr);
                    }
	                if(!empty($industry_ids)){
	                    PostIndustry::where('post_id', '=', $post_id)->delete();
	                    foreach($industry_ids as $ii){
	                        $member_id = $user->id;
	                        $PostIndustry = new PostIndustry;
	                        $PostIndustry->industry_category_id = $ii;
	                        $PostIndustry->status = '1';
	                        $PostIndustry->post_id = $post_id;
	                        $PostIndustry->save();
	                        }
	                    }
		              }
		        	$post = Posts::where('id', '=', $post_id)->first();
		        	$records["details"] = $post;
			        $records["message"] = "Post Added successfully";
			        $records["success"] = true;
			        $records["success_bool"] = 1;

			        echo json_encode($records);
			    	}else{
			    		$records["details"] = array();
				        $records["message"] = "User cannot edit this post, already users comments on that";
				        $records["success"] = false;
				        $records["success_bool"] = 1;
				        echo json_encode($records);
			    	}
		    	}else{
		    	$records["details"] = array();
		        $records["message"] = "User cannot edit this post";
		        $records["success"] = false;
		        $records["success_bool"] = 1;
		        echo json_encode($records);
		    	}

        }else{
        	$records["details"] = array();
	        $records["message"] = "User credentials does not match";
	        $records["success"] = false;
	        $records["success_bool"] = 1;

	        echo json_encode($records);	
        }	


    }


    public function addReply(Request $request){

    	$timestamp_id = $request->input('timestamp_id');
        $post_id = $request->input('post_id'); 
        $post = Posts::where('id', '=', $post_id)->first();

        $user = Users::where('timestamp_id', '=', $timestamp_id)->first();
        $records = array();

        if(!empty($user->id) && (!empty($post->id))){
        	$PostReply = new PostReply;
        	$PostReply->post_id  = $post->id;
        	$PostReply->replied_on = '0';
        	$PostReply->replied_by = $user->id;
        	$PostReply->reply_text = $request->input('reply_text');
        	$PostReply->status = '1';

        	$PostReply->save();
        	$records["details"] = array();
	        $records["message"] = "Post Comments Added Successfully";
	        $records["success"] = true;
	        $records["success_bool"] = 1;

	        echo json_encode($records);

        }else{
        	$records["details"] = array();
	        $records["message"] = "User Credentials or Post does not match";
	        $records["success"] = false;
	        $records["success_bool"] = 1;

	        echo json_encode($records);	
        }	
    }


    public function editReply(Request $request){

    	$timestamp_id = $request->input('timestamp_id');
        $post_id = $request->input('post_id'); 
        $post_reply_id = $request->input('post_reply_id'); 
        $post = Posts::where('id', '=', $post_id)->first();
        $postReply = PostReply::where('replied_on', '=', $post_reply_id)->first();

        $user = Users::where('timestamp_id', '=', $timestamp_id)->first();
        $records = array();

        if(!empty($user->id) && (!empty($post->id))){
        	if(empty($postReply)){
        	// $PostReply = new PostReply;
        	// $PostReply->post_id  = $post->id;
        	// $PostReply->replied_on = '0';
        	// $PostReply->replied_by = $user->id;
        	// $PostReply->reply_text = $request->input('reply_text');
        	// $PostReply->status = '1';

        	
        	$editPosts['reply_text'] = $request->input('reply_text');



        	$res = PostReply::where('id', '=', $post_reply_id)->update($editPosts);
        	$records["details"] = array();
	        $records["message"] = "Post Comments Added Successfully";
	        $records["success"] = true;
	        $records["success_bool"] = 1;

	        echo json_encode($records);
	    	}else{
	    	$records["details"] = array();
	        $records["message"] = "Post Comment is noteditable, already someone comment on that";
	        $records["success"] = false;
	        $records["success_bool"] = 1;
	    	}

        }else{
        	$records["details"] = array();
	        $records["message"] = "User Credentials or Post does not match";
	        $records["success"] = false;
	        $records["success_bool"] = 1;

	        echo json_encode($records);	
        }	
    }



}
