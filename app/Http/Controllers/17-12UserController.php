<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Users;
use App\Models\Stages;
use App\Models\IndustryCategories;
use App\Models\MemberBusiness;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Image;
use App\Models\FounderTransaction;
use App\Models\MemberService;
use App\Models\MemberVideo;
use Auth;
use DB;
use Session;
use PHPMailer\PHPMailer\PHPMailer;
use Carbon\Carbon;
use Validator;


class UserController extends Controller
{

    public function index() {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'userManagement';
        $dataBag['childMenu'] = 'usersList';
        $dataBag['userList'] = $userList= Users::whereNull('founder_id')->whereIn('user_type', [2])
        ->orderBy('member_company', 'asc')->get();

        //dd($dataBag['userList']->toArray());
        if(!empty($userList)){
            $i=0; $memberBusiness= array();
            foreach($userList as $user){

                //dd($user);
            $memberBusiness= DB::table('member_business')
            ->select('industry_category')
             ->join('industry_category', 'member_business.industry_category_id', '=', 'industry_category.id')
             ->where("industry_category.status", "=", 1)
             ->where("member_id", "=", $user->id)->get();

             $dataBag['userList'][$i]['memberBusiness']= ""; 
             $str = '';
             if(!empty($memberBusiness)){
                $k=0;
                foreach($memberBusiness as $mb){
                    if($k==0){
                        $str .= $mb->industry_category;
                    }else{
                        $str .= ', '.$mb->industry_category; 
                    }
                   $k++;
                }   
             }
             $dataBag['userList'][$i]['memberBusiness']=$str;
             $i++;
            }

        }

        //dd($dataBag['userList']->toArray());
        return view('dashboard.users.index', $dataBag);
    }

    public function subUserList($id) {


        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'userManagement';
        $dataBag['childMenu'] = 'usersList';
        $dataBag['userList'] = $userList= Users::where('status', '!=', '3')->whereIn('user_type', [2])->where('founder_id', '=', $id)
        ->orderBy('id', 'desc')->get();

        $dataBag['user_id'] =$id;

        //dd($dataBag);
        return view('dashboard.users.sub_user', $dataBag);
    }

    public function createUser() {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'userManagement';
        $dataBag['childMenu'] = 'createUser';
        $dataBag['stageList'] = Stages::orderBy('id', 'desc')->get();
        $dataBag['industryList'] = IndustryCategories::orderBy('industry_category.industry_category', 'asc')->get();
        return view('dashboard.users.create', $dataBag);    
    }

    public function createSubUser($id) {


        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'userManagement';
        $dataBag['childMenu'] = 'usersList';
        $dataBag['stageList'] = Stages::orderBy('id', 'desc')->get();
        $dataBag['industryList'] = IndustryCategories::orderBy('id', 'desc')->get();
        $dataBag['user_id'] =$id;
        return view('dashboard.users.create_sub', $dataBag);    
    }

    public function saveUser(Request $request) {

        $request->validate([
            
            'email_id' => 'required|email|unique:users,email_id'
        ],[
        
            'email_id.unique' => 'This Email-id Already Exist, Try Another.'
        ]);


        $Users = new Users;
        $Users->timestamp_id = md5(microtime(TRUE));
        $Users->member_company = trim($request->input('member_company'));
        $Users->slug  =Str::slug($Users->member_company, '-');
        $Users->contact_name = trim($request->input('contact_name'));
        $Users->email_id = trim($request->input('email_id'));
        $Users->contact_no = trim($request->input('contact_no'));
        $Users->password = md5(trim($request->input('password')));
        $Users->user_type = 2;

        $Users->milestone = trim($request->input('milestone'));

        $Users->member_spec = trim($request->input('member_spec'));
        $Users->member_looking = trim($request->input('member_looking'));
        $Users->member_help = trim($request->input('member_help'));
        $Users->achievements = trim($request->input('achievements'));
        $Users->certifications = trim($request->input('certifications'));

        $Users->is_raised_invest = trim($request->input('is_raised_invest'));

        if($Users->is_raised_invest==1)
        {
            $Users->invest_name = trim($request->input('invest_name'));
        }
        else
        {
            $Users->invest_name = "";
        }

        $Users->speech = trim($request->input('speech'));


        if( $request->hasFile('image') ) {

                $image = $request->file('image');
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



        $Users->mobile = trim($request->input('mobile'));
        $Users->stage_id = trim($request->input('stage_id'));
        $Users->country = trim($request->input('country'));
        $Users->city = trim($request->input('city'));
        $Users->website = trim($request->input('website'));
        $Users->legal_status = trim($request->input('legal_status'));
        $Users->profile_info = trim($request->input('profile_info'));
        $Users->created_by = Auth::user()->id;

        $Users->last_login =date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));

        //$Users->current_login =date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));

        //dd($Users);


        if( $Users->save() ) {
            //founder
                $founder_name = $request->input('founder_name');
                $founder_profile = $request->input('founder_profile');

                $founder_img=$request->file('founder_img');


                $user_id = $Users->id;

                $new_time = date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));
                
                $update_data = array(
                    'current_login' => $new_time,
                );
                Users::where('id', '=', $user_id)->update($update_data);


                //dd($founder_img);

                if(!empty($founder_name))
                {
                    
                    $memberFounder = array();
                    foreach($founder_name as $key=>$v) {

                        if(!empty($v))
                        {
                        
                            $memberFounder[$key]['member_id'] = $Users->id;
                            $memberFounder[$key]['name'] = $v;
                            $memberFounder[$key]['profile'] = $founder_profile[$key];

                            //$key +=$count_hidden;

                            //echo $key;die;
                            if( $founder_img[$key] ) {
                                $image = $founder_img[$key];
                                $real_path = $image->getRealPath();
                                $file_orgname = $image->getClientOriginalName();
                                $file_size = $image->getClientSize();
                                $file_ext = strtolower($image->getClientOriginalExtension());
                                $file_newname = "founder"."_".time().$key.".".$file_ext;
                                $destinationPath = public_path('/uploads/founder_images');
                                $original_path = $destinationPath."/original";
                                $thumb_path = $destinationPath."/thumb";
                                
                                $img = Image::make($real_path);
                                $img->resize(150, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->save($thumb_path.'/'.$file_newname);

                                $image->move($original_path, $file_newname);
                                $memberFounder[$key]['image'] = $file_newname;
                            }
                        }
                    }

                    //dd($memberFounder);

                    
                    if (!empty($memberFounder)) {
                        //FounderTransaction::where('member_id', '=', $Users->id)->delete();
                        FounderTransaction::insert($memberFounder);
                    }
                
                
                }

                 //service
                $buisness_caption = $request->input('buisness_caption');

           
                $buisness_website = $request->input('buisness_website');

                $buisness_img=$request->file('buisness_img');

                $buisness_video=$request->input('buisness_video');

                //dd($founder_img);

                //$buisness_img_hidden=$request->input('buisness_img_hidden');

                //echo count($buisness_caption);die;
                if(count($buisness_caption))
                {
                    
                    $memberService = array();
                    foreach($buisness_caption as $key=>$v) {

                        
                        if(!empty($v))
                        {
                            $memberService[$key]['member_id'] = $Users->id;
                            $memberService[$key]['caption'] = $v;
                            $memberService[$key]['buisness_video'] = $buisness_video[$key];
                            //$memberService[$key]['website'] = $buisness_website[$key];

                            //$key +=$count_hidden;

                            //echo $key;die;
                            if( $buisness_img[$key] ) {
                                $image = $buisness_img[$key];
                                $real_path = $image->getRealPath();
                                $file_orgname = $image->getClientOriginalName();
                                $file_size = $image->getClientSize();
                                $file_ext = strtolower($image->getClientOriginalExtension());
                                $file_newname = "service"."_".time().$key.".".$file_ext;
                                $destinationPath = public_path('/uploads/website_images');
                                $original_path = $destinationPath."/original";
                                $thumb_path = $destinationPath."/thumb";
                                
                                $img = Image::make($real_path);
                                $img->resize(150, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->save($thumb_path.'/'.$file_newname);

                                $image->move($original_path, $file_newname);
                                $memberService[$key]['image'] = $file_newname;
                            }
                        }
                    }

                    //dd($memberFounder);

                    
                    if (!empty($memberService)) {
                        //MemberService::where('member_id', '=', $Users->id)->delete();
                        MemberService::insert($memberService);
                    }
                    
                }
            $industry_ids = $request->input('industry_id');
            if(!empty($industry_ids)){
                foreach($industry_ids as $ii){
                    $member_id = $Users->id;
                    $MemberBusiness = new MemberBusiness;
                    $MemberBusiness->industry_category_id = $ii;
                    $MemberBusiness->status = 1;
                    $MemberBusiness->member_id = $member_id;
                    $MemberBusiness->save();
                    }
                }

            $company_videoArr = $request->input('company_video');
            //dd($company_videoArr);

            if(!empty($company_videoArr)){
                $memberVideo = array();
                foreach($company_videoArr as $v1) {
                    $arr = array();

                    if(!empty($v1))
                    {
                        $arr['member_id'] = $Users->id;
                        $arr['company_video'] = $v1;
                        array_push($memberVideo, $arr);
                    }
                    
                }
                if (!empty($memberVideo)) {


                    if(count($memberVideo) >5)
                    {
                         return back()->with('msg', 'Five Company Video allo')->with('msg_class', 'alert alert-danger');
                    }
                    MemberVideo::where('member_id', '=', $Users->id)->delete();
                    MemberVideo::insert($memberVideo);
                }
            }




            return back()->with('msg_class', 'alert alert-success')
            ->with('msg', 'New User Created Succesfully.');

        } else {
            return back()->with('msg_class', 'alert alert-danger')
            ->with('msg', 'Something Went Wrong.');
        }
    }

     public function saveSubUser(Request $request) {

        $request->validate([
            
            'email_id' => 'required|email|unique:users,email_id'
        ],[
        
            'email_id.unique' => 'This Email-id Already Exist, Try Another.'
        ]);


        $Users = new Users;
        $Users->timestamp_id = md5(microtime(TRUE));


        $user_result =DB::table('users')->where('id', '=', $request->input('founder_id'))->select('member_company')->first();

        $Users->contact_name = trim($request->input('contact_name'));

        $Users->member_company = $user_result->member_company;
      
        $Users->email_id = trim($request->input('email_id'));
        //$Users->contact_no = trim($request->input('contact_no'));
        $Users->password = md5(trim($request->input('password')));

        $Users->founder_id = trim($request->input('founder_id'));

        $Users->slug = $user_result->slug;
        $Users->user_type = 2;



       // $Users->mobile = trim($request->input('mobile'));
        
        $Users->created_by = Auth::user()->id;


        $Users->last_login =date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));


        if( $Users->save() ) {

            return back()->with('msg_class', 'alert alert-success')
            ->with('msg', 'New Sub User Created Succesfully.');

        } else {
            return back()->with('msg_class', 'alert alert-danger')
            ->with('msg', 'Something Went Wrong.');
        }
    }

    public function editUser($user_timestamp_id) {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'userManagement';
        $dataBag['stageList'] = Stages::orderBy('id', 'desc')->get();
        $dataBag['industryList'] = IndustryCategories::orderBy('id', 'desc')->get();


        $user = Users::where('timestamp_id', '=', $user_timestamp_id)->first();
        $dataBag['user_data'] = $user;

        //dd($dataBag['user_data']);



        $dataBag['founders'] = DB::table('founder_transactions')->where('member_id', '=', $user->id)->orderBy('id', 'asc')->get();

        //dd($DataBag['founders']);

        $dataBag['founderscount'] = DB::table('founder_transactions')->where('member_id', '=', $user->id)->count();

         $dataBag['buisness'] = DB::table('member_services')->where('member_id', '=', $user->id)->orderBy('id', 'asc')->get();

        // dd($dataBag['buisness']);

         /*foreach ($dataBag['buisness'] as $key => $c) {


             if(!empty($c->buisness_video))
              {
                 //echo $c->buisness_video;die;
                 $dataBag['buisness'][$key]->buisness_video=getYoutubeEmbedUrl($c->buisness_video);
              }
         }*/

        // echo $user->id;
        // exit;
        $dataBag['memberBusiness'] = MemberBusiness::where('member_id', '=', $user->id)->pluck('industry_category_id')->toArray();
        //dd($dataBag['memberBusiness']);

        $dataBag['company_videos'] = DB::table('member_videos')->where('member_id', '=', $user->id)->orderBy('id', 'asc')->get();

        /*foreach ($dataBag['company_videos'] as $key1 => $c1) {


             if(!empty($c1->company_video))
              {
                 //echo $c->buisness_video;die;
                 $dataBag['company_videos'][$key1]->company_video=getYoutubeEmbedUrl($c1->company_video);
              }
         }*/

        //dd($dataBag['company_videos']);
        return view('dashboard.users.edit', $dataBag);
    }

    public function editSubUser($user_timestamp_id,$id) {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'userManagement';
        $dataBag['stageList'] = Stages::orderBy('id', 'desc')->get();
        $dataBag['industryList'] = IndustryCategories::orderBy('id', 'desc')->get();


        $user = Users::where('timestamp_id', '=', $user_timestamp_id)->first();
        $dataBag['user_data'] = $user;

        $dataBag['user_id'] =$id;

        /*foreach ($dataBag['company_videos'] as $key1 => $c1) {


             if(!empty($c1->company_video))
              {
                 //echo $c->buisness_video;die;
                 $dataBag['company_videos'][$key1]->company_video=getYoutubeEmbedUrl($c1->company_video);
              }
         }*/

        //dd($dataBag['company_videos']);
        return view('dashboard.users.edit_sub', $dataBag);
    }

    public function updateUser(Request $request, $user_timestamp_id) {

        $User = Users::where('timestamp_id', '=', $user_timestamp_id)->first();
        if( !empty($User) ) {

            $request->validate([
            
            'email_id' => 'required|email|unique:users,email_id,'.$User->id
            ],[
            
                'email_id.unique' => 'This Email-id Already Exist, Try Another.'
            ]);

            $updateData = array();
            $updateData['member_company'] = trim($request->input('member_company'));

            $updateData['slug'] = Str::slug($updateData['member_company'], '-');

            $updateData['contact_name'] = trim($request->input('contact_name'));
            $updateData['email_id'] = trim($request->input('email_id'));
            $updateData['contact_no'] = trim($request->input('contact_no'));
            $updateData['mobile'] = trim($request->input('mobile'));
            $updateData['stage_id'] = trim($request->input('stage_id'));

            $updateData['country'] = trim($request->input('country'));
            $updateData['city'] = trim($request->input('city'));
            $updateData['website'] = trim($request->input('website'));
            $updateData['legal_status'] = trim($request->input('legal_status'));
            $updateData['profile_info'] = trim($request->input('profile_info'));


            $updateData['milestone'] = trim($request->input('milestone'));

            $updateData['member_spec'] = trim($request->input('member_spec'));
            $updateData['member_looking'] = trim($request->input('member_looking'));
            $updateData['member_help'] = trim($request->input('member_help'));
            $updateData['achievements'] = trim($request->input('achievements'));
            $updateData['certifications'] = trim($request->input('certifications'));

            $updateData['is_raised_invest'] = trim($request->input('is_raised_invest'));

            if($updateData['is_raised_invest']==1)
            {
                $updateData['invest_name'] = trim($request->input('invest_name'));
            }
            else
            {
                $updateData['invest_name'] = "";
            }

            $updateData['invest_name'] = trim($request->input('invest_name'));

            $updateData['speech'] = trim($request->input('speech'));
            
            $updateData['updated_by'] = Auth::user()->id;
            $updateData['updated_at'] = date('Y-m-d H:i:s');
            if( $request->hasFile('image') ) {

                $image = $request->file('image');
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
                $updateData['image'] = $file_newname;
            }
            $res = Users::where('timestamp_id', '=', $user_timestamp_id)->update($updateData);
            if( $res ) {

                $childs =Users::where('founder_id', '=', $User->id)->get();

                foreach ($childs as $key => $child) {
                    $update_child_data = array(
                    'slug' => $updateData['slug']
                    );

                    //dd($update_data);
                    Users::where('id', '=', $child->id)->update($update_child_data);
                }

                //founder
                $founder_name = $request->input('founder_name');
                $founder_profile = $request->input('founder_profile');

                $founder_img=$request->file('founder_img');

                //dd($founder_img);

                $founder_img_hidden=$request->input('founder_img_hidden');

                if(!empty($founder_name))
                {
                    if(!empty($founder_img_hidden))
                    {
                        $count_hidden =count($founder_img_hidden);
                        $memberFounder = array();
                        foreach($founder_name as $key=>$v) {

                            
                            if(!empty($v))
                            {
                                $memberFounder[$key]['member_id'] = $User->id;
                                $memberFounder[$key]['name'] = $v;
                                $memberFounder[$key]['profile'] = $founder_profile[$key];

                                //$key +=$count_hidden;

                                //echo $key;die;


                                if( isset($founder_img[$key]) ) {
                                    $image = $founder_img[$key];
                                    $real_path = $image->getRealPath();
                                    $file_orgname = $image->getClientOriginalName();
                                    $file_size = $image->getClientSize();
                                    $file_ext = strtolower($image->getClientOriginalExtension());
                                    $file_newname = "founder"."_".time().$key.".".$file_ext;
                                    $destinationPath = public_path('/uploads/founder_images');
                                    $original_path = $destinationPath."/original";
                                    $thumb_path = $destinationPath."/thumb";
                                    
                                    $img = Image::make($real_path);
                                    $img->resize(150, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    })->save($thumb_path.'/'.$file_newname);

                                    $image->move($original_path, $file_newname);
                                    $memberFounder[$key]['image'] = $file_newname;
                                }
                                else
                                {
                                    $file_newname =$founder_img_hidden[$key];
                                    $memberFounder[$key]['image'] = $file_newname;
                                }
                            }
                            
                        }

                        
                        if (!empty($memberFounder)) {
                            FounderTransaction::where('member_id', '=', $User->id)->delete();
                            FounderTransaction::insert($memberFounder);
                        }
                    }
                    else
                    {
                    
                    $memberFounder = array();
                    foreach($founder_name as $key=>$v) {

                        if(!empty($v))
                        {
                        
                            $memberFounder[$key]['member_id'] = $User->id;
                            $memberFounder[$key]['name'] = $v;
                            $memberFounder[$key]['profile'] = $founder_profile[$key];

                            //$key +=$count_hidden;

                            //echo $key;die;
                            if( $founder_img[$key] ) {
                                $image = $founder_img[$key];
                                $real_path = $image->getRealPath();
                                $file_orgname = $image->getClientOriginalName();
                                $file_size = $image->getClientSize();
                                $file_ext = strtolower($image->getClientOriginalExtension());
                                $file_newname = "founder"."_".time().$key.".".$file_ext;
                                $destinationPath = public_path('/uploads/founder_images');
                                $original_path = $destinationPath."/original";
                                $thumb_path = $destinationPath."/thumb";
                                
                                $img = Image::make($real_path);
                                $img->resize(150, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->save($thumb_path.'/'.$file_newname);

                                $image->move($original_path, $file_newname);
                                $memberFounder[$key]['image'] = $file_newname;
                            }
                        }
                    }

                    //dd($memberFounder);

                    
                    if (!empty($memberFounder)) {
                        FounderTransaction::where('member_id', '=', $User->id)->delete();
                        FounderTransaction::insert($memberFounder);
                    }
                }
                
                }

                //founder

                //service
                $buisness_caption = $request->input('buisness_caption');

           
                //$buisness_website = $request->input('buisness_website');

                $buisness_img=$request->file('buisness_img');

                //dd($founder_img);

                $buisness_img_hidden=$request->input('buisness_img_hidden');

                $buisness_video=$request->input('buisness_video');

                //dd($buisness_video);

                //echo count($buisness_caption);die;
                if(count($buisness_caption))
                {
                    if(!empty($buisness_img_hidden)){

                        
                        $memberService = array();
                        foreach($buisness_caption as $key=>$v) {
                            //echo $v;die;
                            if(!empty($v))
                            {
                                //echo "1";die;
                                $memberService[$key]['member_id'] = $User->id;
                                $memberService[$key]['caption'] = $v;

                                $memberService[$key]['buisness_video'] = $buisness_video[$key];
                                //$memberService[$key]['website'] = $buisness_website[$key];

                                //$key +=$count_hidden;

                                //echo $key;die;


                                if( isset($buisness_img[$key]) ) {
                                    $image = $buisness_img[$key];
                                    $real_path = $image->getRealPath();
                                    $file_orgname = $image->getClientOriginalName();
                                    $file_size = $image->getClientSize();
                                    $file_ext = strtolower($image->getClientOriginalExtension());
                                    $file_newname = "service"."_".time().$key.".".$file_ext;
                                    $destinationPath = public_path('/uploads/website_images');
                                    $original_path = $destinationPath."/original";
                                    $thumb_path = $destinationPath."/thumb";
                                    
                                    $img = Image::make($real_path);
                                    $img->resize(150, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    })->save($thumb_path.'/'.$file_newname);

                                    $image->move($original_path, $file_newname);
                                    $memberService[$key]['image'] = $file_newname;
                                }
                                else
                                {
                                    $file_newname =$buisness_img_hidden[$key];
                                    $memberService[$key]['image'] = $file_newname;
                                }
                            }
                            
                            
                        }

                        //dd($memberFounder);

                        
                        if (!empty($memberService)) {
                            MemberService::where('member_id', '=', $User->id)->delete();
                            MemberService::insert($memberService);
                        }
                    }
                    else
                    {
                        $memberService = array();
                        foreach($buisness_caption as $key=>$v) {

                            
                            if(!empty($v))
                            {
                                $memberService[$key]['member_id'] = $User->id;
                                $memberService[$key]['caption'] = $v;
                                //$memberService[$key]['website'] = $buisness_website[$key];

                                //$key +=$count_hidden;

                                //echo $key;die;
                                if( $buisness_img[$key] ) {
                                    $image = $buisness_img[$key];
                                    $real_path = $image->getRealPath();
                                    $file_orgname = $image->getClientOriginalName();
                                    $file_size = $image->getClientSize();
                                    $file_ext = strtolower($image->getClientOriginalExtension());
                                    $file_newname = "service"."_".time().$key.".".$file_ext;
                                    $destinationPath = public_path('/uploads/website_images');
                                    $original_path = $destinationPath."/original";
                                    $thumb_path = $destinationPath."/thumb";
                                    
                                    $img = Image::make($real_path);
                                    $img->resize(150, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    })->save($thumb_path.'/'.$file_newname);

                                    $image->move($original_path, $file_newname);
                                    $memberService[$key]['image'] = $file_newname;
                                }
                            }
                        }

                        //dd($memberFounder);

                        
                        if (!empty($memberService)) {
                            MemberService::where('member_id', '=', $User->id)->delete();
                            MemberService::insert($memberService);
                        }
                    }
                }


                $industry_ids = $request->input('industry_id');
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


                $company_videoArr = $request->input('company_video');
                //dd($company_videoArr);

                if(!empty($company_videoArr)){
                    $memberVideo = array();
                    foreach($company_videoArr as $v1) {
                        $arr = array();

                        if(!empty($v1))
                        {
                            $arr['member_id'] = $User->id;
                            $arr['company_video'] = $v1;
                            array_push($memberVideo, $arr);
                        }
                        
                    }
                    if (!empty($memberVideo)) {


                        if(count($memberVideo) >5)
                        {
                             return back()->with('msg', 'Five Company Video allo')->with('msg_class', 'alert alert-danger');
                        }
                        MemberVideo::where('member_id', '=', $User->id)->delete();
                        MemberVideo::insert($memberVideo);
                    }
                }

                return back()->with('msg_class', 'alert alert-success')
                ->with('msg', 'User Updated Succesfully.');
            } else {
                return back()->with('msg_class', 'alert alert-danger')
                ->with('msg', 'Something Went Wrong.');
            }
        } else {
            return back()->with('msg_class', 'alert alert-danger')
            ->with('msg', 'Something Went Wrong. User Missmatch');
        }
    }

     public function updateSubUser(Request $request, $user_timestamp_id) {

        $User = Users::where('timestamp_id', '=', $user_timestamp_id)->first();
        if( !empty($User) ) {

            $request->validate([
            
            'email_id' => 'required|email|unique:users,email_id,'.$User->id
            ],[
            
                'email_id.unique' => 'This Email-id Already Exist, Try Another.'
            ]);

            $updateData = array();
            $updateData['member_company'] = trim($request->input('member_company'));
            $updateData['email_id'] = trim($request->input('email_id'));
            //$updateData['contact_no'] = trim($request->input('contact_no'));
            //$updateData['mobile'] = trim($request->input('mobile'));
    
           
            
            $updateData['updated_by'] = Auth::user()->id;
            $updateData['updated_at'] = date('Y-m-d H:i:s');
            
            $res = Users::where('timestamp_id', '=', $user_timestamp_id)->update($updateData);
            if( $res ) {

                return back()->with('msg_class', 'alert alert-success')
                ->with('msg', 'User Updated Succesfully.');
            }
            else
            {
                 return back()->with('msg_class', 'alert alert-danger')
                 ->with('msg', 'Something Went Wrong. User Missmatch');
            }
        } else {
            return back()->with('msg_class', 'alert alert-danger')
            ->with('msg', 'Something Went Wrong. User Missmatch');
        }
    }

    public function resetPassword( $user_timestamp_id ) {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'userManagement';
        $dataBag['user_data'] = Users::where('timestamp_id', '=', $user_timestamp_id)->first();
        return view('dashboard.users.reset_password', $dataBag);
    }

    public function updatePassword(Request $request, $user_timestamp_id) {

        $ck = Users::where('timestamp_id', '=', $user_timestamp_id)->first();
        if( !empty($ck) ) {
            $updateData = array();
            $updateData['password'] = md5(trim($request->input('password')));
            $res = Users::where('timestamp_id', '=', $user_timestamp_id)->update($updateData);
            if( $res ) {
                return back()->with('msg_class', 'alert alert-success')
                ->with('msg', 'User Password Updated Succesfully.');
            } else {
                return back()->with('msg_class', 'alert alert-danger')
                ->with('msg', 'Something Went Wrong.');
            }
        } else {
            return back()->with('msg_class', 'alert alert-danger')
            ->with('msg', 'Something Went Wrong. User Missmatch');
        }
    }

    public function deleteUser( $user_timestamp_id ) {

        $res = Users::where('timestamp_id', '=', $user_timestamp_id)->update(['status' => '3']);
        $user = Users::where('timestamp_id', '=', $user_timestamp_id)->first();
        //DB::table('model_has_permissions')->where('model_id', $user->id)->delete();
        if( $res ) {
            return back()->with('msg_class', 'alert alert-success')
            ->with('msg', 'User Deleted Succesfully.');
        } else {
            return back()->with('msg_class', 'alert alert-danger')
            ->with('msg', 'Something Went Wrong.');
        }
    }

    public function deleteSubUser( $user_timestamp_id ) {

        $res = Users::where('timestamp_id', '=', $user_timestamp_id)->update(['status' => '3']);
        $user = Users::where('timestamp_id', '=', $user_timestamp_id)->first();
        //DB::table('model_has_permissions')->where('model_id', $user->id)->delete();
        if( $res ) {
            return back()->with('msg_class', 'alert alert-success')
            ->with('msg', 'User Deleted Succesfully.');
        } else {
            return back()->with('msg_class', 'alert alert-danger')
            ->with('msg', 'Something Went Wrong.');
        }
    }

    public function profile() {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'settings';
        $dataBag['childMenu'] = 'profile';
        return view('dashboard.users.profile', $dataBag);
    }

    public function profileUpdate(Request $request) {

        $user_id = Auth::user()->id;
        $request->validate([
            
        'email_id' => 'required|email|unique:users,email_id,'.$user_id
        ],[
        
            'email_id.unique' => 'This Email-id Already Exist, Try Another.'
        ]);

        $updateData = array();
        $updateData['first_name'] = trim($request->input('first_name'));
        $updateData['last_name'] = trim($request->input('last_name'));
        $updateData['email_id'] = trim($request->input('email_id'));
        $updateData['contact_no'] = trim($request->input('contact_no'));
        $updateData['sex'] = trim($request->input('sex'));
        $updateData['address'] = trim($request->input('address'));
        $updateData['updated_by'] = $user_id;
        $updateData['updated_at'] = date('Y-m-d H:i:s');
        if( $request->hasFile('image') ) {

            $image = $request->file('image');
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
            $updateData['image'] = $file_newname;
        }
        $res = Users::where('id', '=', $user_id)->update($updateData);
        if( $res ) {
            return back()->with('msg_class', 'alert alert-success')
            ->with('msg', 'Profile Updated Succesfully.');
        } else {
            return back()->with('msg_class', 'alert alert-danger')
            ->with('msg', 'Something Went Wrong.');
        }
    }

    public function changePassword() {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'settings';
        $dataBag['childMenu'] = 'cngPwd';
        return view('dashboard.users.change_password', $dataBag);
    }

    public function changePasswordSave(Request $request) {

        $current_password = md5(trim($request->input('current_password')));
        $new_password = md5(trim($request->input('new_password')));
        $ck = Users::where('id', '=', Auth::user()->id)
        ->where('password', '=', $current_password)->first();
        if( !empty($ck) ) {
            $res = Users::where('id', '=', Auth::user()->id)->update(['password' => $new_password]);
            if( $res ) {
                return back()->with('msg_class', 'alert alert-success')
                ->with('msg', 'Password Changed Succesfully.');
            } else {
                return back()->with('msg_class', 'alert alert-danger')
                ->with('msg', 'Something Went Wrong.');
            }
        } else {
            return back()->with('msg_class', 'alert alert-danger')
            ->with('msg', 'Current Password Not Match.');
        }
    }

    public function userPermissions($user_timestamp_id) {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'userManagement';
        $dataBag['dashboardModules'] = DashboardModules::with(['modulePermissions'])
        ->orderBy('order_no', 'asc')->get();
        $user = Users::where('timestamp_id', '=', $user_timestamp_id)->first();
        $dataBag['user_data'] = $user;
        $dataBag['user_permissions'] = $user->getAllPermissions()->pluck('id')->toArray();
        return view('dashboard.users.user_permissions', $dataBag);
    }

    public function userPermissionsUpdate(Request $request, $user_timestamp_id)
    {
        $User = Users::where('timestamp_id', '=', $user_timestamp_id)->first();
        if( !empty($User) ) {
            //$currentPermissions = DB::table('model_has_permissions')->where('model_id', $User->id)->delete();
            if($request->has('permission_ids') && !empty($request->input('permission_ids'))) {
                $User->syncPermissions($request->input('permission_ids'));
                return back()->with('msg_class', 'alert alert-success')
                ->with('msg', 'Permissions updated successfully.');
            }
        }
        return back()->with('msg_class', 'alert alert-danger')
        ->with('msg', 'Something went wrong.');
    }

    public function takeAction(Request $request) {
        $msg = '';
        if( $request->has('action_btn') && $request->has('user_ids') ) {
            $actBtnValue = trim( $request->input('action_btn') );
            $idsArr = $request->input('user_ids');

            switch ( $actBtnValue ) {
                
                case 'activate':
                    foreach($idsArr as $id) {
                        $user = Users::find($id);
                        $user->status = '1';
                        $user->save();
                    }
                    $msg = 'Users Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $user = Users::find($id);
                        $user->status = '2';
                        $user->save();
                    }
                    $msg = 'Users Deactivated Succesfully.';
                    break;

                case 'delete_user':
                    foreach($idsArr as $id) {
                        $user = Users::find($id);
                        $user->status = '3';
                        $user->save();
                        DB::table('model_has_permissions')->where('model_id', $id)->delete();
                    }
                    $msg = 'Users Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }

}
