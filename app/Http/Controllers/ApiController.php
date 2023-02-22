<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Users;
use App\Models\Stages;
use App\Models\IndustryCategories;
use App\Models\Categories;
use App\Models\EmailTemplate;
use App\Models\MemberBusiness;
use App\Models\PostIndustry;
use App\Models\Posts;
use App\Models\PostReply;
use App\Models\PostGuideline;
use App\Models\PostCategory;
use App\Models\PostMedia;
use App\Models\FounderTransaction;
use App\Models\MemberService;
use App\Models\MemberVideo;
use App\Models\Message;
use App\Models\IndustryExpertise;
use App\Models\CompanyType;


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
        
        
        ini_set('upload_max_filesize', '20M');
        ini_set('post_max_size', '20M');
        ini_set('max_input_time', 500);
        ini_set('max_execution_time', 500);
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
    
    
    public function getPostGuideline(Request $request) {

        $records = array();

        $result = PostGuideline::select('id','post_guide')
                        //->where('status', '=', '1')
                        ->orderBy('post_guide', 'asc')->get();

        $records["details"] = $result;
        $records["message"] = "successful";
        $records["success"] = true;
        $records["success_bool"] = 1;

        echo json_encode($records);
    }

    public function getCategory(Request $request) {

        $records = array();

        $result = Categories::select('id','name')
                        ->where('status', '=', '1')
                        ->orderBy('name', 'asc')->get();

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

        //$memberCategory = PostCategory::where('member_id', '=', $user->id)->pluck('category_id')->toArray();

        //$memberBusinessStr = implode(',', $memberBusiness);
        //echo $memberBusinessStr; exit;
        #dd($memberBusiness);
        $result = array();
        if(!empty($user->id)){
            $uid= $user->id;
            //echo $user->id; exit;
                   if($industry_type == '1'){
 
                        $result = DB::table('post_master')
                            ->select('post_master.*')
                             ->join('post_industry', 'post_master.id', '=', 'post_industry.post_id')
                        ->where(function ($query)  use($uid,$memberBusiness) {
                        $query->where('post_master.private_member_id', '=', $uid)
                            ->whereIn('post_industry.industry_category_id', $memberBusiness)
                            ->where('post_master.post_type', '=', '2')
                            ->where('post_master.status', '=', '1');
                        })->orWhere(function($query) use($memberBusiness){
                            $query->where('post_master.status', '=', '1')
                                ->whereIn('post_industry.industry_category_id', $memberBusiness)
                                ->where('post_master.post_type', '=', '1');   
                        })
                         ->groupBy('post_master.id')
                        ->orderBy('post_master.id', 'desc')->get();
                    }else{
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
                        ->groupBy('post_master.id')
                        ->orderBy('post_master.id', 'desc')->get();
                    }
        
                //dd($result);

        if(!empty($result)){
        	$i=0;
        	foreach($result as $r){

                 

                $favourate_count =DB::table('post_favourates')->where('post_id',$r->id)->where('member_id',$user->id)->count();


                $result[$i]->is_favorate = $favourate_count;
 

        		$postDiv = html_entity_decode($r->post_info);

                $result[$i]->post = '<div style="line-height:3;">'.$postDiv.'<div>';

        		$postReply = PostReply::where('post_id', '=', $r->id)->count();
        		$userCom = Users::where('id', '=', $r->member_id)->first();
        		$result[$i]->replyCount = $postReply;
                if(!empty($userCom->member_company)){
        		$result[$i]->member_company = $userCom->member_company;
                }else{
                    $result[$i]->member_company = '';
                }

        		// echo  $r->member_id."____".$userCom->stage_id;

        		// exit;
        		$stageArr = Stages::where('id', '=', $userCom->stage_id)->first();
                if(!empty($stageArr)){
        		$result[$i]->stage = $stageArr->stage_name;
                }else{
                  $result[$i]->stage = '';  
                }




        		$result[$i]->posted_user_image = 'public/uploads/user_images/original/'.$userCom->image;
                $result[$i]->timestamp_id = $userCom->timestamp_id;
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


                 $result[$i]->postIndustry= DB::table('post_industry')
                ->select('industry_category_id', 'industry_category')
                 ->join('industry_category', 'post_industry.industry_category_id', '=', 'industry_category.id')
                 ->where("industry_category.status", "=", 1)
                 ->where("post_id", "=", $r->id)->get();


                 





                
                    
                $result[$i]->postCategory= DB::table('post_categories')
                    ->select('category_id', 'name')
                     ->join('categories', 'post_categories.category_id', '=', 'categories.id')
                     ->where("categories.status", "=", 1)
                     ->where("post_id", "=", $r->id)->get();


             //dd($result[$i]->postCategory);
                 


                 




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

        $postDiv = html_entity_decode($result->post_info);

        $result->post = '<div style="line-height:3;">'.$postDiv.'<div>';

        $result->member_company = $userCom->member_company;
        $result->posted_user_image = 'public/uploads/user_images/thumb/'.$userCom->image;
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

        
        $result->postIndustry= DB::table('post_industry')
                ->select('industry_category_id', 'industry_category')
                 ->join('industry_category', 'post_industry.industry_category_id', '=', 'industry_category.id')
                 ->where("industry_category.status", "=", 1)
                 ->where("post_id", "=", $post_id)->get();




        $result->postCategory= DB::table('post_categories')
                ->select('category_id', 'name')
                 ->join('categories', 'post_categories.category_id', '=', 'categories.id')
                 ->where("categories.status", "=", 1)
                 ->where("post_id", "=", $post_id)->get();

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


    public function userList1(Request $request) {
    	if ($request->isMethod('post')) { 
    		$timestamp_id = $request->input('timestamp_id');

    		if(!empty($timestamp_id)){
    		$result = Users::select('id','contact_name','member_company','area_of_expertise','email_id','linkedIn','image')
                        ->where('status', '=', '1')
                        ->where('contact_name' ,'!=','')
                        ->where('user_type', '=', '2')
                        ->where('timestamp_id', '<>', $timestamp_id)
                        ->whereNull('founder_id')
                        ->orderBy('contact_name', 'asc')->get();
            }else{
                    	$result = Users::select('id','contact_name','member_company','area_of_expertise','email_id','linkedIn','image')
                        ->where('status', '=', '1')
                        ->where('contact_name' ,'!=','')
                        ->where('user_type', '=', '2')
                        ->whereNull('founder_id')
                        ->orderBy('contact_name', 'asc')->get();
            }


            foreach ($result as $key => $user) {

                $company_id =$user->id;
                  
                $area_of_expertise = explode(',',$user->area_of_expertise);
    
                $str='';
                foreach( $area_of_expertise  as $row){
                    if($row!=''){
                      $ar =  DB::table('industry_expertise')->where('id', '=', $row)->first(); 
    
                      if(isset($ar)){
                        $str.=$ar->industry_expertise.', ';
                      }
                        
                    }
                }
     
                $result[$key]['area_of_expertise']= rtrim( $str, ', ');
       
                $result[$key]['number_of_post']=Posts::where('member_id', '=', $company_id)->where('status', '=', '1')->count();
    
                $result[$key]['number_of_reply']=PostReply::where('replied_by', '=', $company_id)->where('status', '=', '1')->count();
     
                $image=$result[$key]['image'];

                $result[$key]['image'] =  asset('public/front_end/images/profile-pic.png');
                if( $image != null &&  $image!='') {
                    $result[$key]['image'] = asset('public/uploads/user_images/thumb/'.$image);
                    // $result[$key]['image'] =  $result[$key]['image'];
                }
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


            $post = Posts::select('post_title','post_info')
                        ->where('status', '=', '1')
                        ->where('id', '=', $post_id)->first();
            //dd($post);

    		if(!empty($timestamp_id)){
    			$result = PostReply::select('*')
                    ->where('status', '=', '1')
                    ->where('post_id', '=', $post_id)
                    ->where('replied_on', '=', 0)
                    ->orderBy('id', 'desc')
                    ->get();
    		}else{
    			$result = PostReply::select('*')
                    ->where('status', '=', '1')
                    ->where('post_id', '=', $post_id)
                    ->where('replied_on', '=', 0)
                    //->orderBy('id', 'desc')
                    ->get();
    		}

    		if(!empty($result)){
    			$i=0;
    			foreach($result as $r){
                   
                   
    			$commentsUser= Users::where('id', '=', $r->replied_by)->first();	

                $result[$i]->postTitle =$post->post_title;
                $result[$i]->post =html_entity_decode($post->post_info);

	    		$result[$i]->comment_by_name =$commentsUser->contact_name;
                $result[$i]->member_company = $commentsUser->member_company;
                $result[$i]->comment_user_image = 'public/uploads/user_images/original/'.$commentsUser->image;


                $post_images= DB::table('post_medias')
                                ->select('media_path')
                                ->where("post_reply_id", "=",$r->id)
                                 ->where("media_type", "=", 'I')
                                 ->where("status", "=", 1)->get();

                $postVideoMatter =array();

                if(!empty($post_images) && count($post_images))
                {
                     foreach($post_images as $key =>$fpv1)
                     {
                            $postVideoMatter[$key] = asset('public/uploads/posts/images/'. $fpv1->media_path);
                     }


                }

                /*

                $post_videos= DB::table('post_medias')
                                ->select('media_path')
                                ->where("post_reply_id", "=",$r->id)
                                 ->where("media_type", "=", 'V')
                                 ->where("status", "=", 1)->get();*/

                /*$postVideoMatterVideo =array();

                if(!empty($post_videos) && count($post_videos))
                {
                     foreach($post_videos as $key1 =>$fpi)
                     {
                            $postVideoMatterVideo[$key1] = asset('public/uploads/posts/videos/'. $fpi->media_path);
                     }
                }*/

                $result[$i]->post_images =$postVideoMatter;
                //$result[$i]->post_videos =$postVideoMatterVideo;




                $memberBusiness= DB::table('member_business')
                                ->select('industry_category_id', 'industry_category','industry_category.id')
                                 ->join('industry_category', 'member_business.industry_category_id', '=', 'industry_category.id')
                                 ->where("industry_category.status", "=", 1)
                                 ->where("member_id", "=", $commentsUser->id)->get();

                //dd($r);     
                $comment_reply =array();            
                $comment_reply = PostReply::select('replied_by','reply_text','created_at','video_url','id')
                    ->where('status', '=', '1')
                    ->where('replied_on', '=', $r->id)
                    ->orderBy('id', 'desc')->get();                

                //dd($comment_reply); 

                
                
                //$result[$i]->commentreply =$comment_reply;     
                //$cmtRep =array();   
                if(!empty($comment_reply)){ 
                    //$g='0'; 
                    foreach($comment_reply as $cr){  
                    //echo $g; 


                        $post_images_1= DB::table('post_medias')
                                ->select('media_path')
                                ->where("post_reply_id", "=",$cr->id)
                                 ->where("media_type", "=", 'I')
                                 ->where("status", "=", 1)->get();

                        $postVideoMatter_2 =array();

                        if(!empty($post_images_1) && count($post_images_1))
                        {
                             foreach($post_images_1 as $key =>$fpv2)
                             {
                                    $postVideoMatter_2[$key] = asset('public/uploads/posts/images/'. $fpv2->media_path);
                             }


                        }

                        /*$post_videos_1= DB::table('post_medias')
                                        ->select('media_path')
                                        ->where("post_reply_id", "=",$cr->id)
                                         ->where("media_type", "=", 'V')
                                         ->where("status", "=", 1)->get();

                        $postVideoMatterVideo_2 =array();

                        if(!empty($post_videos_1) && count($post_videos_1))
                        {
                             foreach($post_videos_1 as $key1 =>$fpi_2)
                             {
                                    $postVideoMatterVideo_2[$key1] = asset('public/uploads/posts/videos/'. $fpi_2->media_path);
                             }
                        }*/

                        $cr->post_images =$postVideoMatter_2;
                        //$cr->video_url =$postVideoMatterVideo;



                       
                    $replyUser= Users::where('id', '=', $cr->replied_by)->first(); 
                    //dd($replyUser);   
                    //echo $replyUser->contact_name;
                    if(!empty($replyUser->contact_name)){
                    $cr->replied_by_contact_name =$replyUser->contact_name;
                    }else{
                     $cr->replied_by_contact_name = "";   
                    } 
                    $cr->replied_member_company =$replyUser->member_company; 
                    $cr->replied_user_image = 'public/uploads/user_images/original/'.$replyUser->image;
                    $cr->replied_at = date('d M, Y', strtotime($cr->created_at));
                    $cr->reply_of_comments = $cr->reply_text;
                    //$g++;
                 }
                }
                //dd($comment_reply);
                $result[$i]->cmtRep =$comment_reply;  
                $result[$i]->industry = $memberBusiness;            

	    		$result[$i]->commented_at = date('d M, Y', strtotime($r->created_at));
        		//$result[$i]->posted_at = str_replace('-', '/', $result[$i]->posted_at);
                $i++;
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
                    ->orderBy('id', 'desc')->get();
    		}else{
    			$result = PostReply::select('*')
                    ->where('status', '=', '1')
                    ->where('replied_on', '=', $comment_id)
                    ->orderBy('id', 'desc')->get();
    		}


    		//dd($result);

    		if(!empty($result)){
    			$i=0;
    			foreach($result as $r){
    			$commentsUser= Users::where('id', '=', $r->replied_by)->first();	
	    		$result[$i]->posted_by_name =$commentsUser->contact_name;

                $result[$i]->member_company = $commentsUser->member_company;
                $result[$i]->comment_user_image = 'public/uploads/user_images/original/'.$commentsUser->image;



                $post_images= DB::table('post_medias')
                                ->select('media_path')
                                ->where("post_reply_id", "=",$r->id)
                                 ->where("media_type", "=", 'I')
                                 ->where("status", "=", 1)->get();

                $postVideoMatter =array();

                if(!empty($post_images) && count($post_images))
                {
                     foreach($post_images as $key =>$fpv1)
                     {
                            $postVideoMatter[$key] = asset('public/uploads/posts/images/'. $fpv1->media_path);
                     }


                }

                /*$post_videos= DB::table('post_medias')
                                ->select('media_path')
                                ->where("post_reply_id", "=",$r->id)
                                 ->where("media_type", "=", 'V')
                                 ->where("status", "=", 1)->get();

                $postVideoMatterVideo =array();

                if(!empty($post_videos) && count($post_videos))
                {
                     foreach($post_videos as $key1 =>$fpi)
                     {
                            $postVideoMatterVideo[$key1] = asset('public/uploads/posts/videos/'. $fpi->media_path);
                     }
                }*/

                $result[$i]->post_images =$postVideoMatter;
                //$result[$i]->post_videos =$postVideoMatterVideo;



                $memberBusiness= DB::table('member_business')
                                ->select('industry_category_id', 'industry_category','industry_category.id')
                                 ->join('industry_category', 'member_business.industry_category_id', '=', 'industry_category.id')
                                 ->where("industry_category.status", "=", 1)
                                 ->where("member_id", "=", $commentsUser->id)->get();



                $result[$i]->industry = $memberBusiness;

	    		$result[$i]->reply_at = date('d M, Y', strtotime($r->created_at));
        		//$result[$i]->posted_at = str_replace('-', '/', $result[$i]->posted_at);
                $i++;
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

                    $new_time = date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));                   
                    
					$user_id = $user->id;
					$current_login = $user->current_login;

                    if(empty($current_login))
                    {
                        $current_login =$new_time;
                    }

					$update_data = array(
						 'current_login' => $new_time,
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

            $new_time = date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));
        	
			
			$timestamp_id = $request->input('timestamp_id');   
                
			$update_data = array(
				'deviceToken' => '',
                'last_login' => $new_time,
			);
			//$userDetails = Users::find($id);
			//dd($userDetails);
			//$userDetails->update($update_data);
			$res = Users::where('timestamp_id', '=', $timestamp_id)->update($update_data);

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
        $Users->slug  =Str::slug($Users->member_company, '-');
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

        $Users->last_login =date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));

        $Users->current_login =date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));

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

        //dd($request->all());
    	$records = array();
        $user_timestamp_id = $request->input('timestamp_id');
    	$primary = Users::where('timestamp_id', '=', $user_timestamp_id)->first();

        $company_id =company_id($primary->id);

        $User = Users::where('id', '=', $company_id)->first();

        $user_timestamp_id =$User->timestamp_id;

    	if( !empty($User) ) {


    		$email = $User->email_id;

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

            $updateData['slug'] =Str::slug($updateData['member_company'], '-');
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
            if(!empty($request->input('profile_info'))){
                $updateData['about_you'] = trim(htmlentities($request->input('profile_info'), ENT_QUOTES));
            }

            if(!empty($request->input('member_company_types'))){
                $updateData['company_type'] = trim($request->input('member_company_types'));
            }

              
                
            if(!empty($request->input('member_spec'))){
            $updateData['member_spec'] = trim($request->input('member_spec'));
            }
            if(!empty($request->input('member_looking'))){
            $updateData['member_looking'] = trim($request->input('member_looking'));
            }
            if(!empty($request->input('member_help'))){
            $updateData['member_help'] = trim($request->input('member_help'));
            }
            if(!empty($request->input('achievements'))){
            $updateData['achievements'] = trim($request->input('achievements'));
            }
            if(!empty($request->input('certifications'))){
            $updateData['certifications'] = trim($request->input('certifications'));
            }
                
                $invest=trim($request->input('is_raised_invest'));
                if(isset($invest)){
            if($request->input('is_raised_invest')!=''){
                
              $updateData['is_raised_invest'] =  intval($invest);
                
            

               if($updateData['is_raised_invest']==1)
                {
                    $updateData['invest_name'] = trim($request->input('invest_name'));
                }
                else
                {
                    $updateData['invest_name'] = "";
                }
            }
                }
        	if(!empty($request->input('legal_status'))){
            $updateData['legal_status'] = trim($request->input('legal_status'));
        	}
        	if(!empty($request->input('profile_info'))){
            $updateData['profile_info'] = trim($request->input('profile_info'));
        	}
                
                
           if(!empty($request->input('profile_info'))){
            $updateData['profile_info'] = trim($request->input('profile_info'));
        	}

            if(!empty($request->input('milestone'))){
            $updateData['milestone'] = trim($request->input('milestone'));
            }
            if(!empty($request->input('buisness_info'))){
            $updateData['buisness_info'] = trim($request->input('buisness_info'));
            }
            

            // if(!empty($request->input('speech'))){
            //  $updateData['speech'] = trim($request->input('speech'));
            // }

 
            if( $request->hasFile('speech') ) {
            

                $file = $request->file('speech');

                $file=$file[0];
       
          //Display File Name
          $file_newname=$file->getClientOriginalName();
          
       
           $file->getClientOriginalExtension();
          
       
          //Display File Real Path
          $file->getRealPath();
          
       
          //Display File Size
           $file->getSize();
          
       
          //Display File Mime Type
         $file->getMimeType();
       
          $destinationPath = public_path('/uploads/user_images');
          $file->move($destinationPath,$file->getClientOriginalName());
    
    
          $updateData['speech'] = $file_newname;
            }



    		$updateData['updated_at'] = date('Y-m-d H:i:s');

    		if( $request->hasFile('image') ) {
                // echo 'hi...'; exit;
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
	        	if(!empty($file_newname)){
	        	$updateData['image'] = $file_newname;
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



            //dd($updateData);
	    	$res = Users::where('timestamp_id', '=', $user_timestamp_id)->update($updateData);
	    	if( $res ) {

                $childs =Users::where('founder_id', '=', $company_id)->get();


                if(isset($updateData['slug'])){
                foreach ($childs as $key => $child) {
                    $update_child_data = array(
                    'slug' => $updateData['slug']
                    );

                    //dd($update_data);
                    Users::where('id', '=', $child->id)->update($update_child_data);
                }

            }

                $founders = json_decode($request->input('founder'), true);
        
                if(!empty($founders))
                {
                   
                       
                    $memberFounder = array();
                    foreach($founders as $key=>$v) {
                        
                        if(!empty($v))
                        {
                            if($v['isNewFounder']=="1")
                            {
                                $memberFounder['member_id'] = $User->id;
                                $memberFounder['name'] = isset($v['founder_name'])?$v['founder_name']:'';;
                                $memberFounder['profile'] = isset($v['founder_profile'])?$v['founder_profile']:'';




                                $memberFounder['linkdin_profile'] = isset($v['linkdin_profile'])?$v['linkdin_profile']:'';

                                //$key +=$count_hidden;

                                //echo $key;die;
                                if( isset($v['founder_img']) && !empty($v['founder_img']) ) 
                                {
                                        $folderPath = "public/uploads/founder_images/original/";

                                        $folderPath1 = "public/uploads/founder_images/thumb/";

                                        $image_parts = explode(";base64,", $v['founder_img']);
                                        $image_type_aux = explode("image/", $image_parts[0]);
                                        $image_type = $image_type_aux[1];
                                        $image_base64 = base64_decode($image_parts[1]);
                                        $file_name = uniqid() .$key. '.'.$image_type;

                                        $total_path =$folderPath .$file_name;

                                        $total_path_thumb =$folderPath1 .$file_name;

                                        file_put_contents($total_path, $image_base64);

                                        file_put_contents($total_path_thumb, $image_base64);

                                        $memberFounder['image'] = $file_name;
                                    
                                }

                                //dd($memberFounder);

                                FounderTransaction::insert($memberFounder);
                            }
                            else
                            {
                                    $memberFounder1['member_id'] = $User->id;
                                    $memberFounder1['name'] = isset($v['founder_name'])?$v['founder_name']:'';;
                                    $memberFounder1['profile'] = isset($v['founder_profile'])?$v['founder_profile']:'';

                                    


                                    $memberFounder1['linkdin_profile'] = isset($v['linkdin_profile'])?$v['linkdin_profile']:'';

                                    if($v['founder_image_updated']=="1")
                                    {
                                       
                                       $folderPath = "public/uploads/founder_images/original/";

                                        $folderPath1 = "public/uploads/founder_images/thumb/";

                                        $image_parts = explode(";base64,", $v['founder_img']);
                                        $image_type_aux = explode("image/", $image_parts[0]);
                                        $image_type = $image_type_aux[1];
                                        $image_base64 = base64_decode($image_parts[1]);
                                        $file_name = uniqid() .$key. '.'.$image_type;

                                        $total_path =$folderPath .$file_name;

                                        $total_path_thumb =$folderPath1 .$file_name;

                                        file_put_contents($total_path, $image_base64);

                                        file_put_contents($total_path_thumb, $image_base64);

                                        $memberFounder1['image'] = $file_name;
                                    }

                                
                                //dd($memberFounder1);
                                //$userDetails->update($update_data);

                                FounderTransaction::where('id', '=', $v['founder_id'])->update($memberFounder1);
                                
                            }
                            
                        }
                        
                    }

                    
                    
                }

                $buisness = json_decode($request->input('buisness'), true);
                if(!empty($buisness))
                {
                   
                        
                    $memberService = array();
                    foreach($buisness as $key=>$v1) {
                        //dd($v);
                        if(!empty($v1))
                        {
                        
                            $memberService[$key]['member_id'] = $User->id;
                            $memberService[$key]['caption'] = $v1['caption'];
                            $memberService[$key]['buisness_video'] = $v1['buisness_video'];

                            $memberService[$key]['image'] ='';

                            //echo $key;die;
                            if( isset($v1['buisness_img']) && !empty($v1['buisness_img']) ) {
                                $folderPath = "public/uploads/website_images/original/";

                                $folderPath1 = "public/uploads/website_images/thumb/";

                                $image_parts = explode(";base64,", $v1['buisness_img']);
                                $image_type_aux = explode("image/", $image_parts[0]);
                                $image_type = $image_type_aux[1];
                                $image_base64 = base64_decode($image_parts[1]);
                                $file_name = uniqid().$key. '.'.$image_type;

                                $total_path =$folderPath .$file_name;

                                $total_path_thumb =$folderPath1 .$file_name;

                                file_put_contents($total_path, $image_base64);

                                file_put_contents($total_path_thumb, $image_base64);

                                $memberService[$key]['image'] = $file_name;
                            }
                        }
                    }

                    //dd($memberService);

                    
                    if (!empty($memberService)) {
                        MemberService::where('member_id', '=', $User->id)->delete();
                        MemberService::insert($memberService);
                    }
                    
                }
                else
                {
                     MemberService::where('member_id', '=', $User->id)->delete();
                }


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

                else
                {
                    MemberBusiness::where('member_id', '=', $User->id)->delete();
                }

                $company_videoArr = json_decode($request->input('company_video'),true);
                //dd($company_videoArr);

                if(!empty($company_videoArr)){
                    $memberVideo = array();
                    foreach($company_videoArr as $v2) {

                        
                        $arr = array();

                        if(!empty($v2))
                        {
                            $arr['member_id'] = $User->id;
                            $arr['company_video'] = $v2['video_url'];
                            array_push($memberVideo, $arr);
                        }
                        
                    }
                    if (!empty($memberVideo)) {


                        /*if(count($memberVideo) >5)
                        {
                             return back()->with('msg', 'Five Company Video allo')->with('msg_class', 'alert alert-danger');
                        }*/
                        MemberVideo::where('member_id', '=', $User->id)->delete();
                        MemberVideo::insert($memberVideo);
                    }
                }
                else
                {
                    MemberVideo::where('member_id', '=', $User->id)->delete();
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
    	} else {
    			$records["details"] = array();
		        $records["message"] = "Porvidev user id is wrong";
		        $records["success"] = false;
		        $records["success_bool"] = 1;

		        echo json_encode($records);
    	}
    }

    public function userDetl(Request $request) {

       // echo "1";die;

    	$records = array();
    	$user_timestamp_id = $request->input('timestamp_id');


        $primary = Users::where('timestamp_id', '=', $user_timestamp_id)->first();


        if($primary->user_type==1)
        {
            //$records["details"] = "";
            $records["message"] = "Admin data not avialable";
            $records["success"] = false;
            $records["success_bool"] = 1;

            echo json_encode($records);
        }
        else
        {
            $company_id =company_id($primary->id);
 
            $user = DB::table('users')
                ->select('users.*','stage_name', 'ct.company_type as company_type_data')
                 ->join('stage', 'users.stage_id', '=', 'stage.id')
                 ->join('company_type as ct', 'users.company_type', '=', 'ct.id')
                 ->where("users.status", "=", 1);  
                 
                 
                  
            if(!empty($company_id)){
                 $user = $user->where("users.id", "=", $company_id); 
            }
            
            $user = $user->first();

            $user->company_pitch_file_path=  "public/uploads/user_images/".$user->speech;
            

           
            //dd($user);

            foreach ($user as $key => $value) {

             

                //dd($key);
                if (is_null($value)) {
                     $user->$key = "";
                }
            }

            
            //dd($user);
            $destinationPath = 'public/uploads/user_images/thumb/'; 
            //$original_path = $destinationPath."/original";
            //echo $original_path.$user->image;


            $memberBusiness= DB::table('member_business')
                ->select('industry_category_id', 'industry_category','industry_category.id')
                 ->join('industry_category', 'member_business.industry_category_id', '=', 'industry_category.id')
                 ->where("industry_category.status", "=", 1)
                 ->where("member_id", "=", $company_id)->get(); 

            //MemberBusiness::where('member_id', '=', $user->id)->get();
            // echo $user->id;   
         //     dd($memberBusiness);


            foreach ($memberBusiness as $key => $value3) {

                //dd($key);
                if (is_null($value3)) {
                     $memberBusiness->$key = "";
                }
            }


            $founders = DB::table('founder_transactions')->where('member_id', '=',$company_id)->orderBy('id', 'asc')->get();

            //dd($founders);


            if(count($founders))
            {
                foreach ($founders as $key1 => $value1) {

                    //dd($value1->image);

                    foreach($value1 as $key5 => $value5)
                    {
                        if (is_null($value5)) {

                            
                             $value1->$key5 = "";
                        }
                    }

                    if($value1->image != '' && $value1->image != null) 
                    {
                        //echo $value1->image;die;

                        //$image=$value1->image;

                        //dd($image);

                        $destinationPath2='public/uploads/founder_images/'; 
                        $founders[$key1]->image_url_thumb = $destinationPath2.'thumb/'.$value1->image;
                        $founders[$key1]->image_url_ori = $destinationPath2.'original/'.$value1->image;
                    }
                   
                }
            }
            
 
            $buisness = DB::table('member_services')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();


            if(count($buisness))
            {
                foreach ($buisness as $key2 => $value2) {

                   // dd($value2);

                    foreach($value2 as $key4 => $value4)
                    {
                        if (is_null($value4)) {

                            
                             $value2->$key4 = "";
                        }
                    }
                    

                    if($value2->image != '' && $value2->image != null) 
                    {
                        $destinationPath1='public/uploads/website_images/'; 

                        $buisness[$key2]->image_url_thumb = $destinationPath1.'/thumb/'.$value2->image;
                        $buisness[$key2]->image_url_ori = $destinationPath1.'original/'.$value2->image;
                    }
                   
                }
            }

            //dd($buisness);


            $company_videos = DB::table('member_videos')->where('member_id', '=',$company_id)->orderBy('id', 'asc')->get();

 
            $company_users =Users::where('users.status',1)->where('user_type',2)->where('founder_id',$company_id)->get();
            
            foreach($company_users as $key=>$company_user){
                $area_of_expertise = explode(',',$company_user->area_of_expertise);
    
                $str='';
                foreach( $area_of_expertise  as $row){
                    if($row!=''){
                      $ar =  DB::table('industry_expertise')->where('id', '=', $row)->first(); 
    
                      if(isset($ar)){
                        $str.=$ar->industry_expertise.', ';
                      }
                        
                    }
                }
                
                $company_users[$key]['area_of_expertise']=rtrim( $str, ', ');
            }


            $number_of_post=Posts::where('member_id', '=', $company_id)->where('status', '=', '1')->count();

            $number_of_reply=PostReply::where('replied_by', '=', $company_id)->where('status', '=', '1')->count();
            

            $user->image_with_path = $destinationPath.$user->image;
            $records["details"] = $user; 
            $records["users"] = $company_users;

            $records["number_of_post"] = $number_of_post; 
            $records["number_of_reply"] = $number_of_reply;

            $records['memberBusiness'] = $memberBusiness;
            $records['founders'] = $founders;
            $records['buisness'] = $buisness;
            $records['company_videos'] = $company_videos;
            //$records["image_path"] = $original_path;
            $records["message"] = "successful";
            $records["success"] = true;
            $records["success_bool"] = 1;
            //dd($records);


            echo json_encode($records);
        }

        
    }

    public function addPost(Request $request){

    	$timestamp_id = $request->input('timestamp_id');
        #$post_id = $request->input('post_id'); 



        $user = Users::where('timestamp_id', '=', $timestamp_id)->first();
        

        if(!empty($user->id)){

             $update_data = array(
                        'post_read' => 0,

                    );

            Users::where('status', '=', 1)->update($update_data);

        	$Posts = new Posts;
        	$Posts->post_title = $request->input('post_title');
        	$Posts->post_info = $request->input('post_info');
        	$Posts->member_id = $user->id;
        	$Posts->post_type = $request->input('post_type'); //2=>private, 1=>public
        	$Posts->private_member_id = $request->input('assign_to');
        	$Posts->status = '1';

            $new_time = date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes')); 

            $Posts->created_at = $new_time;

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

            $category_idsArr = $request->input('category_id');

            if(!empty($category_idsArr)){
                $category_ids = explode(',', $category_idsArr);
                }

            //dd($category_idsArr);
            if(!empty($category_ids)){
                $postCategory = array();
                foreach($category_ids as $v1) {
                    $arr = array();
                    $arr['post_id'] = $Posts->id;
                    $arr['category_id'] = $v1;
                    array_push($postCategory, $arr);
                }
                //dd($postCategory);
                if (!empty($postCategory)) {
                    PostCategory::insert($postCategory);
                }
            }

        	

            /** Push Notification  */

        $token =Users::select('deviceToken')->where('users.status',1)->where('user_type',2)->where('deviceToken','!=', null)->where('deviceToken','!=', '')->pluck('deviceToken')->toArray();;
            
        // $token = json_encode($token);
       
        $url = "https://fcm.googleapis.com/fcm/send"; //Google api endpoint to send notification

        //devicetoken saved against user in db , can be array of tokens/fcm_id/deviceToken 
        // $token = "eA42E_6FQEiYwKJaYQxI2g:APA91bFkyX3KxcgBYt-E87LSuc5b9S-qvOuiOZZt43jdXYzNYdmknRVomBi4IkSbu-M2dPZsyHnU_ZaXeXmnJ1F_3hSOhSYWHrybazN9U8P4k4gCrk4KTArKOhwt9RCLplAQziYrDPjw"; 
      
      
        ////'your server token of FCM project'
        $serverKey = "AAAA_6ilIwA:APA91bEttkTvM4mdzwCpnPGif_KPQuUG7DIsTeIYBOvAL5EOdKXIrtsXUv_eBeA-5lVl0pVVYdQflsjWKHc6nv3BUNArua20CCJA2r3kbP2sxcDjcPg3IEbeLk2JhZTqQXnfVO3hwI7-" ;
    
        $title = "Incubatee";  //title of the message to send
        $body = "A new post is now available.";  // body of the message to send
    
        $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
    
        //'to' for one instead of  registration_ids

        $arrayToSend = array('registration_ids' => $token, 'notification' => $notification,'priority'=>'high');
        $json = json_encode($arrayToSend);
    
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $serverKey;
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    
        //Send the request
        $response = curl_exec($ch);
        ob_end_clean();

    
        //Close request
        if ($response === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
    
         /** Push Notification  */

            $records = array();

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
//        		if(empty($postReply)){
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

                    if(!empty($request->input('industry_id')))
                    {
                        $category_idsArr = $request->input('category_id');

                        $category_ids = explode(',', $category_idsArr);

                        if(!empty($category_ids)){
                            $postCategory = array();
                            foreach($category_ids as $v1) {
                                $arr = array();
                                $arr['post_id'] = $post_id;
                                $arr['category_id'] = $v1;
                                array_push($postCategory, $arr);
                            }
                            if (!empty($postCategory)) {
                                PostCategory::where('post_id', '=', $post_id)->delete();
                                PostCategory::insert($postCategory);
                            }
                        }
                    }

                    

		        	$post = Posts::where('id', '=', $post_id)->first();
		        	$records["details"] = $post;
			        $records["message"] = "Post Added successfully";
			        $records["success"] = true;
			        $records["success_bool"] = 1;

			        echo json_encode($records);
//			    	}else{
//			    		$records["details"] = array();
//				        $records["message"] = "User cannot edit this post, already users comments on that";
//				        $records["success"] = false;
//				        $records["success_bool"] = 1;
//				        echo json_encode($records);
//			    	}
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


        $video_type =videoType($request->input('video_url'));


//        if($video_type=='unknown')
//        {
//            $records["details"] = array();
//            $records["message"] = "Video url not supported";
//            $records["success"] = false;
//            $records["success_bool"] = 1;
//
//            echo json_encode($records); 
//        }
//        else
//        {
            $user = Users::where('timestamp_id', '=', $timestamp_id)->first();
            $records = array();

            if(!empty($user->id) && (!empty($post->id))){
                $PostReply = new PostReply;
                $PostReply->post_id  = $post->id;
                $PostReply->replied_on = '0';
                $PostReply->replied_by = $user->id;
                $PostReply->reply_text = $request->input('reply_text');


                if(!empty($request->input('video_url')))
                {
                    $PostReply->video_url = $request->input('video_url');
                }

                

                $PostReply->status = '1';

                $PostReply->save();

                //post image upload
                $files=$request->file('image');

                if($request->hasFile('image'))
                {
                    
                    foreach ($files as $key =>$image) {

                        $real_path = $image->getRealPath();
                        $file_orgname = $image->getClientOriginalName();
                        $file_size = $image->getClientSize();
                        $file_ext = strtolower($image->getClientOriginalExtension());
                        $file_newname = "post"."_".time().$key."_".$PostReply->id.'.'.$file_ext;;
                        $destinationPath = public_path('/uploads/posts');
                        $original_path = $destinationPath."/images";
                       
                        $img = Image::make($real_path);
                       
                        $image->move($original_path, $file_newname);
                        $PostMedia = new PostMedia;
                        $PostMedia->post_reply_id = $PostReply->id;
                        $PostMedia->media_path = $file_newname;
                        $PostMedia->media_type = 'I';
                        $PostMedia->save();

                    }

                }

                //post image end

                //post video upload



                

               
                /*$files_video=$request->file('video');

                if(!is_null($files_video))
                {
                    $file_ext_video = strtolower($files_video->getClientOriginalExtension());
                
                    $file_newname_video = "post"."_".time()."_".$PostReply->id.'.'.$file_ext_video;
                    $destinationPath = public_path('/uploads/posts/videos');
                   
                    $files_video->move($destinationPath, $file_newname_video);
                    $PostMedia = new PostMedia;
                    $PostMedia->post_reply_id = $PostReply->id;
                    $PostMedia->media_path = $file_newname_video;
                    $PostMedia->media_type = 'V';
                    $PostMedia->save();
                }*/
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
//        }

        
    }


    public function addReplyofReply(Request $request){
        
        
      

        $timestamp_id = $request->input('timestamp_id');
        $comment_id = $request->input('comment_id'); 
        $comment = PostReply::where('id', '=', $comment_id)->first();

        $user = Users::where('timestamp_id', '=', $timestamp_id)->first();
//        dd($user);
        
        $records = array();

//        if($video_type=='unknown')
//        {
//            $records["details"] = array();
//            $records["message"] = "Video url not supported";
//            $records["success"] = false;
//            $records["success_bool"] = 1;
//
//            echo json_encode($records); 
//        }
//        else
//        {
            if(!empty($user->id) && (!empty($comment->id))){
            $PostReply = new PostReply;
            $PostReply->post_id  = $comment->post_id;
            $PostReply->replied_on = $comment->id;
            $PostReply->replied_by = $user->id;
            $PostReply->reply_text = $comment->reply_text;
            
            $PostReply->reply_text = $request->input('reply_text');

            if(!empty($request->input('video_url')))
            {
                $PostReply->video_url = $request->input('video_url');
            }

            $PostReply->status = '1';

            $PostReply->save();

            //post image upload
            $files=$request->file('image');

            if($request->hasFile('image'))
            {
                
                foreach ($files as $key =>$image) {

                    $real_path = $image->getRealPath();
                    $file_orgname = $image->getClientOriginalName();
                    $file_size = $image->getClientSize();
                    $file_ext = strtolower($image->getClientOriginalExtension());
                    $file_newname = "post"."_".time().$key."_".$PostReply->id.'.'.$file_ext;;
                    $destinationPath = public_path('/uploads/posts');
                    $original_path = $destinationPath."/images";
                   
                    $img = Image::make($real_path);
                   
                    $image->move($original_path, $file_newname);
                    $PostMedia = new PostMedia;
                    $PostMedia->post_reply_id = $PostReply->id;
                    $PostMedia->media_path = $file_newname;
                    $PostMedia->media_type = 'I';
                    $PostMedia->save();

                }

            }

            //post image end

            //post video upload
            

           
            /*$files_video=$request->file('video');

            if(!is_null($files_video))
            {
                $file_ext_video = strtolower($files_video->getClientOriginalExtension());
            
                $file_newname_video = "post"."_".time()."_".$PostReply->id.'.'.$file_ext_video;
                $destinationPath = public_path('/uploads/posts/videos');
               
                $files_video->move($destinationPath, $file_newname_video);
                $PostMedia = new PostMedia;
                $PostMedia->post_reply_id = $PostReply->id;
                $PostMedia->media_path = $file_newname_video;
                $PostMedia->media_type = 'V';
                $PostMedia->save();
            }*/
            $records["details"] = array();
            $records["message"] = "Comment Reply Added Successfully";
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
//        }

        
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
	        $records["message"] = "Post Comments Edited Successfully";
	        $records["success"] = true;
	        $records["success_bool"] = 1;

	        echo json_encode($records);
	    	}else{
	    	$records["details"] = array();
	        $records["message"] = "Post Comment is not editable, already someone roplied on that";
	        $records["success"] = false;
	        $records["success_bool"] = 1;
	    	}

        }else{
        	$records["details"] = array();
	        $records["message"] = "User Credentials or Post data does not match";
	        $records["success"] = false;
	        $records["success_bool"] = 1;

	        echo json_encode($records);	
        }	
    }

    public function dashboard(Request $request){

        $timestamp_id = $request->input('timestamp_id');
        

        $user = Users::where('timestamp_id', '=', $timestamp_id)->first();

        //dd($user->id);
       

        if(!empty($user->id)){
            $cur_datev=date('Y-m-d');
            $user_id=$user->id;


            
//            $postQuery1 = $postQuery1->where( function($postQuery1) use($categoryCatID) {
//                    $postQuery1 = $postQuery1->whereHas('postCategories', function ($postQuery1) use($categoryCatID) {
//                        $postQuery1->where('category_id', '=', $categoryCatID);
//
//                    });
//            });
//
//            $records['news']['count'] =$postQuery1->count();
//            $records['news']['category_id'] =1;
            
            

            
            //$DataBag['newsPost'] = $postQuery1->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(5);

            //dd($DataBag['newsPostCount']);

             //post

            $industry_category =MemberBusiness::where('member_id', '=', $user_id)->select('industry_category_id')->get();

            $category=array();

            foreach ($industry_category as $key => $value) {
                $category[$key]=$value->industry_category_id;
            }

            $postQuery6 = Posts::join('post_industry', 'post_industry.post_id', '=', 'post_master.id')->where(function ($query) use ($category) {
                    $query->whereIn('post_industry.industry_category_id', $category);
                })->orWhere(function($query) {
                    $query->where('is_bookmarked', 1);   
                });





            $records['relevantPost']['count'] =$postQuery6->count();
            $records['relevantPost']['category_id'] ='';


            //Announcements

            $postQuery2 = Posts::with(['memberInfo'])->where('is_bookmarked', '=', 1)->where('bookmark_end_date', '>=', $cur_datev)->where('status', '=', '1');

            $postQuery2 =$postQuery2->where(function($postQuery2) use($user_id){
                    $postQuery2->where('post_type', '=', '1');
                    $postQuery2->orWhere(function($postQuery2) use($user_id){
                        $postQuery2->where('post_type', '=', '2');
                        $postQuery2->where('private_member_id', '=', $user_id);
                    });
                   
                });

            $categoryCatID = 2;
            $postQuery2 = $postQuery2->where( function($postQuery2) use($categoryCatID) {
                    $postQuery2 = $postQuery2->whereHas('postCategories', function ($postQuery2) use($categoryCatID) {
                        $postQuery2->where('category_id', '=', $categoryCatID);
                    });
            });

            $records['announcement']['count'] =$postQuery2->count();

            $records['announcement']['category_id'] =2;


            //$DataBag['announcementsPost'] = $postQuery2->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(5);



            //Collaboration

            $postQuery3 = Posts::with(['memberInfo'])->where('is_bookmarked', '=', 1)->where('bookmark_end_date', '>=', $cur_datev)->where('status', '=', '1');

            $postQuery3 =$postQuery3->where(function($postQuery3) use($user_id){
                    $postQuery3->where('post_type', '=', '1');
                    $postQuery3->orWhere(function($postQuery3) use($user_id){
                        $postQuery3->where('post_type', '=', '2');
                        $postQuery3->where('private_member_id', '=', $user_id);
                    });
                   
                });


            $categoryCatID = 3;
            $postQuery3 = $postQuery3->where( function($postQuery3) use($categoryCatID) {
                    $postQuery3 = $postQuery3->whereHas('postCategories', function ($postQuery3) use($categoryCatID) {
                        $postQuery3->where('category_id', '=', $categoryCatID);
                    });
            });

            $records['collaboration']['count'] =$postQuery3->count();

            $records['collaboration']['category_id'] =3;

            //$DataBag['collaborationPost'] = $postQuery3->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(5);


            


            //Achievements

            $postQuery4 = Posts::with(['memberInfo'])->where('is_bookmarked', '=', 1)->where('bookmark_end_date', '>=', $cur_datev)->where('status', '=', '1');

            $postQuery4 =$postQuery4->where(function($postQuery4) use($user_id){
                    $postQuery4->where('post_type', '=', '1');
                    $postQuery4->orWhere(function($postQuery4) use($user_id){
                        $postQuery4->where('post_type', '=', '2');
                        $postQuery4->where('private_member_id', '=', $user_id);
                    });
                   
                });

            $categoryCatID = 4;
            $postQuery4 = $postQuery4->where( function($postQuery4) use($categoryCatID) {
                    $postQuery4 = $postQuery4->whereHas('postCategories', function ($postQuery4) use($categoryCatID) {
                        $postQuery4->where('category_id', '=', $categoryCatID);
                    });
            });

            $records['achievements']['count'] =$postQuery4->count();

            $records['achievements']['category_id'] =4;

            //$DataBag['achievementsPost'] = $postQuery4->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(5);

            //all post
            $postQuery1 = Posts::with(['memberInfo'])->where('status', '=', '1');

            
            $postQuery1 =$postQuery1->where(function($postQuery1) use($user_id) {
                    $postQuery1->where('post_type', '=', '1');
                    $postQuery1->orWhere(function($postQuery1) use($user_id){
                        $postQuery1->where('post_type', '=', '2');
                        $postQuery1->where('private_member_id', '=', $user_id);
                    });
                   
                });

            $categoryCatID = 1;


            $postQuery1 = $postQuery1->where( function($postQuery1) use($categoryCatID) {
                    $postQuery1 = $postQuery1->whereHas('postCategories', function ($postQuery1) use($categoryCatID) {
                        //$postQuery1->where('category_id', '=', $categoryCatID);

                    });
            });
 
            $records['allpost']['count'] =$postQuery1->count();
            $records['allpost']['category_id'] ='';


            //Opportunities

            $postQuery5 = Posts::with(['memberInfo'])->where('is_bookmarked', '=', 1)->where('bookmark_end_date', '>=', $cur_datev)->where('status', '=', '1');

            $postQuery5 =$postQuery5->where(function($postQuery5) use($user_id) {
                    $postQuery5->where('post_type', '=', '1');
                    $postQuery5->orWhere(function($postQuery5) use($user_id){
                        $postQuery5->where('post_type', '=', '2');
                        $postQuery5->where('private_member_id', '=', $user_id);
                    });
                   
                });

            $categoryCatID = 5;
            $postQuery5 = $postQuery5->where( function($postQuery5) use($categoryCatID) {
                    $postQuery5 = $postQuery5->whereHas('postCategories', function ($postQuery5) use($categoryCatID) {
                        $postQuery5->where('category_id', '=', $categoryCatID);
                    });
            });

            $records['opportunities']['count'] =$postQuery5->count();

            $records['opportunities']['category_id'] =5;

            //$DataBag['opportunitiesPost'] = $postQuery5->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(5);

            



           
            $records['allStartUp']['count'] =Users::where('status',1)->where('user_type',2)->whereNull('founder_id')->count();

            //dd($records);

            $records["message"] = "Dashboard Fetched Successfully";
            $records["success"] = true;
            $records["success_bool"] = 1;

            echo json_encode($records); 
            //$DataBag['allPost'] = $postQuery6->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(5);

        }else{
            $records["details"] = array();
            $records["message"] = "User credentials does not match";
            $records["success"] = false;
            $records["success_bool"] = 1;

            echo json_encode($records); 
        }   


    }

    public function categoryPost(Request $request){

        $timestamp_id = $request->input('timestamp_id');
         
        $user = Users::where('timestamp_id', '=', $timestamp_id)->first();

        //dd($user->id);
       

        if(!empty($user->id)){
            $cur_datev=date('Y-m-d');

            // $postQuery1 = Posts::with(['memberInfo'])->where('is_bookmarked', '=', 1)->where('bookmark_end_date', '>=', $cur_datev)->where('status', '=', '1');

            $postQuery1 = Posts::with(['memberInfo'])->where('status', '=', '1');
            $user_id=$user->id;

            // $postQuery1 =$postQuery1->where(function($postQuery1) use($user_id) {
            //         $postQuery1->where('post_type', '=', '1');
            //         $postQuery1->orWhere(function($postQuery1) use($user_id){
            //             $postQuery1->where('post_type', '=', '2');
            //             $postQuery1->where('private_member_id', '=', $user_id);
            //         });
                   
            //     });

            $categoryCatID = $request->input('category_id');

            if(!empty($categoryCatID))
            {
            	$postQuery1 = $postQuery1->where( function($postQuery1) use($categoryCatID) {
                    $postQuery1 = $postQuery1->whereHas('postCategories', function ($postQuery1) use($categoryCatID) {
                        $postQuery1->where('category_id', '=', $categoryCatID);

                    });
            	});

            	$result= $postQuery1->select('post_master.*')->orderBy('post_type', 'desc')->orderBy('id', 'desc')->get();
            }
            else
            {
            	$postQuery2 = Posts::with(['memberInfo'])->where('status', '=', '1');

            	$postQuery2 = $postQuery2->where( function($postQuery2) use($categoryCatID) {
                    $postQuery2 = $postQuery2->whereHas('postCategories', function ($postQuery2) use($categoryCatID) {
                        //$postQuery1->where('category_id', '=', $categoryCatID);

                    });
            	});

            	$result= $postQuery2->select('post_master.*')->orderBy('post_type', 'desc')->orderBy('id', 'desc')->get();
            }

             $i=0;

            foreach($result as $r){

                
                $postReply = PostReply::where('post_id', '=', $r->id)->count(); 
        		$result[$i]->replyCount = $postReply;


                $result[$i]->postIndustry= DB::table('post_industry')
                ->select('industry_category_id', 'industry_category')
                 ->join('industry_category', 'post_industry.industry_category_id', '=', 'industry_category.id')
                 ->where("industry_category.status", "=", 1)
                 ->where("post_id", "=", $r->id)->get();

                $favourate_count =DB::table('post_favourates')->where('post_id',$r->id)->where('member_id',$user->id)->count();


                $result[$i]->is_favorate = $favourate_count;

                $i++;

            }

             $records['details'] =$result;

            //$records['newsPostCount'] =$postQuery1->count();

            

            //dd($DataBag['newsPostCount']);

            


            

            //dd($records);

            $records["message"] = "Category Post Fetched Successfully";
            $records["success"] = true;
            $records["success_bool"] = 1;

            echo json_encode($records); 
            //$DataBag['allPost'] = $postQuery6->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(5);

        }else{
            $records["details"] = array();
            $records["message"] = "User credentials does not match";
            $records["success"] = false;
            $records["success_bool"] = 1;

            echo json_encode($records); 
        }   


    }
    public function myPost(Request $request){
        $records = array();
        $user= array();
        $timestamp_id = $request->input('timestamp_id');
        if(!empty($timestamp_id)){
        $industry_type = $request->input('industry_type');
        $user = Users::where('timestamp_id', '=', $timestamp_id)->first();
        $memberBusiness = MemberBusiness::where('member_id', '=', $user->id)->pluck('industry_category_id')->toArray();

        //$memberCategory = PostCategory::where('member_id', '=', $user->id)->pluck('category_id')->toArray();

        //$memberBusinessStr = implode(',', $memberBusiness);
        //echo $memberBusinessStr; exit;
        #dd($memberBusiness);
        $result = array();
        if(!empty($user->id)){
            $uid= $user->id;
            //echo $user->id; exit;
                   
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
                    ->where('post_master.member_id', '=',$user->id)
                    ->groupBy('post_master.id')
                    ->orderBy('post_master.id', 'desc')->get();
                    
        
                //dd($result);

                    if(!empty($result)){
                        $i=0;
                        foreach($result as $r){

                            
                            

                            $favourate_count =DB::table('post_favourates')->where('post_id',$r->id)->where('member_id',$user->id)->count();


                            $result[$i]->is_favorate = $favourate_count;




                            $postDiv = html_entity_decode($r->post_info);

                            $result[$i]->post = '<div style="line-height:3;">'.$postDiv.'<div>';

                            $postReply = PostReply::where('post_id', '=', $r->id)->count();
                            $userCom = Users::where('id', '=', $r->member_id)->first();
                            $result[$i]->replyCount = $postReply;
                            if(!empty($userCom->member_company)){
                            $result[$i]->member_company = $userCom->member_company;
                            }else{
                                $result[$i]->member_company = '';
                            }

                            // echo  $r->member_id."____".$userCom->stage_id;

                            // exit;
                            $stageArr = Stages::where('id', '=', $userCom->stage_id)->first();
                            if(!empty($stageArr)){
                            $result[$i]->stage = $stageArr->stage_name;
                            }else{
                              $result[$i]->stage = '';  
                            }




                            $result[$i]->posted_user_image = 'public/uploads/user_images/original/'.$userCom->image;
                            $result[$i]->timestamp_id = $userCom->timestamp_id;
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


                             $result[$i]->postIndustry= DB::table('post_industry')
                            ->select('industry_category_id', 'industry_category')
                             ->join('industry_category', 'post_industry.industry_category_id', '=', 'industry_category.id')
                             ->where("industry_category.status", "=", 1)
                             ->where("post_id", "=", $r->id)->get();


                             





                            
                                
                            $result[$i]->postCategory= DB::table('post_categories')
                                ->select('category_id', 'name')
                                 ->join('categories', 'post_categories.category_id', '=', 'categories.id')
                                 ->where("categories.status", "=", 1)
                                 ->where("post_id", "=", $r->id)->get();


                         //dd($result[$i]->postCategory);
                             


                             




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

    public function feedPost(Request $request){
        $records = array();
        $user= array();
        $timestamp_id = $request->input('timestamp_id');
        if(!empty($timestamp_id)){
        //$industry_type = $request->input('industry_type');
        $user = Users::where('timestamp_id', '=', $timestamp_id)->first();
        $memberBusiness = MemberBusiness::where('member_id', '=', $user->id)->pluck('industry_category_id')->toArray();

        //$memberCategory = PostCategory::where('member_id', '=', $user->id)->pluck('category_id')->toArray();

        //$memberBusinessStr = implode(',', $memberBusiness);
        //echo $memberBusinessStr; exit;
        #dd($memberBusiness);
        $result = array();
        if(!empty($user->id)){
            $uid= $user->id;
            //echo $user->id; exit;

                $industry_category =MemberBusiness::where('member_id', '=', $user->id)->select('industry_category_id')->get();

                $category=array();

                foreach ($industry_category as $key => $value) {
                    $category[$key]=$value->industry_category_id;
                }
                  
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
                ->where(function ($query) use ($category) {
                    $query->whereIn('post_industry.industry_category_id', $category);
                })->orWhere(function($query) {
                    $query->where('is_bookmarked', 1);   
                })
                ->groupBy('post_master.id')
                ->orderBy('post_master.id', 'desc')->get();
                    
        
                //dd($result);

                if(!empty($result)){
                    $i=0;
                    foreach($result as $r){

                        
                        

                        $favourate_count =DB::table('post_favourates')->where('post_id',$r->id)->where('member_id',$user->id)->count();


                        $result[$i]->is_favorate = $favourate_count;




                        $postDiv = html_entity_decode($r->post_info);

                        $result[$i]->post = '<div style="line-height:3;">'.$postDiv.'<div>';

                        $postReply = PostReply::where('post_id', '=', $r->id)->count();
                        $userCom = Users::where('id', '=', $r->member_id)->first();
                        $result[$i]->replyCount = $postReply;
                        if(!empty($userCom->member_company)){
                        $result[$i]->member_company = $userCom->member_company;
                        }else{
                            $result[$i]->member_company = '';
                        }

                        // echo  $r->member_id."____".$userCom->stage_id;

                        // exit;
                        $stageArr = Stages::where('id', '=', $userCom->stage_id)->first();
                        if(!empty($stageArr)){
                        $result[$i]->stage = $stageArr->stage_name;
                        }else{
                          $result[$i]->stage = '';  
                        }




                        $result[$i]->posted_user_image = 'public/uploads/user_images/original/'.$userCom->image;
                        $result[$i]->timestamp_id = $userCom->timestamp_id;
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


                         $result[$i]->postIndustry= DB::table('post_industry')
                        ->select('industry_category_id', 'industry_category')
                         ->join('industry_category', 'post_industry.industry_category_id', '=', 'industry_category.id')
                         ->where("industry_category.status", "=", 1)
                         ->where("post_id", "=", $r->id)->get();


                         





                        
                            
                        $result[$i]->postCategory= DB::table('post_categories')
                            ->select('category_id', 'name')
                             ->join('categories', 'post_categories.category_id', '=', 'categories.id')
                             ->where("categories.status", "=", 1)
                             ->where("post_id", "=", $r->id)->get();


                     //dd($result[$i]->postCategory);
                         


                         




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

    public function lastLoginPostCount(Request $request) {
        if ($request->isMethod('post')) { 
            $timestamp_id = $request->input('timestamp_id');

            if(!empty($timestamp_id)){
                $user = Users::where('timestamp_id', '=', $timestamp_id)->first();


                if(!empty($user->id))
                {
                    //echo $user->post_read;die;
                    if( $user->post_read ==1)
                    {
                        $postcount =0;
                    }
                    else
                    {
                        $postcount =DB::table('post_master')->join('users', 'users.id', '=', 'post_master.member_id')->where('post_master.created_at','>=',$user->last_login)->where('post_master.member_id','!=',$user->id)->count();
                    }
                        

                    $records["count"] =$postcount ;


                    $records["message"] = "successful";
                    $records["success"] = true;
                    $records["success_bool"] = 1;
                    echo json_encode($records);
                }

                else{
                            $records["details"] = array();
                            $records["message"] = "User id wrong";
                            $records["success"] = false;
                            $records["success_bool"] = 1;

                            echo json_encode($records);
                    }
            }


        }
    }

    public function lastLoginPost(Request $request) {
        if ($request->isMethod('post')) { 
            $timestamp_id = $request->input('timestamp_id');

            if(!empty($timestamp_id)){
        $industry_type = $request->input('industry_type');
        $user = Users::where('timestamp_id', '=', $timestamp_id)->first();
        $memberBusiness = MemberBusiness::where('member_id', '=', $user->id)->pluck('industry_category_id')->toArray();

        //echo $user->id;die;

        //$memberCategory = PostCategory::where('member_id', '=', $user->id)->pluck('category_id')->toArray();

        //$memberBusinessStr = implode(',', $memberBusiness);
        //echo $memberBusinessStr; exit;
        #dd($memberBusiness);
        $result = array();
        if(!empty($user->id)){
            $uid= $user->id;
            $last_login =$user->last_login;

            $current_login=$user->current_login;

            if($user->post_read ==0)
            {
                   
               /* $postcount =DB::table('post_master')->join('users', 'users.id', '=', 'post_master.member_id')->where('post_master.created_at','<',$user->current_login)->where('post_master.created_at','>=',$user->last_login)->count();

                echo $postcount;die;*/
                   if($industry_type == '1'){


                    




                        $result = DB::table('post_master')
                            ->select('post_master.*')
                             ->join('post_industry', 'post_master.id', '=', 'post_industry.post_id')->where('post_master.created_at','>=',$user->last_login)->where('post_master.member_id','!=',$user->id)
                         ->groupBy('post_master.id')
                        ->orderBy('post_master.id', 'desc')->get();
                    }else{
                        $result = DB::table('post_master')
                            ->select('post_master.*')
                            ->join('post_industry', 'post_master.id', '=', 'post_industry.post_id')->where('post_master.created_at','>=',$user->last_login)
                       
                        ->groupBy('post_master.id')
                        ->orderBy('post_master.id', 'desc')->get();
                    }

            }
        
                //dd(count($result));

            if(!empty($result)){
                $i=0;
                foreach($result as $r){

                    
                     $favourate_count =DB::table('post_favourates')->where('post_id',$r->id)->where('member_id',$user->id)->count();


                    $result[$i]->is_favorate = $favourate_count;


                    $postDiv = html_entity_decode($r->post_info);

                    $result[$i]->post = '<div style="line-height:3;">'.$postDiv.'<div>';

                    $postReply = PostReply::where('post_id', '=', $r->id)->count();
                    $userCom = Users::where('id', '=', $r->member_id)->first();
                    $result[$i]->replyCount = $postReply;
                    if(!empty($userCom->member_company)){
                    $result[$i]->member_company = $userCom->member_company;
                    }else{
                        $result[$i]->member_company = '';
                    }

                    // echo  $r->member_id."____".$userCom->stage_id;

                    // exit;
                    $stageArr = Stages::where('id', '=', $userCom->stage_id)->first();
                    if(!empty($stageArr)){
                    $result[$i]->stage = $stageArr->stage_name;
                    }else{
                      $result[$i]->stage = '';  
                    }




                    $result[$i]->posted_user_image = 'public/uploads/user_images/original/'.$userCom->image;
                    $result[$i]->timestamp_id = $userCom->timestamp_id;
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


                     $result[$i]->postIndustry= DB::table('post_industry')
                    ->select('industry_category_id', 'industry_category')
                     ->join('industry_category', 'post_industry.industry_category_id', '=', 'industry_category.id')
                     ->where("industry_category.status", "=", 1)
                     ->where("post_id", "=", $r->id)->get();


                     





                    
                        
                    $result[$i]->postCategory= DB::table('post_categories')
                        ->select('category_id', 'name')
                         ->join('categories', 'post_categories.category_id', '=', 'categories.id')
                         ->where("categories.status", "=", 1)
                         ->where("post_id", "=", $r->id)->get();


                 //dd($result[$i]->postCategory);
                     


                     




                    $i++;
                }
            }            
        //dd($result);

            $update_data_one = array(
                        'post_read' => 1,

                    );

            Users::where('id', '=',$user->id)->update($update_data_one);
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
}


        }
    }


    public function addFavourate(Request $request) {
        $records = array();
        $user_timestamp_id = $request->input('timestamp_id');
        $user = Users::where('timestamp_id', '=', $user_timestamp_id)->first();

        $post_id =$request->input('post_id');

        $is_favorate =$request->input('is_favourate');

        if( !empty($user) ) {


            if($is_favorate==1)
            {
                //remove favourite

                DB::table('post_favourates')->where('member_id', '=', $user->id)->where('post_id', '=', $post_id)->delete();

                 $records["message"] = "Favourate removed successfully.";
            }
            else
            {
                //add favourite

                $favourite =array();

                $favourite['member_id'] = $user->id;
                $favourite['post_id'] = $post_id;

                DB::table('post_favourates')->insert($favourite);

                $records["message"] = "Favourate added successfully.";

            }
            $records["details"] = array();
           
            $records["success"] = true;
            $records["success_bool"] = 1;

            echo json_encode($records);
           
        } else {
                $records["details"] = array();
                $records["message"] = "Provided user id is wrong";
                $records["success"] = false;
                $records["success_bool"] = 1;

                echo json_encode($records);
        }
    }

    public function postFavourate(Request $request){

        $timestamp_id = $request->input('timestamp_id');
        

        $user = Users::where('timestamp_id', '=', $timestamp_id)->first();

        //dd($user->id);
       

        if(!empty($user->id)){
            
            $postQuery1 = Posts::join('post_favourates', 'post_favourates.post_id', '=', 'post_master.id')->where('post_favourates.member_id', '=', $user->id)->with(['memberInfo'])->where('post_master.status', '=', '1');

            $user_id=$user->id;

            $postQuery1 =$postQuery1->where(function($postQuery1) use($user_id) {
                    $postQuery1->where('post_type', '=', '1');
                    $postQuery1->orWhere(function($postQuery1) use($user_id){
                        $postQuery1->where('post_type', '=', '2');
                        $postQuery1->where('private_member_id', '=', $user_id);
                    });
                   
                });

            

            //$records['newsPostCount'] =$postQuery1->count();

            $result= $postQuery1->select('post_master.*')->orderBy('post_type', 'desc')->orderBy('post_master.id', 'desc')->get();

            //echo count($result);die;

            $i=0;

            foreach($result as $r){

                
                //echo $r->id;die;

                $favourate_count =DB::table('post_favourates')->where('post_id',$r->id)->where('member_id',$user->id)->count();


                $result[$i]->is_favorate = $favourate_count;

                $i++;

            }

             $records['details'] =$result;


            //dd($DataBag['newsPostCount']);

            


            

            //dd($records);

            $records["message"] = "Faviurate Post Fetched Successfully";
            $records["success"] = true;
            $records["success_bool"] = 1;

            echo json_encode($records); 
            //$DataBag['allPost'] = $postQuery6->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(5);

        }else{
            $records["details"] = array();
            $records["message"] = "User credentials does not match";
            $records["success"] = false;
            $records["success_bool"] = 1;

            echo json_encode($records); 
        }   


    }

    public function addUser1(Request $request)
    {
        
        
        $records = array();
  
        $timestamp_id = $request->input('timestamp_id');
        

        $user = Users::where('timestamp_id', '=', $timestamp_id)->first();

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

        $Users->user_type    =2;
      
        $Users->contact_name = trim($request->input('contact_name'));
       
        $Users->email_id = trim($request->input('email_id'));
        $Users->password = md5(trim($request->input('password')));

        $Users->mobile = trim($request->input('phone'));

        $Users->designation = trim($request->input('designation'));
        $Users->linkedIn = trim($request->input('linkedIn'));

        $area_of_expertise = $request->input('area_of_expertise');

        // $str_expertise='';
         
        // if(isset($area_of_expertise) && $area_of_expertise!=''){
         
        //     foreach($area_of_expertise as $row){
        //         $str_expertise=$str_expertise.$row.',';
        //     } 
        // }

        $Users->area_of_expertise =$area_of_expertise;

        $Users->founder_id = company_id($user->id);

        $Users->slug = $user->slug;

        $Users->created_by = $user->id;


        $Users->last_login =date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));



        if( $request->hasFile('image') ) {

            $image = $request->file('image');
            $image=$image[0];
            $real_path = $image->getRealPath();
            $file_orgname = $image->getClientOriginalName();
            $file_size = $image->getClientSize();
            $file_ext = strtolower($image->getClientOriginalExtension());
            $file_newname = "user"."_".time().".".$file_ext;

            $destinationPath = public_path('/uploads/user_images');
            $original_path = $destinationPath."/original";
            $thumb_path = $destinationPath."/thumb";
            
            $img = Image::make($real_path);
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumb_path.'/'.$file_newname);

            $image->move($original_path, $file_newname);
            

            $Users->image = $file_newname;
        }



        //dd(Auth::user());
        //$Users->created_by = Auth::user()->id;

        if( $Users->save() ) {
            
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

    public function addUser(Request $request)
    {
        
        
        $records = array();
  //    $request->validate([
            
  //           'email_id' => 'required|email|unique:users,email_id'
        // ],[
        
        //  'email_id.unique' => 'This Email-id Already Exist, Try Another.'
        // ]);

        $timestamp_id = $request->input('timestamp_id');
        

        $user = Users::where('timestamp_id', '=', $timestamp_id)->first();

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

        $Users->user_type    =2;
      
        $Users->contact_name = trim($request->input('contact_name'));
       
        $Users->email_id = trim($request->input('email_id'));
        $Users->password = md5(trim($request->input('password')));

        $Users->founder_id = company_id($user->id);

        $Users->slug = $user->slug;

        $Users->created_by = $user->id;


        $Users->last_login =date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));

        //dd(Auth::user());
        //$Users->created_by = Auth::user()->id;

        if( $Users->save() ) {
            
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

    public function changePassword(Request $request)
    {
        $timestamp_id = $request->input('timestamp_id');
        

        $user = Users::where('timestamp_id', '=', $timestamp_id)->first();


        $password = md5(trim($request->input('password')));
        //$userID = Auth::user()->id;
        $Users = Users::findorFail($user->id);
        $Users->password = $password;
        if ($Users->save()) {
             $records["details"] = array();    
            $records["message"] = "Password changed successfuly.";
            $records["success"] = true;
            $records["success_bool"] = 1;

            echo json_encode($records);
        }
        else
        {
            $records["details"] = array();
            $records["message"] = "Sorry! Something Went Wrong.";
            $records["success"] = false;
            $records["success_bool"] = 0;

            echo json_encode($records);
        }
        
    }

    public function startupList(Request $request){

        $timestamp_id = $request->input('timestamp_id');
         
        $user = Users::where('timestamp_id', '=', $timestamp_id)->first();

        
 
        if(!empty($user->id)){
            
            $users =Users::where('status',1)->where('user_type',2)->whereNull('founder_id')->orderBy('users.member_company', 'asc')->get();

            $DataBag = array();

            //dd($users);

            foreach ($users as $key => $user) {
                $company_id =$user->id;
  
                $DataBag[$key]['memberInfo'] = Users::join('stage', 'stage.id', '=', 'users.stage_id')->where('users.id', '=', $company_id)->select('users.member_company','users.image','stage.stage_name','users.id','users.timestamp_id','users.member_spec')->get();

                $alllinfo=$DataBag[$key]['memberInfo']->toArray();

                
                foreach ($alllinfo as $key1 => $value) {

                    //dd($value);
                    $DataBag[$key]['memberInfo'][$key1]['profile_image']='public/uploads/user_images/thumb/'.$value['image'];
                    $DataBag[$key]['memberInfo'][$key1]['industry'] =MemberBusiness::join('industry_category', 'industry_category.id', '=', 'member_business.industry_category_id')->where('member_business.member_id',$value['id'])->select('industry_category.industry_category AS name')->get();
                }

               // dd($DataBag[$key]['memberInfo']->toArray());

                $DataBag[$key]['number_of_post']=Posts::where('member_id', '=', $company_id)->where('status', '=', '1')->count();

                $DataBag[$key]['number_of_reply']=PostReply::where('replied_by', '=', $company_id)->where('status', '=', '1')->count();


                $DataBag[$key]['founders'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();

                $DataBag[$key]['founderscount'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->count();

 
            }

             $records['startups'] =$DataBag;


            //dd($DataBag['newsPostCount']);

             

            //dd($records);

            $records["message"] = "StartUps Fetched Successfully";
            $records["success"] = true;
            $records["success_bool"] = 1;

            echo json_encode($records); 
            //$DataBag['allPost'] = $postQuery6->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(5);

        }else{
            $records["details"] = array();
            $records["message"] = "User credentials does not match";
            $records["success"] = false;
            $records["success_bool"] = 1;

            echo json_encode($records); 
        }   


    }


    public function test(Request $request)
    {
      

        $founders = json_decode($request->input('founder'), true);
        //dd($founders);
        if(!empty($founders))
        {
           
                
            $memberFounder = array();
            foreach($founders as $key=>$v) {
                //dd($v);
                if(!empty($v))
                {
                    if($v['isNewFounder']==1)
                    {
                        $memberFounder['member_id'] = 894;
                        $memberFounder['name'] = $v['founder_name'];
                        $memberFounder['profile'] = $v['founder_profile'];

                        $memberFounder['linkdin_profile'] = $v['linkdin_profile'];

                        //$key +=$count_hidden;

                        //echo $key;die;
                        if( isset($v['founder_img']) && !empty($v['founder_img']) ) 
                        {
                                $folderPath = "public/uploads/founder_images/original/";

                                $folderPath1 = "public/uploads/founder_images/thumb/";

                                $image_parts = explode(";base64,", $v['founder_img']);
                                $image_type_aux = explode("image/", $image_parts[0]);
                                $image_type = $image_type_aux[1];
                                $image_base64 = base64_decode($image_parts[1]);
                                $file_name = uniqid() . '.'.$image_type;

                                $total_path =$folderPath .$file_name;

                                $total_path_thumb =$folderPath1 .$file_name;

                                file_put_contents($total_path, $image_base64);

                                file_put_contents($total_path_thumb, $image_base64);

                                $memberFounder['image'] = $file_name;
                            
                        }

                        //dd($memberFounder);

                        FounderTransaction::insert($memberFounder);
                    }
                    
                }
                else
                {
                        $memberFounder['member_id'] = 894;
                        $memberFounder['name'] = $v['founder_name'];
                        $memberFounder['profile'] = $v['founder_profile'];
                        $memberFounder['linkdin_profile'] = $v['linkdin_profile'];

                        if($v['founder_image_updated']==1)
                        {
                           $folderPath = "public/uploads/founder_images/original/";

                            $folderPath1 = "public/uploads/founder_images/thumb/";

                            $image_parts = explode(";base64,", $v['founder_img']);
                            $image_type_aux = explode("image/", $image_parts[0]);
                            $image_type = $image_type_aux[1];
                            $image_base64 = base64_decode($image_parts[1]);
                            $file_name = uniqid() . '.'.$image_type;

                            $total_path =$folderPath .$file_name;

                            $total_path_thumb =$folderPath1 .$file_name;

                            file_put_contents($total_path, $image_base64);

                            file_put_contents($total_path_thumb, $image_base64);

                            $memberFounder['image'] = $file_name;
                        }

                    
                    //dd($update_data);
                    //$userDetails->update($update_data);

                    FounderTransaction::where('id', '=', 1)->update($memberFounder);
                    
                }
            }

            
            
        }

       $records["details"] = array();    
        $records["message"] = "Password changed successfuly.";
        $records["success"] = true;
        $records["success_bool"] = 1;

        echo json_encode($records);
    }

    public function sendMessage(Request $request)
    {

        $timestamp_id = $request->input('timestamp_id');
         
        $user = Users::where('timestamp_id', '=', $timestamp_id)->first();
 
        $message = new Message;
        $message->sender_id = $user->id;
        $message->receiver_id = trim($request->input('receiver_id'));
        $message->message = trim($request->input('message'));
 
        if( $message->save() ) {


        $r_user=trim($request->input('receiver_id'));
 

        $Users = Users::findOrFail($r_user); 

        if(isset($Users) && $Users->email_id!=''){
             
            $content = '<p>Hi '.$Users->first_name.'</p>

            <p>You have received a new message in IIMCIP Collaboration Platform. Please login to the application to view the message.</p>
             
            <p>Thanks & Regards,</p>
            Team IIMCIP ';
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: <no-reply@iimcip.net>' . "\r\n";
            // $headers .= 'Cc: karmickdeveloper77@gmail.com' . "\r\n";

            mail($Users->email_id,'You have a new message',$content,$headers);
     
 
        }

             /** Push Notification  */

        $token =Users::select('deviceToken')->where('id',$r_user)->where('users.status',1)->where('user_type',2)->where('deviceToken','!=', null)->where('deviceToken','!=', '')->pluck('deviceToken')->toArray();;
            
        
        $url = "https://fcm.googleapis.com/fcm/send"; //Google api endpoint to send notification

        
        ////'your server token of FCM project'
        $serverKey = "AAAA_6ilIwA:APA91bEttkTvM4mdzwCpnPGif_KPQuUG7DIsTeIYBOvAL5EOdKXIrtsXUv_eBeA-5lVl0pVVYdQflsjWKHc6nv3BUNArua20CCJA2r3kbP2sxcDjcPg3IEbeLk2JhZTqQXnfVO3hwI7-" ;
    
        $title = "Incubatee";  //title of the message to send
        $body = "You have a new message.";  // body of the message to send
    
        $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => '1');

        $mtype="MSG";
        $data=array("notification_type"=>$mtype);  //data payload, this is extra data to be sent  
 
    
        //'to' for one instead of  registration_ids

        $arrayToSend = array('registration_ids' => $token, 'notification' => $notification,'priority'=>'high', 'data'=>$data);
        $json = json_encode($arrayToSend);
    
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $serverKey;
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    
        //Send the request
        $response = curl_exec($ch);
        ob_end_clean();

    
        //Close request
        if ($response === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
    
         /** Push Notification  */

           
        $records["message"] = "Message Sent Succesfully.";
        $records["success"] = true;
        $records["success_bool"] = 1;

        echo json_encode($records);
 
        } else {
             
            $records["message"] = "Sorry! Something Went Wrong.";
            $records["success"] = false;
            $records["success_bool"] = 0;

            echo json_encode($records);
        }
 
    }
    
    public function getMessage(Request $request)
    {

        $timestamp_id = $request->input('timestamp_id'); 
        $user = Users::where('timestamp_id', '=', $timestamp_id)->first(); 
        
        $sender_id=$user->id; 

        $receiver_id= $request->input('receiver_id');
        $records  = array();
         
        $messages = DB::select("select `users`.*,`message`.`created_at` as mtime, `message`.`message`, `message`.`receiver_id`,`message`.`sender_id` from `message` inner join `users` on `users`.`id` = `message`.`sender_id` where (`message`.`sender_id` = $sender_id and `message`.`receiver_id` = $receiver_id) or (`message`.`sender_id` = $receiver_id and `message`.`receiver_id` = $sender_id) 
        order by message.id desc");

        DB::query("update message set read_status=1 where (`message`.`sender_id` = $sender_id and `message`.`receiver_id` = $receiver_id) or (`message`.`sender_id` = $receiver_id and `message`.`receiver_id` = $sender_id) 
        ");
        
        // $records['user'] = Users::where('id',$receiver_id)->first();

        $records['messages']= array_values($messages);

        $records["message"] = "Messages Fetched Successfully";
        $records["success"] = true;
        $records["success_bool"] = 1;

        echo json_encode($records); 
    
    }

    public function myMessages(Request $request)
    { 
        $timestamp_id = $request->input('timestamp_id'); 
        $user = Users::where('timestamp_id', '=', $timestamp_id)->first(); 
        // $records  = array();
        $sender_id=$user->id; 
        // $records['sender_id']=$sender_id; 
        $records['users'] = Message::select('message.*')->where('message.sender_id',$sender_id)->orWhere('message.receiver_id', '=', $sender_id)
         ->orderBy('id','desc')->get()->toArray();

        $a=array();    

        foreach ($records['users'] as $key => $user) {
 
            if($sender_id==$user['sender_id']){
                $records['users'][$key]['user']=Users::where('id',$user['receiver_id'])->get()->toArray();
            }
            else{
                $records['users'][$key]['user']=Users::where('id',$user['sender_id'])->get()->toArray();
            }
 
            if(!in_array($records['users'][$key]['user'][0]['id'], $a)){ 
                array_push($a,$records['users'][$key]['user'][0]['id']); 

                $image=   $records['users'][$key]['user'][0]['image'];

                $records['users'][$key]['user'][0]['image'] =  asset('public/front_end/images/profile-pic.png');
                if( $image != null &&  $image!='') {
                    $records['users'][$key]['user'][0]['image'] = asset('public/uploads/user_images/thumb/'.$image);
                    
                }
            }
            else{
                unset($records['users'][$key]);
            }
  
          
      
        }

        $records['users']= array_values($records['users']);

        $records["message"] = "Messages Fetched Successfully";
        $records["success"] = true;
        $records["success_bool"] = 1;

        echo json_encode($records); 
    }

    public function getareaofexpertise()
    { 
        
        $records['users'] = IndustryExpertise::select('industry_expertise.*')
         ->orderBy('id','desc')->get()->toArray();

         
        $records['users']= array_values($records['users']);

        $records["message"] = "Area of Expertise Fetched Successfully";
        $records["success"] = true;
        $records["success_bool"] = 1;

        echo json_encode($records); 
    }

    public function getcompanytypes()
    { 
 
        $records['users'] = CompanyType::select('company_type.*')
         ->orderBy('id','desc')->get()->toArray();
 
        $records['users']= $records['users'];

        $records["message"] = "Company Type Fetched Successfully";
        $records["success"] = true;
        $records["success_bool"] = 1;

        echo json_encode($records); 
    }


    public function sendMail(Request $request)
    {
        $user_timestamp_id = $request->input('timestamp_id');
    	$primary = Users::where('timestamp_id', '=', $user_timestamp_id)->first();

        $company_id =company_id($primary->id);

        $User = Users::where('id', '=', $company_id)->first();

        $user_timestamp_id =$User->timestamp_id;

     
    	$email = $User->email_id;

        // $email = $request->input('email');
        $title = $request->input('subject');
        $info = $request->input('message');
  
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: <info@iimcip.net>' . "\r\n";
        $headers .= 'Cc: info@iimcip.net' . "\r\n";

        mail($email,$title,$info,$headers);
 
      
        $records["message"] = "Mail Sent Successfully..!";
        $records["success"] = true;
        $records["success_bool"] = 1;

        echo json_encode($records); 

    }

}
