<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndustryCategories;
use App\Models\MemberBusiness;
use App\Models\MemberInvestment;
use App\Models\Users;
use App\Models\Posts;
use App\Models\PostIndustry;
use App\Models\Categories;
use App\Models\PostCategory;
use App\Models\PostReply;
use App\Models\PostMedia;
use App\Models\EmailTemplate;
use App\Models\FounderTransaction;
use App\Models\MemberService;
use App\Models\MemberVideo;
use App\Models\Message;
use App\Models\CompanyType;
use App\Models\StartupCustomer;
use App\Models\StartupYearlyFinancial;
use App\Models\StartupMonthlySale;
use App\Models\StartupMonthlyExpenditure;
use App\Models\StartupMonthlyOrderPipeline;
use Session;
use Image;
use View;
use Auth;
use DB;
use Mail;
use Illuminate\Support\Str;
use App\Models\Invitations;
use App\Jobs\MailSendJob;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\StartupComplianceCheck;
use App\Models\StartupFundingNeed;
use App\Models\StartupImpact;
use App\Models\StartupYearlyTarget;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Builder;


class FrontendController extends Controller
{
    public function __construct(Request $request)
    {


        //dd(\Auth::admin());
        $shareData = array();

        $shareData['industry_category'] = DB::table('industry_category')->where('status', 1)
            ->orderBy('industry_category', 'asc')->get();


        $shareData['industry_category_show'] = DB::table('industry_category')->where('status', 1)->where('id', '!=', 7)
            ->orderBy('industry_category', 'asc')->get();

        $shareData['category'] = DB::table('categories')->where('status', 1)
            ->orderBy('name', 'asc')->get();
        //dd($shareData['category']);

        $shareData['privateMember'] = Users::where('user_type', '=', '2')->where('status', '=', 1)
            ->orderBy('contact_name', 'asc')->get();

        View::share($shareData);
    }

    public function index()
    {
        $DataBag = array();
        $DataBag['stage'] = DB::table('stage')->orderBy('stage_name', 'asc')->get();
        return view('frontend.index', $DataBag);
    }

    public function forgetPassword()
    {
        $DataBag = array();
        $DataBag['stage'] = DB::table('stage')->orderBy('stage_name', 'asc')->get();
        return view('frontend.forgetPassword', $DataBag);
    }

    public function sendLink(Request $request)
    {

        $email_id = trim($request->input('email_id'));

        $ckEmail = Users::where('email_id', '=', $email_id)->first();
        if (!empty($ckEmail)) {
            $userFname = $ckEmail->first_name;
            $token = md5(microtime(TRUE) . $email_id);
            $link = route('reset_pwd', array('token' => $token));
            $link = "<a href='" . $link . "' target='_blank'>" . $link . "</a>";
            $empTemp = \App\Models\EmailTemplate::find(2);
            if (!empty($empTemp)) {

                $chars = "0123456789";
                $password = substr(str_shuffle($chars), 0, 8);

                $emSubject = $empTemp->subject;
                $content = $empTemp->description;
                $content = str_replace("{{username}}", $userFname, $content);
                $content = str_replace("{{email}}", $email_id, $content);
                $content = str_replace("{{password}}", $password, $content);



                // $content = str_replace("[FIRSTNAME]", $userFname, $content);
                // echo $content = str_replace("[PWD_RESET_LINK]", $link, $content);
                // die;

                $res = Users::where('email_id', '=', $email_id)->update(['password' => md5($password)]);

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                // More headers
                $headers .= 'From: <no-reply@iimcip.net>' . "\r\n";
                // $headers .= 'Cc: karmickdeveloper77@gmail.com' . "\r\n";

                mail($email_id, $emSubject, trim($content), $headers);


                // $job = (new MailSendJob('emails.accountVerification', $emSubject, $email_id, trim($content)))->delay(5)->onQueue('default');

                // dispatch($job);

                if ($res) {
                    return back()->with('msg', 'New Password Send To Your Mail.');
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


    public function signup()
    {
        $DataBag = array();
        if (isset($_GET['email'])) {
            $DataBag['email_id'] = $_GET['email'];
        }

        $DataBag['stage'] = DB::table('stage')->orderBy('stage_name', 'asc')->get();

        $DataBag['legal_status'] = DB::table('legal_status')->orderBy('legal_status', 'asc')->get();


        return view('frontend.signup', $DataBag);
    }

    public function signupIndex()
    {
        return redirect()->route('signinup');
    }

    public function signinIndex()
    {
        return redirect()->route('signinup');
    }

    public function userSignup(Request $request)
    {
        $ckEmail = Users::where('email_id', trim($request->input('email_id')))->first();
        if (!empty($ckEmail)) {
            return back()->with('msg', 'Email-id already exist, try another')
                ->with('msg_class', 'alert alert-danger')
                ->with('signupError', 'email_exist');
        }

        $ckEmail = Users::where('email_id', trim($request->input('contact_email')))->first();
        if (!empty($ckEmail)) {
            return back()->with('msg', 'Contact Email-id already exist, try another')
                ->with('msg_class', 'alert alert-danger')
                ->with('signupError', 'email_exist');
        }

        $ckCode = Invitations::where('status', 0)->where('startup_email', trim($request->input('email_id')))->where('code', trim($request->input('code')))->first();
        if (empty($ckCode)) {
            return back()->with('msg', 'Code Does Not Exist, try another')
                ->with('msg_class', 'alert alert-danger')
                ->with('signupError', 'email_exist');
        }

        $contact_email = $request->input('contact_email');
        $email_id = $request->input('email_id');

        if ($contact_email == $email_id) {
            return back()->with('msg', 'Contact email should be different from company email')
                ->with('msg_class', 'alert alert-danger')
                ->with('signupError', 'email_exist');
        }

        $updateData = array();
        $updateData['status'] = 1;
        $res = Invitations::where('status', 0)->where('startup_email', trim($request->input('email_id')))->where('code', trim($request->input('code')))->update($updateData);

        $Users = new Users;
        $Users->user_type = 2;
        $Users->timestamp_id = md5(microtime(TRUE));

        $Users->member_company = trim($request->input('member_company'));
        $Users->slug = Str::slug($Users->member_company, '-');
        $Users->contact_name = trim($request->input('contact_name'));
        $Users->stage_id = trim($request->input('stage_id'));
        $Users->email_id = trim($request->input('email_id'));
        //$Users->password = md5('iImCiP@2020$');
        $Users->password = md5($request->input('password'));
        $Users->contact_no = trim($request->input('contact_no'));
        $Users->mobile = trim($request->input('contact_no'));
        $Users->country = trim($request->input('country'));
        $Users->city = trim($request->input('city'));
        $Users->website = trim($request->input('website'));
        $Users->legal_status = trim($request->input('legal_status'));

        $Users->last_login = date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));

        $Users->current_login = date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $real_path = $image->getRealPath();
            $file_orgname = $image->getClientOriginalName();
            $file_size = $image->getClientSize();
            $file_ext = strtolower($image->getClientOriginalExtension());
            $file_newname = "user" . "_" . time() . "." . $file_ext;
            $destinationPath = public_path('/uploads/user_images');
            $original_path = $destinationPath . "/original";
            $thumb_path = $destinationPath . "/thumb";

            $img = Image::make($real_path);
            $img->resize(150, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumb_path . '/' . $file_newname);

            $image->move($original_path, $file_newname);
            $Users->image = $file_newname;
        }

        if ($Users->save()) {
            $industry_idsArr = $request->input('industry_category_id');
            if (!empty($industry_idsArr)) {
                $memberBusiness = array();
                foreach ($industry_idsArr as $v) {
                    $arr = array();
                    $arr['member_id'] = $Users->id;
                    $arr['industry_category_id'] = $v;
                    array_push($memberBusiness, $arr);
                }
                if (!empty($memberBusiness)) {
                    MemberBusiness::insert($memberBusiness);
                }
            }


            $founder_id = $Users->id;

            $user_result = DB::table('users')->where('id', '=', $founder_id)->select('member_company', 'slug')->first();

            $Company_Users = new Users();

            $Company_Users->member_company = $user_result->member_company;
            $Company_Users->slug = $user_result->slug;
            $Company_Users->timestamp_id = md5(microtime(TRUE));

            $Company_Users->user_type = 2;

            $Company_Users->contact_name = trim($request->input('contact_name'));

            $Company_Users->email_id = trim($request->input('contact_email'));
            $Company_Users->password = md5(trim($request->input('password')));

            $Company_Users->mobile = trim($request->input('contact_no'));

            $Company_Users->founder_id = $founder_id;

            $Company_Users->created_by = $Users->id;


            $Company_Users->last_login = date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));

            if ($Company_Users->save()) {

                $template_data = EmailTemplate::where('id', '=', '3')->first();
                //dd($template_data);
                $content = $template_data->description;

                $content = str_replace("{{email}}", $Users->contact_email, $content);
                $content = str_replace("{{username}}", $Users->contact_name, $content);
                $content = str_replace("{{password}}", $request->input('password'), $content);

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                // More headers
                $headers .= 'From: <no-reply@iimcip.net>' . "\r\n";
                // $headers .= 'Cc: karmickdeveloper77@gmail.com' . "\r\n";

                mail($Users->contact_email, 'Add Company User', $content, $headers);

                // return back()->with('msg', 'User information has been saved successfully!')
                // ->with('msg_class', 'alert alert-success');
            }



            $email_id = trim($request->input('contact_email'));
            $password = md5(trim($request->input('password')));
            if ($this->autoLogin($email_id, $password) == 1) {
                return redirect()->route('front.user.account')
                    ->with('msg', 'Welcome to IIMCIP')->with('msg_class', 'alert alert-success');
            } else {
                return back()->with('msg', 'Account created successfully, Thanks')
                    ->with('msg_class', 'alert alert-success');
            }
        }

        return back();
    }

    public function autoLogin($email_id, $password)
    {
        $loginUser = Users::where('email_id', '=', $email_id)
            ->where('password', '=', $password)->where('user_type', 2)->where('status', '=', '1')->first();

        if (!empty($loginUser)) {
            Auth::login($loginUser);
            Session::put('iimcip_user_id', $loginUser->id);
            Session::put('is_iimcip_logged_in', 'yes');
            Session::put('user_type_id', $loginUser->user_type);
            return 1;
        }
        return 0;
    }

    public function userLogin(Request $request)
    {
        $request->validate([
            'email_id' => 'required',
            'password' => 'required'
        ], [
            'email_id.required' => 'Please enter email-id.',
            'password.required' => 'Please enter password.'
        ]);

        $email_id = trim($request->input('email_id'));
        $password = md5(trim($request->input('password')));
        $rm_me = trim($request->input('rm_me'));
        $norPwd = trim($request->input('password'));



        $loginUser = Users::where('email_id', '=', $email_id)
            ->where('password', '=', $password)->where('status', '=', '1')->first();


        //dd($email_id, $password);

        $new_time = date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));

        if (!empty($loginUser)) {
            Auth::login($loginUser);

            $user_id = Auth::user()->id;
            $current_login = Auth::user()->current_login;

            if (empty($current_login)) {
                $current_login = $new_time;
            }

            $update_data = array(
                'current_login' => $new_time
            );
            Users::where('id', '=', $user_id)->update($update_data);

            if ($rm_me == '1') {
                setcookie("iimcip_email", $email_id, time() + (86400 * 30));
                setcookie("iimcip_password", $norPwd, time() + (86400 * 30));
            } else {
                unset($_COOKIE['iimcip_email']);
                unset($_COOKIE['iimcip_password']);
                setcookie("iimcip_email", '', time() - 3600);
                setcookie("iimcip_password", '', time() - 3600);
            }

            Session::put('iimcip_user_id', $loginUser->id);
            Session::put('is_iimcip_logged_in', 'yes');
            Session::put('user_type_id', $loginUser->user_type);

            //return redirect()->route('front.user.account');

            //            return redirect()->route('front.user.dashboard');

            //echo $loginUser->user_type; die;
            if ($loginUser->user_type == 6) {
                return redirect()->route('front.mentor.startup');
            } else {
                return redirect()->route('front.user.feed', ['post' => 'feed']);
            }
        } else {
            return back()->with('msg', 'Sorry! Incorrect Login Information..');
        }
    }

    public function account(Request $request)
    {


        $DataBag = array();
        $DataBag['queryString'] = $_SERVER['QUERY_STRING'];

        //$user_type = Auth::user()->user_type;


        if ($request->category == "startup") {
            //$DataBag['users'] = array();



            $DataBag['usersQuery'] = Users::orderBy('id', 'desc')->where('member_company', 'LIKE', '%' . $_GET['search'] . '%')->whereNotIn('member_company', ['IIMCIP Portfolio Managers', 'IIMCIP MENTORS'])->whereNull('founder_id')->get();
        }

        /* 
        if(isset($_GET['category']) && $_GET['category'] =='company' ){
        return redirect()->route('front.user.company', ['category' =>'company', 'search'=> $_GET['search']]);
        } */

        if ($request->category == "company") {


            $DataBag['companyQuery'] = Users::orderBy('id', 'desc')->where('contact_name', 'LIKE', '%' . $_GET['search'] . '%')->get();



            //dd($DataBag['companyQuery']);

        }


        if ($request->category == "mentor") {

            //$DataBag['mentorQuery'] = Users::orderBy('id','desc')->where('first_name', 'LIKE', '%'.$_GET['search'].'%' )->where('user_type', '=', 6)->whereNull('founder_id')->get();

            $DataBag['mentorQuery'] = Users::orderBy('id', 'desc')->where('contact_name', 'LIKE', $_GET['search'] . '%')->get();



            //dd($DataBag['mentorQuery']);

        }




        $postQuery = Posts::with(['memberInfo'])->where('status', '=', '1');

        if (isset($_GET['post']) && $_GET['post'] != '') {
            $postFilter = trim($_GET['post']);
            if ($postFilter == 'public') {
                $postQuery = $postQuery->where(function ($postQuery) {
                    $postQuery->where('post_type', '=', '1');
                });
            }
            if ($postFilter == 'private') {
                $postQuery = $postQuery->where(function ($postQuery) {
                    $postQuery->where('post_type', '=', '2');
                    $postQuery->where('private_member_id', '=', Auth::user()->id);
                    $postQuery->orWhere('private_sender_id', '=', Auth::user()->id);
                });
            }
            if ($postFilter == 'administrator') {
                $postQuery = $postQuery->where(function ($postQuery) {
                    $postQuery->where('post_type', '=', '1');
                    $postQuery->where('member_id', '=', '1');
                });
            }
        } else {
            $postQuery = $postQuery->where(function ($postQuery) {
                $postQuery->where('post_type', '=', '1');
                $postQuery->orWhere(
                    function ($postQuery) {
                        $postQuery->where('post_type', '=', '2');
                        $postQuery->where('private_member_id', '=', Auth::user()->id);
                    }
                );
                // $postQuery->orWhere(function($postQuery) {
                //     $postQuery->where('post_type', '=', '2');
                //     $postQuery->where('member_id', '=', Auth::user()->id);
                // });
            });
            //dd($postQuery->toSql());
        }
        if (isset($_GET['industry']) && $_GET['industry'] != '') {
            $industryCatID = base64_decode($_GET['industry']);
            $postQuery = $postQuery->where(function ($postQuery) use ($industryCatID) {
                $postQuery = $postQuery->whereHas(
                    'postIndistries',
                    function ($postQuery) use ($industryCatID) {
                        $postQuery->where('industry_category_id', '=', 7);
                        $postQuery->orWhere('industry_category_id', '=', $industryCatID);
                    }
                );
            });
        }


        if (isset($_GET['category']) && $_GET['category'] != '') {
            $categoryCatID = base64_decode($_GET['category']);
            $postQuery = $postQuery->where(function ($postQuery) use ($categoryCatID) {
                $postQuery = $postQuery->whereHas(
                    'postCategories',
                    function ($postQuery) use ($categoryCatID) {
                        $postQuery->where('category_id', '=', $categoryCatID);
                    }
                );
            });
            //dd($postQuery)
        }
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = trim($_GET['search']);
            if (strpos($search, '(') !== false) {
                $search = trim(substr($search, 0, strpos($search, '(')));
            }
            $postQuery = $postQuery->where(function ($postQuery) use ($search) {
                $postQuery->where('post_title', 'LIKE', '%' . $search . '%');
                $postQuery->orWhere('post_info', 'LIKE', '%' . $search . '%');
                $postQuery->orWhereHas(
                    'memberInfo',
                    function ($postQuery) use ($search) {
                        $postQuery->where('contact_name', 'LIKE', '%' . $search . '%');
                        $postQuery->orWhere('member_company', 'LIKE', '%' . $search . '%');
                    }
                );
            });
        }
        if (isset($_GET['category']) && $_GET['category'] != '') {
            $categoryCatID = base64_decode($_GET['category']);
            $postQuery = $postQuery->where(function ($postQuery) use ($categoryCatID) {
                $postQuery = $postQuery->whereHas(
                    'postCategories',
                    function ($postQuery) use ($categoryCatID) {
                        $postQuery->where('category_id', '=', $categoryCatID);
                    }
                );
            });
        }




        $DataBag['normalPost'] = $postQuery->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(15);

        //dd($DataBag['normalPost']);

        $DataBag['mesg'] = "Sorry! No post found.";





        return view('frontend.account', $DataBag);
    }

    public function feed()
    {

        $DataBag = array();

        //echo Auth::user()->id;die;

        $industry_category = MemberBusiness::where('member_id', '=', Auth::user()->id)->select('industry_category_id')->get();

        $category = array();

        foreach ($industry_category as $key => $value) {
            $category[$key] = $value->industry_category_id;
        }

        $myString = '1,2,3';
        $myArray = explode(',', $myString);
        //dd($myArray);
        //dd($category);

        //DB::enableQueryLog();



        $DataBag['myPosts'] = Posts::join('post_industry', 'post_industry.post_id', '=', 'post_master.id')->where(function ($query) use ($category) {
            $query->whereIn('post_industry.industry_category_id', $category);
        })->orWhere(function ($query) {
            $query->where('is_bookmarked', 1);
        })
            ->orderBy('post_master.id', 'desc')->select('post_master.*')->paginate(15);


        //dd($DataBag['myPosts']->toArray());

        //dd(DB::getQueryLog());

        $DataBag['mesg'] = "Sorry! No post found.";
        return view('frontend.feed_post', $DataBag);
    }

    public function startup()
    {


        $users = Users::where('users.status', 1)->where('user_type', 2)->whereNull('founder_id');

        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = trim($_GET['search']);
            if (strpos($search, '(') !== false) {
                $search = trim(substr($search, 0, strpos($search, '(')));
            }
            $users->where('users.member_company', 'LIKE', '%' . $search . '%');
        }
        if (isset($_GET['searr_id']) && $_GET['searr_id'] != '') {

            //echo $_GET['search_id'];die;

            $search1 = trim($_GET['searr_id']);
            if (strpos($search1, '(') !== false) {
                $search1 = trim(substr($search1, 0, strpos($search1, '(')));
            }
            $users->where('member_company', 'LIKE', '' . $search1 . '%');
        }

        if (isset($_GET['industry_id']) && $_GET['industry_id'] != '') {

            //echo $_GET['search_id'];die;

            $users = $users->join('member_business', 'users.id', '=', 'member_business.member_id');

            $search2 = trim($_GET['industry_id']);
            if (strpos($search2, '(') !== false) {
                $search2 = trim(substr($search2, 0, strpos($search2, '(')));
            }
            $users->where('member_business.industry_category_id', $search2);
        }

        $users = $users->orderBy('users.member_company', 'asc')->select('users.*')->get();

        $DataBag['users'] = array();

        //dd($users->toArray());

        foreach ($users as $key => $user) {
            $company_id = $user->id;


            //echo $user->id;die;
            $DataBag['users'][$key]['memberInfo'] = Users::join('stage', 'stage.id', '=', 'users.stage_id')->where('users.id', '=', $company_id)->select('users.*', 'stage.stage_name')->first();

            $DataBag['users'][$key]['number_of_post'] = Posts::where('member_id', '=', $company_id)->where('status', '=', '1')->count();

            $DataBag['users'][$key]['number_of_reply'] = PostReply::where('replied_by', '=', $company_id)->where('status', '=', '1')->count();


            $DataBag['users'][$key]['founders'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();

            $DataBag['users'][$key]['founderscount'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->count();
        }

        //dd($DataBag);



        return view('frontend.startup', $DataBag);
    }

    public function myMessages()
    {
        $DataBag = array();
        $sender_id = Auth::user()->id;

        $DataBag['sender_id'] = $sender_id;


        // $DataBag['users'] = Message::select('users.*','message.message')->where('message.sender_id',$sender_id)->join('users', 'users.id', '=', 'message.receiver_id')
        // ->distinct('users.id')->get();

        $DataBag['users'] = Message::select('message.*')->where('message.sender_id', $sender_id)->orWhere('message.receiver_id', '=', $sender_id)
            ->orderBy('id', 'desc')->get();

        foreach ($DataBag['users'] as $key => $user) {

            if ($sender_id == $user['sender_id']) {
                $DataBag['users'][$key]['user'] = Users::where('id', $user['receiver_id'])->first();
            } else {
                $DataBag['users'][$key]['user'] = Users::where('id', $user['sender_id'])->first();
            }
        }

        // $a=array();
        // foreach ($DataBag['users'] as $key => $user) {

        //     if(!in_array($user['user']->id, $a)){ 

        //         array_push($a,$user['user']->id); 
        //     } 
        // }

        // for($i=0;$i<=count($DataBag['users'])-1; $i++) {

        //     for($j=1;$j<=count($DataBag['users'])-1; $j++) {

        //       if(isset($DataBag['users'][$i]) && isset($DataBag['users'][$j]) && $DataBag['users'][$i]['user']->id==$DataBag['users'][$j]['user']->id){
        //           unset($DataBag['users'][$j]);
        //       }

        //     }  
        // }


        return view('frontend.messages', $DataBag);
    }

    public function sendMessage()
    {

        $receiver_id = $_GET['user_id'];
        $DataBag = array();
        $sender_id = Auth::user()->id;

        $DataBag['receiver_id'] = $receiver_id;

        DB::enableQueryLog();

        $DataBag['messages'] = DB::select("select `users`.*,`message`.`created_at` as mtime, `message`.`message`, `message`.`receiver_id`,`message`.`sender_id` from `message` inner join `users` on `users`.`id` = `message`.`sender_id` where (`message`.`sender_id` = $sender_id and `message`.`receiver_id` = $receiver_id) or (`message`.`sender_id` = $receiver_id and `message`.`receiver_id` = $sender_id) 
        order by message.id desc");

        DB::query("update message set read_status=1 where (`message`.`sender_id` = $sender_id and `message`.`receiver_id` = $receiver_id) or (`message`.`sender_id` = $receiver_id and `message`.`receiver_id` = $sender_id) 
        ");


        $DataBag['user'] = Users::where('id', $receiver_id)->first();

        return view('frontend.chatbox', $DataBag);
    }

    public function sendMessages(Request $request)
    {

        $message = new Message;
        $message->sender_id = Auth::user()->id;
        $message->receiver_id = trim($request->input('receiver_id'));
        $message->message = trim($request->input('message'));

        if ($message->save()) {


            $r_user = trim($request->input('receiver_id'));

            $Users = Users::findOrFail($r_user);

            if (isset($Users) && $Users->email_id != '') {

                //$content = '<p>Hi '.$Users->first_name.'</p>
                $content = '<p>Hi ' . $Users->contact_name . '</p>
				
                <p>You have received a new message in IIMCIP Collaboration Platform. Please login to the application to view the message.</p>
                 
                <p>Thanks & Regards,</p>
                Team IIMCIP ';
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                // More headers
                $headers .= 'From: <no-reply@iimcip.net>' . "\r\n";
                // $headers .= 'Cc: karmickdeveloper77@gmail.com' . "\r\n";

                mail($Users->email_id, 'You have a new message', $content, $headers);
            }


            /** Push Notification  */

            // $token =Users::select('deviceToken')->where('id',$r_user)->where('users.status',1)->where('user_type',2)->where('deviceToken','!=', null)->where('deviceToken','!=', '')->pluck('deviceToken')->toArray();

            //$token =Users::select('deviceToken')->where('id',$r_user)->where('users.status',1)->where('user_type',2)->pluck('deviceToken')->toArray();

            //dd($token);


            $url = "https://fcm.googleapis.com/fcm/send"; //Google api endpoint to send notification


            ////'your server token of FCM project'
            $serverKey = "AAAA_6ilIwA:APA91bEttkTvM4mdzwCpnPGif_KPQuUG7DIsTeIYBOvAL5EOdKXIrtsXUv_eBeA-5lVl0pVVYdQflsjWKHc6nv3BUNArua20CCJA2r3kbP2sxcDjcPg3IEbeLk2JhZTqQXnfVO3hwI7-";

            $title = "Incubatee"; //title of the message to send
            $body = "You have a new message."; // body of the message to send

            $notification = array('title' => $title, 'body' => $body, 'sound' => 'default', 'badge' => '1');

            //$mtype=="MSG";
            $mtype = "MSG";
            $data = array("notification_type" => $mtype); //data payload, this is extra data to be sent  



            //'to' for one instead of  registration_ids

            //$arrayToSend = array('registration_ids' => $token, 'notification' => $notification,'priority'=>'high', 'data'=>$data);
            $arrayToSend = array('notification' => $notification, 'priority' => 'high', 'data' => $data);

            //$arrayToSend = array('registration_ids' => $token, 'notification' => $notification,'priority'=>'high');
            $json = json_encode($arrayToSend);

            //dd($json);

            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: key=' . $serverKey;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            //Send the request
            $response = curl_exec($ch);

            if (ob_get_length() > 0) {
                ob_end_clean();
            }


            //Close request
            if ($response === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);

            /** Push Notification  */

            return back()->with('msg_class', 'alert alert-success')
                ->with('msg', 'Message Sent Succesfully.');
        } else {
            return back()->with('msg_class', 'alert alert-danger')
                ->with('msg', 'Something Went Wrong.');
        }
    }

    public function company()
    {

        $founder_id = company_id(Auth::user()->id);

        //$users =Users::where('users.status',1)->where('user_type',2)->where('founder_id','!=',NULL);

        $users = Users::where('users.status', 1)->where('user_type', 2)->where('founder_id', '!=', NULL);

        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = trim($_GET['search']);
            if (strpos($search, '(') !== false) {
                $search = trim(substr($search, 0, strpos($search, '(')));
            }
            $users->where('users.contact_name', 'LIKE', '%' . $search . '%');
        }

        if (isset($_GET['searr_id']) && $_GET['searr_id'] != '') {

            //echo $_GET['search_id'];die;

            $search1 = trim($_GET['searr_id']);
            if (strpos($search1, '(') !== false) {
                $search1 = trim(substr($search1, 0, strpos($search1, '(')));
            }
            $users->where('contact_name', 'LIKE', '' . $search1 . '%');
        }

        if (isset($_GET['industry_id']) && $_GET['industry_id'] != '') {

            //echo $_GET['search_id'];die;

            $users = $users->join('member_business', 'users.id', '=', 'member_business.member_id');

            $search2 = trim($_GET['industry_id']);
            if (strpos($search2, '(') !== false) {
                $search2 = trim(substr($search2, 0, strpos($search2, '(')));
            }
            $users->where('member_business.industry_category_id', $search2);
        }

        $users = $users->orderBy('users.contact_name', 'asc')->select('users.*')->get();

        $DataBag['users'] = array();

        //dd($users->toArray());

        foreach ($users as $key => $user) {

            $company_id = $user->id;

            $area_of_expertise = explode(',', $user->area_of_expertise);

            $str = '';
            foreach ($area_of_expertise as $row) {
                if ($row != '') {
                    $ar = DB::table('industry_expertise')->where('id', '=', $row)->first();

                    if (isset($ar)) {
                        $str .= $ar->industry_expertise . ', ';
                    }
                }
            }

            $DataBag['users'][$key]['area_of_expertise'] = rtrim($str, ', ');

            $DataBag['users'][$key]['memberInfo'] = Users::join('stage', 'stage.id', '=', 'users.stage_id')->where('users.id', '=', $company_id)->select('users.*', 'stage.stage_name')->first();

            $DataBag['users'][$key]['number_of_post'] = Posts::where('member_id', '=', $company_id)->where('status', '=', '1')->count();

            $DataBag['users'][$key]['number_of_reply'] = PostReply::where('replied_by', '=', $company_id)->where('status', '=', '1')->count();

            $DataBag['users'][$key]['founders'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();

            $DataBag['users'][$key]['founderscount'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->count();
        }

        return view('frontend.company', $DataBag);
    }

    public function bookmark()
    {

        $DataBag = array();
        $DataBag['queryString'] = $_SERVER['QUERY_STRING'];

        $cur_datev = date('Y-m-d');


        $postQuery = Posts::with(['memberInfo'])->where('is_bookmarked', '=', 1)->where('bookmark_end_date', '>=', $cur_datev)->where('status', '=', '1');

        if (isset($_GET['post']) && $_GET['post'] != '') {
            $postFilter = trim($_GET['post']);
            if ($postFilter == 'public') {
                $postQuery = $postQuery->where(function ($postQuery) {
                    $postQuery->where('post_type', '=', '1');
                });
            }
            if ($postFilter == 'private') {
                $postQuery = $postQuery->where(function ($postQuery) {
                    $postQuery->where('post_type', '=', '2');
                    $postQuery->where('private_member_id', '=', Auth::user()->id);
                    $postQuery->orWhere('private_sender_id', '=', Auth::user()->id);
                });
            }
            if ($postFilter == 'administrator') {
                $postQuery = $postQuery->where(function ($postQuery) {
                    $postQuery->where('post_type', '=', '1');
                    $postQuery->where('member_id', '=', '1');
                });
            }
        } else {

            //echo "1";die;
            $postQuery = $postQuery->where(function ($postQuery) {
                $postQuery->where('post_type', '=', '1');
                $postQuery->orWhere(
                    function ($postQuery) {
                        $postQuery->where('post_type', '=', '2');
                        $postQuery->where('private_member_id', '=', Auth::user()->id);
                    }
                );
            });
            //dd($postQuery->toSql());
        }

        //dd($postQuery->get());
        if (isset($_GET['industry']) && $_GET['industry'] != '') {
            $industryCatID = base64_decode($_GET['industry']);
            $postQuery = $postQuery->where(function ($postQuery) use ($industryCatID) {
                $postQuery = $postQuery->whereHas(
                    'postIndistries',
                    function ($postQuery) use ($industryCatID) {
                        $postQuery->where('industry_category_id', '=', $industryCatID);
                    }
                );
            });
        }
        if (isset($_GET['category']) && $_GET['category'] != '') {
            $categoryCatID = base64_decode($_GET['category']);
            $postQuery = $postQuery->where(function ($postQuery) use ($categoryCatID) {
                $postQuery = $postQuery->whereHas(
                    'postCategories',
                    function ($postQuery) use ($categoryCatID) {
                        $postQuery->where('category_id', '=', $categoryCatID);
                    }
                );
            });
        }
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = trim($_GET['search']);
            if (strpos($search, '(') !== false) {
                $search = trim(substr($search, 0, strpos($search, '(')));
            }
            $postQuery = $postQuery->where(function ($postQuery) use ($search) {
                $postQuery->where('post_title', 'LIKE', '%' . $search . '%');
                $postQuery->orWhereHas(
                    'memberInfo',
                    function ($postQuery) use ($search) {
                        $postQuery->where('contact_name', 'LIKE', '%' . $search . '%');
                        $postQuery->orWhere('member_company', 'LIKE', '%' . $search . '%');
                    }
                );
            });
        }
        $DataBag['normalPost'] = $postQuery->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(15);

        //dd($DataBag['normalPost']);

        $DataBag['mesg'] = "Sorry! No post found.";

        return view('frontend.account', $DataBag);
    }

    public function dasboard()
    {


        $DataBag = array();


        /*DB::enableQueryLog();
        $postcount = DB::table('post_master')->join('users', 'users.id', '=', 'post_master.member_id')->where('post_master.created_at','<',Auth::user()->current_login)->where('post_master.created_at','>=',Auth::user()->last_login)->where('users.post_read','=',0)->count();
        dd(DB::getQueryLog());*/
        //news

        //echo Auth::user()->id;die;


        $cur_datev = date('Y-m-d');

        $postQuery1 = Posts::with(['memberInfo'])->where('is_bookmarked', '=', 1)->where('bookmark_end_date', '>=', $cur_datev)->where('status', '=', '1');

        $postQuery1 = $postQuery1->where(function ($postQuery1) {
            $postQuery1->where('post_type', '=', '1');
            $postQuery1->orWhere(
                function ($postQuery1) {
                    $postQuery1->where('post_type', '=', '2');
                    $postQuery1->where('private_member_id', '=', Auth::user()->id);
                }
            );
        });

        $categoryCatID = 1;
        $postQuery1 = $postQuery1->where(function ($postQuery1) use ($categoryCatID) {
            $postQuery1 = $postQuery1->whereHas(
                'postCategories',
                function ($postQuery1) use ($categoryCatID) {
                    $postQuery1->where('category_id', '=', $categoryCatID);
                }
            );
        });

        $DataBag['newsPostCount'] = $postQuery1->count();

        $DataBag['newsPost'] = $postQuery1->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(5);

        //dd($DataBag['newsPostCount']);

        //Announcements

        $postQuery2 = Posts::with(['memberInfo'])->where('is_bookmarked', '=', 1)->where('bookmark_end_date', '>=', $cur_datev)->where('status', '=', '1');

        $postQuery2 = $postQuery2->where(function ($postQuery2) {
            $postQuery2->where('post_type', '=', '1');
            $postQuery2->orWhere(
                function ($postQuery2) {
                    $postQuery2->where('post_type', '=', '2');
                    $postQuery2->where('private_member_id', '=', Auth::user()->id);
                }
            );
        });

        $categoryCatID = 2;
        $postQuery2 = $postQuery2->where(function ($postQuery2) use ($categoryCatID) {
            $postQuery2 = $postQuery2->whereHas(
                'postCategories',
                function ($postQuery2) use ($categoryCatID) {
                    $postQuery2->where('category_id', '=', $categoryCatID);
                }
            );
        });

        $DataBag['announcementsPostCount'] = $postQuery2->count();

        $DataBag['announcementsPost'] = $postQuery2->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(5);



        //Collaboration

        $postQuery3 = Posts::with(['memberInfo'])->where('is_bookmarked', '=', 1)->where('bookmark_end_date', '>=', $cur_datev)->where('status', '=', '1');

        $postQuery3 = $postQuery3->where(function ($postQuery3) {
            $postQuery3->where('post_type', '=', '1');
            $postQuery3->orWhere(
                function ($postQuery3) {
                    $postQuery3->where('post_type', '=', '2');
                    $postQuery3->where('private_member_id', '=', Auth::user()->id);
                }
            );
        });


        $categoryCatID = 3;
        $postQuery3 = $postQuery3->where(function ($postQuery3) use ($categoryCatID) {
            $postQuery3 = $postQuery3->whereHas(
                'postCategories',
                function ($postQuery3) use ($categoryCatID) {
                    $postQuery3->where('category_id', '=', $categoryCatID);
                }
            );
        });

        $DataBag['collaborationPostCount'] = $postQuery3->count();

        $DataBag['collaborationPost'] = $postQuery3->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(5);


        //Achievements

        $postQuery4 = Posts::with(['memberInfo'])->where('is_bookmarked', '=', 1)->where('bookmark_end_date', '>=', $cur_datev)->where('status', '=', '1');

        $postQuery4 = $postQuery4->where(function ($postQuery4) {
            $postQuery4->where('post_type', '=', '1');
            $postQuery4->orWhere(
                function ($postQuery4) {
                    $postQuery4->where('post_type', '=', '2');
                    $postQuery4->where('private_member_id', '=', Auth::user()->id);
                }
            );
        });

        $categoryCatID = 4;
        $postQuery4 = $postQuery4->where(function ($postQuery4) use ($categoryCatID) {
            $postQuery4 = $postQuery4->whereHas(
                'postCategories',
                function ($postQuery4) use ($categoryCatID) {
                    $postQuery4->where('category_id', '=', $categoryCatID);
                }
            );
        });

        $DataBag['achievementsPostCount'] = $postQuery4->count();

        $DataBag['achievementsPost'] = $postQuery4->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(5);


        //Opportunities

        $postQuery5 = Posts::with(['memberInfo'])->where('is_bookmarked', '=', 1)->where('bookmark_end_date', '>=', $cur_datev)->where('status', '=', '1');

        $postQuery5 = $postQuery5->where(function ($postQuery5) {
            $postQuery5->where('post_type', '=', '1');
            $postQuery5->orWhere(
                function ($postQuery5) {
                    $postQuery5->where('post_type', '=', '2');
                    $postQuery5->where('private_member_id', '=', Auth::user()->id);
                }
            );
        });

        $categoryCatID = 5;
        $postQuery5 = $postQuery5->where(function ($postQuery5) use ($categoryCatID) {
            $postQuery5 = $postQuery5->whereHas(
                'postCategories',
                function ($postQuery5) use ($categoryCatID) {
                    $postQuery5->where('category_id', '=', $categoryCatID);
                }
            );
        });

        $DataBag['opportunitiesPostCount'] = $postQuery5->count();

        $DataBag['opportunitiesPost'] = $postQuery5->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(5);



        //post

        /*$postQuery6 = Posts::with(['memberInfo'])->where('status', '=', '1');
        $postQuery6 =$postQuery6->where(function($postQuery6) {
        $postQuery6->where('post_type', '=', '1');
        $postQuery6->orWhere(function($postQuery6) {
        $postQuery6->where('post_type', '=', '2');
        $postQuery6->where('private_member_id', '=', Auth::user()->id);
        });
        
        });*/

        $industry_category = MemberBusiness::where('member_id', '=', Auth::user()->id)->select('industry_category_id')->get();

        $category = array();

        foreach ($industry_category as $key => $value) {
            $category[$key] = $value->industry_category_id;
        }

        $postQuery6 = Posts::join('post_industry', 'post_industry.post_id', '=', 'post_master.id')->where(function ($query) use ($category) {
            $query->whereIn('post_industry.industry_category_id', $category);
        })->orWhere(function ($query) {
            $query->where('is_bookmarked', 1);
        });



        $DataBag['allPostCount'] = $postQuery6->count();

        $DataBag['allPost'] = $postQuery6->orderBy('post_master.id', 'desc')->select('post_master.*')->paginate(5);


        $DataBag['allStartUpCount'] = Users::where('status', 1)->where('user_type', 2)->whereNull('founder_id')->count();

        //dd($DataBag);

        return view('frontend.dashboard', $DataBag);
    }

    public function accountLast()
    {
        $DataBag = array();
        $DataBag['queryString'] = $_SERVER['QUERY_STRING'];

        if (empty(Auth::user()->last_login)) {
            $DataBag['normalPost'] = array();
        } else {
            if (Auth::user()->post_read == 0) {
                $postQuery = Posts::with(['memberInfo'])->where('post_master.status', '=', '1')->where('post_master.member_id', '!=', Auth::user()->id);

                $postQuery->where(function ($postQuery) {
                    $postQuery->where('post_master.created_at', '>=', Auth::user()->last_login);
                    //$postQuery->where('post_master.created_at', '<', Auth::user()->current_login);

                });

                $DataBag['normalPost'] = $postQuery->orderBy('post_type', 'desc')->orderBy('post_master.id', 'desc')->paginate(15);

                //dd($DataBag['normalPost']);

                $update_data = array(
                    'post_read' => 1,

                );

                //dd($update_data);

                //DB::enableQueryLog();

                Users::where('id', '=', Auth::user()->id)->update($update_data);
            } else {
                $DataBag['normalPost'] = array();
            }
        }

        $DataBag['mesg'] = "Sorry! No new post found.";

        //dd(DB::getQueryLog());

        //Posts::where('status', '=', '1')->update($update_data);

        return view('frontend.account', $DataBag);
    }

    public function saveMyPost(Request $request)
    {


        $category_idsArr = $request->input('category_id');

        $update_data = array('post_read' => 0,);

        Users::where('status', '=', 1)->update($update_data);

        $Posts = new Posts;
        $Posts->post_title = trim($request->input('post_title'));
        $Posts->post_info = trim(htmlentities($request->input('post_info'), ENT_QUOTES));

        $Posts->video_link = trim($request->input('video_link'));

        if ($request->file('image')) {
            //$file= $request->file('image');
            $file = $request->image;
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('/front_end/images/'), $filename);
            $Posts['image'] = $filename;
        }
        //dd($request->file('image'));


        $Posts->member_id = Auth::user()->id;

        $new_time = date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));

        $Posts->created_at = $new_time;

        //$Posts->post_type = $request->input('post_type');

        $Posts->post_type = 1;

        if ($request->input('post_type') == '2') {
            $Posts->private_member_id = $request->input('private_member_id');
            $Posts->private_sender_id = Auth::user()->id;
        }
        if ($Posts->save()) {

            $postID = $Posts->id;
            $industry_idsArr = $request->input('industry_category_id');
            if (!empty($industry_idsArr)) {
                $postIndustry = array();
                foreach ($industry_idsArr as $v) {
                    $arr = array();
                    $arr['post_id'] = $postID;
                    $arr['industry_category_id'] = $v;
                    array_push($postIndustry, $arr);
                }
                if (!empty($postIndustry)) {
                    PostIndustry::insert($postIndustry);
                }
            }

            $category_idsArr = $request->input('category_id');
            if (!empty($category_idsArr)) {
                $postCategory = array();
                foreach ($category_idsArr as $v1) {
                    $arr = array();
                    $arr['post_id'] = $postID;
                    $arr['category_id'] = $v1;
                    array_push($postCategory, $arr);
                }
                //dd($postCategory);
                if (!empty($postCategory)) {
                    PostCategory::insert($postCategory);
                }
            }


            /** Push Notification  */

            //$token =Users::select('deviceToken')->where('users.status',1)->where('user_type',2)->where('deviceToken','!=', null)->where('deviceToken','!=', '')->pluck('deviceToken')->toArray();

            //dd($token);

            //$token = json_encode($token);

            $url = "https://fcm.googleapis.com/fcm/send"; //Google api endpoint to send notification

            //devicetoken saved against user in db , can be array of tokens/fcm_id/deviceToken 
            //$token = "eA42E_6FQEiYwKJaYQxI2g:APA91bFkyX3KxcgBYt-E87LSuc5b9S-qvOuiOZZt43jdXYzNYdmknRVomBi4IkSbu-M2dPZsyHnU_ZaXeXmnJ1F_3hSOhSYWHrybazN9U8P4k4gCrk4KTArKOhwt9RCLplAQziYrDPjw"; 


            ////'your server token of FCM project'
            $serverKey = "AAAA_6ilIwA:APA91bEttkTvM4mdzwCpnPGif_KPQuUG7DIsTeIYBOvAL5EOdKXIrtsXUv_eBeA-5lVl0pVVYdQflsjWKHc6nv3BUNArua20CCJA2r3kbP2sxcDjcPg3IEbeLk2JhZTqQXnfVO3hwI7-";

            $title = "Incubatee"; //title of the message to send
            $body = "A new post is now available."; // body of the message to send

            $notification = array('title' => $title, 'body' => $body, 'sound' => 'default', 'badge' => '1');

            //'to' for one instead of  registration_ids

            //$arrayToSend = array('registration_ids' => $token, 'notification' => $notification,'priority'=>'high');
            $arrayToSend = array('notification' => $notification, 'priority' => 'high');
            $json = json_encode($arrayToSend);

            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: key=' . $serverKey;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            //Send the request
            $response = curl_exec($ch);
            //dd($response);
            //ob_end_clean();
            if (ob_get_length() > 0) {
                ob_end_clean();
            }


            //Close request
            if ($response === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);

            /** Push Notification  */


            return redirect()->route('front.user.account')
                ->with('msg', 'Your query posted successfully, thankyou')
                ->with('msg_class', 'alert alert-success');
        }
        return back();
    }

    public function logout()
    {
        $new_time = date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));

        $update_data = array(
            'last_login' => $new_time,
        );
        Users::where('id', '=', Auth::user()->id)->update($update_data);

        Auth::logout();
        Session::flush();
        return redirect()->route('signinup');
    }

    public function autoComplete(Request $request)
    {
        if (isset($_GET['query']) && $_GET['query'] != '') {

            $search = trim($_GET['query']);
            $autoCompleteArr = array();

            $postQuery = Posts::select('post_master.post_title')
                ->where('post_master.post_type', '=', '1')
                ->where(function ($postQuery) use ($search) {
                    $postQuery->where('post_master.post_title', 'LIKE', '%' . $search . '%');
                    $postQuery->orWhere('post_master.post_info', 'LIKE', '%' . $search . '%');
                })->get();
            //return response()->json($postQuery);
            /* $userQuery = Users::select('contact_name', 'member_company')
            ->where('contact_name', 'LIKE', '%' . $search . '%')
            ->orWhere('member_company', 'LIKE', '%' . $search . '%')
            ->get(); */



            $userQuery = Users::select('member_company')
                ->whereNull('founder_id')
                ->where('member_company', 'LIKE', '%' . $search . '%')
                ->whereNotIn('member_company', ['IIMCIP Portfolio Managers', 'IIMCIP MENTORS'])
                ->get();

            $companyQuery = Users::select('contact_name')
                ->distinct('contact_name')
                ->where('contact_name', 'LIKE', '%' . $search . '%')
                ->get();

            if (!empty($postQuery) && count($postQuery)) {
                foreach ($postQuery as $v) {
                    $arr = array();
                    $arr['name'] = $v->post_title;
                    array_push($autoCompleteArr, $arr);
                }
            }

            if (!empty($userQuery) && count($userQuery)) {
                foreach ($userQuery as $v) {
                    $arr = array();
                    //$arr['name'] = $v->contact_name . ' (' . $v->member_company . ')';
                    $arr['name'] = $v->member_company;
                    array_push($autoCompleteArr, $arr);
                }
            }

            if (!empty($companyQuery) && count($companyQuery)) {
                foreach ($companyQuery as $v) {
                    $arr = array();
                    //$arr['name'] = $v->contact_name . ' (' . $v->member_company . ')';
                    $arr['name'] = $v->contact_name;
                    array_push($autoCompleteArr, $arr);
                }
            }
            return response()->json($autoCompleteArr);
            //return json_encode($autoCompleteArr);
        }
    }

    public function changePassword()
    {
        $DataBag = array();
        return view('frontend.change_password', $DataBag);
    }

    public function updatePassword(Request $request)
    {
        $password = md5(trim($request->input('password')));
        $userID = Auth::user()->id;
        $Users = Users::findorFail($userID);
        $Users->password = $password;
        if ($Users->save()) {
            return back()->with('msg', 'Your password changed successfully, thankyou')
                ->with('msg_class', 'alert alert-success');
        }
        return back();
    }

    public function manageUserProfile()
    {
        $DataBag = array();

        $DataBag['company_type'] = DB::table('company_type')->orderBy('company_type', 'asc')->get();

        $user_id = Auth::user()->id;

        // $company_id =company_id(Auth::user()->id);

        $DataBag['users'] = Users::where('id', '=', $user_id)->first();
        $DataBag['industry_expertise'] = DB::table('industry_expertise')->where('status', 1)
            ->orderBy('industry_expertise', 'asc')->get();


        $DataBag['industry_category'] = DB::table('industry_category')->where('status', 1)
            ->orderBy('industry_category', 'asc')->get();
        return view('frontend.manage_profile_user', $DataBag);
    }

    public function manageProfile()
    {
        $DataBag = array();
        $DataBag['stage'] = DB::table('stage')->orderBy('stage_name', 'asc')->get();

        $DataBag['company_type'] = DB::table('company_type')->orderBy('company_type', 'asc')->get();

        $DataBag['legal_status'] = DB::table('legal_status')->orderBy('legal_status', 'asc')->get();


        $company_id = company_id(Auth::user()->id);

        $DataBag['currentIndusCats'] = MemberBusiness::where('member_id', '=', $company_id)
            ->pluck('industry_category_id')->toArray();

        $DataBag['founders'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();

        $DataBag['founderscount'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->count();

        $DataBag['buisness'] = DB::table('member_services')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();

        $DataBag['company_videos'] = DB::table('member_videos')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();

        $DataBag['company_investments'] = DB::table('member_investments')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();

        //dd($DataBag['founders']);


        /*foreach ($DataBag['buisness'] as $key => $c) {
        if(!empty($c->buisness_video))
        {
        //echo $c->buisness_video;die;
        $DataBag['buisness'][$key]->buisness_video=getYoutubeEmbedUrl($c->buisness_video);
        }
        }*/

        /*foreach ($DataBag['company_videos'] as $key1 => $c1) {
        if(!empty($c1->company_video))
        {
        //echo $c->buisness_video;die;
        $DataBag['company_videos'][$key1]->company_video=getYoutubeEmbedUrl($c1->company_video);
        }
        }*/

        //dd($DataBag['buisness']);die;

        //echo $DataBag['founderscount'];die;
        return view('frontend.manage_profile', $DataBag);
    }

    public function addUser()
    {
        $DataBag = array();

        $DataBag['industry_expertise'] = DB::table('industry_expertise')->where('status', 1)
            ->orderBy('industry_expertise', 'asc')->get();

        return view('frontend.manage_profile_sub', $DataBag);
    }

    public function addUserAction(Request $request)
    {

        $ckEmail = Users::where('email_id', trim($request->input('email_id')))
            ->where('id', '!=', Auth::user()->id)->first();
        if (!empty($ckEmail)) {
            return back()->with('msg', 'Email-id already exist, try another')
                ->with('msg_class', 'alert alert-danger')
                ->with('signupError', 'email_exist');
        }


        $founder_id = company_id(Auth::user()->id);

        $user_result = DB::table('users')->where('id', '=', $founder_id)->select('member_company', 'slug')->first();

        $Users = new Users();

        $Users->member_company = $user_result->member_company;
        $Users->slug = $user_result->slug;
        $Users->timestamp_id = md5(microtime(TRUE));

        $Users->user_type = 2;

        $Users->contact_name = trim($request->input('contact_name'));

        $Users->email_id = trim($request->input('email_id'));
        $Users->password = md5(trim($request->input('password')));

        $Users->mobile = trim($request->input('phone'));
        $Users->designation = trim($request->input('designation'));
        $Users->linkedIn = trim($request->input('linkedIn'));
        $area_of_expertise = $request->input('area_of_expertise');

        $str_expertise = '';

        if (isset($area_of_expertise) && $area_of_expertise != '') {

            foreach ($area_of_expertise as $row) {
                $str_expertise = $str_expertise . $row . ',';
            }
        }

        $Users->area_of_expertise = $str_expertise;

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $real_path = $image->getRealPath();
            $file_orgname = $image->getClientOriginalName();
            $file_size = $image->getClientSize();
            $file_ext = strtolower($image->getClientOriginalExtension());
            $file_newname = "user" . "_" . time() . "." . $file_ext;

            $destinationPath = public_path('/uploads/user_images');
            $original_path = $destinationPath . "/original";
            $thumb_path = $destinationPath . "/thumb";

            $img = Image::make($real_path);
            $img->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumb_path . '/' . $file_newname);

            $image->move($original_path, $file_newname);


            $Users->image = $file_newname;
        }

        /* if( $request->hasFile('image') ) {
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
        } */



        $Users->founder_id = company_id(Auth::user()->id);

        $Users->slug = Auth::user()->slug;


        $Users->created_by = Auth::user()->id;


        $Users->last_login = date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));


        if ($Users->save()) {

            $template_data = EmailTemplate::where('id', '=', '3')->first();
            //dd($template_data);
            $content = $template_data->description;

            $content = str_replace("{{email}}", $Users->email_id, $content);
            $content = str_replace("{{username}}", $Users->contact_name, $content);
            $content = str_replace("{{password}}", $request->input('password'), $content);

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: <no-reply@iimcip.net>' . "\r\n";
            // $headers .= 'Cc: karmickdeveloper77@gmail.com' . "\r\n";

            mail($Users->email_id, 'Add Company User', $content, $headers);

            return back()->with('msg', 'User information has been saved successfully!')
                ->with('msg_class', 'alert alert-success');
        }

        return back();
    }

    public function updateUserAction(Request $request)
    {

        $user_id = Auth::user()->id;

        $Users = Users::findOrFail($user_id);
        $Users->contact_name = trim($request->input('contact_name'));

        // $Users->email_id = trim($request->input('email_id'));
        // $Users->password = md5(trim($request->input('password')));

        $Users->mobile = trim($request->input('phone'));
        $Users->designation = trim($request->input('designation'));
        $Users->linkedIn = trim($request->input('linkedIn'));
        $Users->about_you = trim($request->input('about_you'));

        $area_of_expertise = $request->input('area_of_expertise');

        if (isset($area_of_expertise) && $area_of_expertise != '') {
            $str_expertise = '';
            foreach ($area_of_expertise as $row) {
                $str_expertise = $str_expertise . $row . ',';
            }

            $Users->area_of_expertise = $str_expertise;
        }


        $industry_category = $request->input('industry_category');

        if (isset($industry_category) && $industry_category != '') {

            $str_category = '';
            foreach ($industry_category as $row) {
                $str_category = $str_category . $row . ',';
            }
        }

        $Users->industry_category = $str_category;

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $real_path = $image->getRealPath();
            $file_orgname = $image->getClientOriginalName();
            $file_size = $image->getClientSize();
            $file_ext = strtolower($image->getClientOriginalExtension());
            $file_newname = "user" . "_" . time() . "." . $file_ext;

            $destinationPath = public_path('/uploads/user_images');
            $original_path = $destinationPath . "/original";
            $thumb_path = $destinationPath . "/thumb";

            $img = Image::make($real_path);
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumb_path . '/' . $file_newname);

            $image->move($original_path, $file_newname);


            $Users->image = $file_newname;
        }

        if ($Users->save()) {


            return back()->with('msg', 'Profile Updated successfully..!')
                ->with('msg_class', 'alert alert-success');
        }

        return back();
    }

    public function updateProfile(Request $request)
    {
        $company_id = company_id(Auth::user()->id);

        $ckEmail = Users::where('email_id', trim($request->input('email_id')))
            ->where('id', '!=', $company_id)->first();
        if (!empty($ckEmail)) {
            return back()->with('msg', 'Email-id already exist, try another')
                ->with('msg_class', 'alert alert-danger')
                ->with('signupError', 'email_exist');
        }

        $UserID = $company_id;
        $Users = Users::findOrFail($UserID);
        $Users->member_company = trim($request->input('member_company'));

        $Users->slug = Str::slug($Users->member_company, '-');

        $Users->company_name = trim($request->input('company_name'));
        $Users->stage_id = trim($request->input('stage_id'));
        $Users->email_id = trim($request->input('email_id'));
        //$Users->password = md5(trim($request->input('password')));
        $Users->company_mobile = trim($request->input('company_mobile'));
        // $Users->mobile = trim($request->input('contact_no'));
        $Users->company_code = trim($request->input('company_code'));
        $Users->affiliation = trim($request->input('affiliation'));
        $Users->operational_presence = trim($request->input('operational_presence'));
        $Users->market_reach = trim($request->input('market_reach'));
        $Users->country = trim($request->input('country'));
        $Users->city = trim($request->input('city'));
        $Users->state = trim($request->input('state'));
        $Users->district = trim($request->input('district'));
        $Users->pincode = trim($request->input('pincode'));
        $Users->incorporation = trim($request->input('incorporation'));
        $Users->website = trim($request->input('website'));
        $Users->legal_status = trim($request->input('legal_status'));
        $Users->about_you = trim(htmlentities($request->input('about_you'), ENT_QUOTES));
        $Users->address = trim($request->input('address'));



        $Users->milestone = trim($request->input('milestone'));

        $Users->buisness_info = trim($request->input('buisness_info'));

        $Users->member_spec = trim($request->input('member_spec'));
        $Users->member_looking = trim($request->input('member_looking'));
        $Users->member_help = trim($request->input('member_help'));
        $Users->achievements = trim($request->input('achievements'));
        $Users->certifications = trim($request->input('certifications'));
        $Users->company_type = trim($request->input('company_type'));

        //$Users->is_raised_invest = trim($request->input('is_raised_invest'));

        /* if($Users->is_raised_invest==1)
        {
        $Users->invest_name = trim($request->input('invest_name'));
        }
        else
        {
        $Users->invest_name = "";
        }  */

        // $Users->speech = trim($request->input('speech'));

        //dd($Users);



        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $real_path = $image->getRealPath();
            $file_orgname = $image->getClientOriginalName();
            $file_size = $image->getClientSize();
            $file_ext = strtolower($image->getClientOriginalExtension());
            $file_newname = "user" . "_" . time() . "." . $file_ext;
            $destinationPath = public_path('/uploads/user_images');
            $original_path = $destinationPath . "/original";
            $thumb_path = $destinationPath . "/thumb";

            $img = Image::make($real_path);
            $img->resize(150, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumb_path . '/' . $file_newname);

            $image->move($original_path, $file_newname);
            $Users->image = $file_newname;
        }


        if ($request->hasFile('speech')) {


            $file = $request->file('speech');

            //Display File Name
            $file_newname = $file->getClientOriginalName();
            echo '<br>';

            //Display File Extension
            echo 'File Extension: ' . $file->getClientOriginalExtension();
            echo '<br>';

            //Display File Real Path
            echo 'File Real Path: ' . $file->getRealPath();
            echo '<br>';

            //Display File Size
            echo 'File Size: ' . $file->getSize();
            echo '<br>';

            //Display File Mime Type
            echo 'File Mime Type: ' . $file->getMimeType();

            $destinationPath = public_path('/uploads/user_images');
            $file->move($destinationPath, $file->getClientOriginalName());


            $Users->speech = $file_newname;
        }

        if ($Users->save()) {

            $childs = Users::where('founder_id', '=', $company_id)->get();

            foreach ($childs as $key => $child) {
                $update_child_data = array(
                    'slug' => $Users->slug
                );

                //dd($update_data);
                Users::where('id', '=', $child->id)->update($update_child_data);
            }


            //founder
            $founder_name = $request->input('founder_name');
            $founder_profile = $request->input('founder_profile');

            $founder_linc_profile = $request->input('founder_linc_profile');

            $founder_img = $request->file('founder_img');

            //dd($founder_img);

            $founder_img_hidden = $request->input('founder_img_hidden');

            if (!empty($founder_name)) {
                if (!empty($founder_img_hidden)) {

                    $count_hidden = count($founder_img_hidden);
                    $memberFounder = array();
                    foreach ($founder_name as $key => $v) {


                        if (!empty($v)) {
                            $memberFounder[$key]['member_id'] = $Users->id;
                            $memberFounder[$key]['name'] = $v;
                            $memberFounder[$key]['profile'] = $founder_profile[$key];
                            $memberFounder[$key]['linkdin_profile'] = $founder_linc_profile[$key];

                            //$key +=$count_hidden;

                            //echo $key;die;


                            if (isset($founder_img[$key])) {
                                $image = $founder_img[$key];
                                $real_path = $image->getRealPath();
                                $file_orgname = $image->getClientOriginalName();
                                $file_size = $image->getClientSize();
                                $file_ext = strtolower($image->getClientOriginalExtension());
                                $file_newname = "founder" . "_" . time() . $key . "." . $file_ext;
                                $destinationPath = public_path('/uploads/founder_images');
                                $original_path = $destinationPath . "/original";
                                $thumb_path = $destinationPath . "/thumb";

                                $img = Image::make($real_path);
                                $img->resize(150, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->save($thumb_path . '/' . $file_newname);

                                $image->move($original_path, $file_newname);
                                $memberFounder[$key]['image'] = $file_newname;
                            } else {
                                $file_newname = $founder_img_hidden[$key];
                                $memberFounder[$key]['image'] = $file_newname;
                            }
                        }
                    }

                    //dd($memberFounder);


                    if (!empty($memberFounder)) {
                        FounderTransaction::where('member_id', '=', $UserID)->delete();
                        FounderTransaction::insert($memberFounder);
                    }
                } else {

                    $memberFounder = array();
                    foreach ($founder_name as $key => $v) {

                        if (!empty($v)) {

                            $memberFounder[$key]['member_id'] = $Users->id;
                            $memberFounder[$key]['name'] = $v;
                            $memberFounder[$key]['profile'] = $founder_profile[$key];
                            $memberFounder[$key]['linkdin_profile'] = $founder_linc_profile[$key];

                            //$key +=$count_hidden;

                            //echo $key;die;
                            if ($founder_img[$key]) {
                                $image = $founder_img[$key];
                                $real_path = $image->getRealPath();
                                $file_orgname = $image->getClientOriginalName();
                                $file_size = $image->getClientSize();
                                $file_ext = strtolower($image->getClientOriginalExtension());
                                $file_newname = "founder" . "_" . time() . $key . "." . $file_ext;
                                $destinationPath = public_path('/uploads/founder_images');
                                $original_path = $destinationPath . "/original";
                                $thumb_path = $destinationPath . "/thumb";

                                $img = Image::make($real_path);
                                $img->resize(150, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->save($thumb_path . '/' . $file_newname);

                                $image->move($original_path, $file_newname);
                                $memberFounder[$key]['image'] = $file_newname;
                            }
                        }
                    }

                    //dd($memberFounder);


                    if (!empty($memberFounder)) {
                        FounderTransaction::where('member_id', '=', $UserID)->delete();
                        FounderTransaction::insert($memberFounder);
                    }
                }
            }

            //end founder

            //service
            $buisness_caption = $request->input('buisness_caption');

            //dd($buisness_caption);die;

            //dd($buisness_caption);

            //dd($buisness_caption);
            //$buisness_website = $request->input('buisness_website');

            $buisness_img = $request->file('buisness_img');

            //dd($founder_img);

            $buisness_img_hidden = $request->input('buisness_img_hidden');

            $buisness_video = $request->input('buisness_video');

            //dd($buisness_video);
            //echo count($buisness_caption);die;
            if (count($buisness_caption)) {
                if (!empty($buisness_img_hidden)) {


                    $memberService = array();
                    foreach ($buisness_caption as $key => $v) {
                        //echo $v;die;
                        if (!empty($v)) {
                            //echo "1";die;
                            $memberService[$key]['member_id'] = $Users->id;
                            $memberService[$key]['caption'] = $v;
                            $memberService[$key]['buisness_video'] = $buisness_video[$key];
                            //$memberService[$key]['website'] = $buisness_website[$key];

                            //$key +=$count_hidden;

                            //echo $key;die;


                            if (isset($buisness_img[$key])) {
                                $image = $buisness_img[$key];
                                $real_path = $image->getRealPath();
                                $file_orgname = $image->getClientOriginalName();
                                $file_size = $image->getClientSize();
                                $file_ext = strtolower($image->getClientOriginalExtension());
                                $file_newname = "service" . "_" . time() . $key . "." . $file_ext;
                                $destinationPath = public_path('/uploads/website_images');
                                $original_path = $destinationPath . "/original";
                                $thumb_path = $destinationPath . "/thumb";

                                $img = Image::make($real_path);
                                $img->resize(150, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->save($thumb_path . '/' . $file_newname);

                                $image->move($original_path, $file_newname);
                                $memberService[$key]['image'] = $file_newname;
                            } else {
                                $file_newname = $buisness_img_hidden[$key];
                                $memberService[$key]['image'] = $file_newname;
                            }
                        }
                    }

                    //dd($memberService);


                    if (!empty($memberService)) {
                        MemberService::where('member_id', '=', $UserID)->delete();
                        MemberService::insert($memberService);
                    }
                } else {
                    $memberService = array();
                    foreach ($buisness_caption as $key => $v) {


                        if (!empty($v)) {
                            $memberService[$key]['member_id'] = $Users->id;
                            $memberService[$key]['caption'] = $v;
                            //$memberService[$key]['website'] = $buisness_website[$key];

                            //$key +=$count_hidden;

                            //echo $key;die;
                            if ($buisness_img[$key]) {
                                $image = $buisness_img[$key];
                                $real_path = $image->getRealPath();
                                $file_orgname = $image->getClientOriginalName();
                                $file_size = $image->getClientSize();
                                $file_ext = strtolower($image->getClientOriginalExtension());
                                $file_newname = "service" . "_" . time() . $key . "." . $file_ext;
                                $destinationPath = public_path('/uploads/website_images');
                                $original_path = $destinationPath . "/original";
                                $thumb_path = $destinationPath . "/thumb";

                                $img = Image::make($real_path);
                                $img->resize(150, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->save($thumb_path . '/' . $file_newname);

                                $image->move($original_path, $file_newname);
                                $memberService[$key]['image'] = $file_newname;
                            }
                        }
                    }

                    //dd($memberService);


                    if (!empty($memberService)) {
                        MemberService::where('member_id', '=', $UserID)->delete();
                        MemberService::insert($memberService);
                    }
                }
            }
            //echo "hello";die;
            //end service

            $industry_idsArr = $request->input('industry_category_id');
            if (!empty($industry_idsArr)) {
                $memberBusiness = array();
                foreach ($industry_idsArr as $v) {
                    $arr = array();
                    $arr['member_id'] = $Users->id;
                    $arr['industry_category_id'] = $v;
                    array_push($memberBusiness, $arr);
                }
                if (!empty($memberBusiness)) {
                    MemberBusiness::where('member_id', '=', $UserID)->delete();
                    MemberBusiness::insert($memberBusiness);
                }
            }

            $company_videoArr = $request->input('company_video');
            //dd($company_videoArr);

            if (!empty($company_videoArr)) {
                $memberVideo = array();
                foreach ($company_videoArr as $v1) {
                    $arr = array();

                    if (!empty($v1)) {
                        $arr['member_id'] = $Users->id;
                        $arr['company_video'] = $v1;
                        array_push($memberVideo, $arr);
                    }
                }
                if (!empty($memberVideo)) {


                    if (count($memberVideo) > 5) {
                        return back()->with('msg', 'Five Company Video allo')->with('msg_class', 'alert alert-danger');
                    }
                    MemberVideo::where('member_id', '=', $UserID)->delete();
                    MemberVideo::insert($memberVideo);
                }
            }
            //$company_invest = $request->input('company_invest');
            $company_source = $request->input('company_source');
            $company_instrument = $request->input('company_instrument');
            $company_value = $request->input('company_value');
            //dd($company_source, $company_value);

            if ($company_source) {
                $memberInvest = array();
                foreach ($company_source as $key => $v1) {
                    $arr = array();
                    //dd($v1);
                    if (!empty($v1)) {
                        $memberInvest[$key]['member_id'] = $Users->id;
                        $memberInvest[$key]['source'] = $v1;
                        $memberInvest[$key]['instrument'] = $company_instrument[$key];
                        $memberInvest[$key]['value'] = $company_value[$key];

                        /* $arr['member_id'] = $Users->id;
                        $arr['source'] = $v1; */


                        array_push($memberInvest, $arr);
                    }
                }

                $memberInvest = array_filter(array_map('array_filter', $memberInvest));
                //dd($memberInvest);
                if (!empty($memberInvest)) {
                    MemberInvestment::where('member_id', '=', $UserID)->delete();
                    MemberInvestment::insert($memberInvest);
                }
            }



            return back()->with('msg', 'Your profile saved successfully, Thanks')
                ->with('msg_class', 'alert alert-success');
        }

        return back();
    }

    public function editPost($post_id)
    {
        $DataBag = array();
        $DataBag['stage'] = DB::table('stage')->orderBy('stage_name', 'asc')->get();
        $DataBag['post'] = Posts::findOrFail($post_id);
        $DataBag['currentIndusCats'] = PostIndustry::where('post_id', '=', $post_id)
            ->pluck('industry_category_id')->toArray();

        $DataBag['currentCats'] = PostCategory::where('post_id', '=', $post_id)
            ->pluck('category_id')->toArray();
        return view('frontend.edit_post', $DataBag);
    }

    public function updatePost(Request $request, $post_id)
    {
        $Posts = Posts::findOrFail($post_id);
        $Posts->post_title = trim($request->input('post_title'));
        $Posts->post_info = trim(htmlentities($request->input('post_info'), ENT_QUOTES));

        $Posts->video_link = trim($request->input('video_link'));

        //$Posts->image = $request->file('image');


        if ($request->hasfile('image')) {
            $dest = '/public/front_end/images/' . $Posts->image;
            //echo $dest;
            if (File::exists($dest)) {
                File::delete($dest);
            }
            //$file= $request->file('image');
            $file = $request->image;
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('/front_end/images'), $filename);
            $Posts->image = $filename;
        }



        $Posts->post_type = $request->input('post_type');
        if ($request->input('post_type') == '2') {
            $Posts->private_member_id = $request->input('private_member_id');
            $Posts->private_sender_id = Auth::user()->id;
        } else {
            $Posts->private_member_id = '0';
        }
        if ($Posts->save()) {

            //dd($Posts);

            $postID = $Posts->id;
            $industry_idsArr = $request->input('industry_category_id');
            if (!empty($industry_idsArr)) {
                $postIndustry = array();
                foreach ($industry_idsArr as $v) {
                    $arr = array();
                    $arr['post_id'] = $postID;
                    $arr['industry_category_id'] = $v;
                    array_push($postIndustry, $arr);
                }
                if (!empty($postIndustry)) {
                    PostIndustry::where('post_id', '=', $post_id)->delete();
                    PostIndustry::insert($postIndustry);
                }
            }

            $category_idsArr = $request->input('category_id');
            if (!empty($category_idsArr)) {
                $postCategory = array();
                foreach ($category_idsArr as $v1) {
                    $arr = array();
                    $arr['post_id'] = $postID;
                    $arr['category_id'] = $v1;
                    array_push($postCategory, $arr);
                }
                if (!empty($postCategory)) {
                    PostCategory::where('post_id', '=', $post_id)->delete();
                    PostCategory::insert($postCategory);
                }
            }
            return back()->with('msg', 'Your post updated successfully, thankyou')
                ->with('msg_class', 'alert alert-success');
        }
        return back();
    }

    public function myPosts()
    {
        $DataBag = array();
        $DataBag['myPosts'] = Posts::where('member_id', '=', Auth::user()->id)
            ->orderBy('id', 'desc')->paginate(15);
        return view('frontend.list_post', $DataBag);
    }

    public function myFavPosts()
    {
        $DataBag = array();
        $DataBag['myPosts'] = Posts::join('post_favourates', 'post_favourates.post_id', '=', 'post_master.id')->where('post_favourates.member_id', '=', Auth::user()->id)
            ->orderBy('post_master.id', 'desc')->select('post_master.*')->paginate(15);



        return view('frontend.my_fav_post', $DataBag);
    }

    public function ajaxAddComment(Request $request)
    {
        $status = '';
        $DataBag = array();

        //dd($request->all());
        $post_id = $request->input('post_id');
        $reply_text = $request->input('reply_text');
        $replied_on = $request->input('replied_on');
        $replied_by = Auth::user()->id;

        $PostReply = new PostReply;
        $PostReply->post_id = $post_id;
        $PostReply->reply_text = $reply_text;
        $PostReply->replied_on = $replied_on;
        $PostReply->replied_by = $replied_by;

        $PostReply->video_url = $request->input('video_url');
        if ($PostReply->save()) {

            //post image upload
            $files = $request->file('images');

            if ($request->hasFile('images')) {

                foreach ($files as $key => $image) {

                    $real_path = $image->getRealPath();
                    $file_orgname = $image->getClientOriginalName();
                    $file_size = $image->getClientSize();
                    $file_ext = strtolower($image->getClientOriginalExtension());
                    $file_newname = "post" . "_" . time() . $key . "_" . $PostReply->id . '.' . $file_ext;;
                    $destinationPath = public_path('/uploads/posts');
                    $original_path = $destinationPath . "/images";

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






            //post video end



            $DataBag['postInfo'] = $PostReply;
            $DataBag['postInfo']['image'] = PostMedia::where('status', 1)->where('media_type', 'I')->where('post_reply_id', $PostReply->id)->get();

            $DataBag['postInfo']['video'] = PostMedia::where('status', 1)->where('media_type', 'V')->where('post_reply_id', $PostReply->id)->get();

            $DataBag['memberInfo'] = Users::find($replied_by);
            $commentCount = PostReply::where('post_id', '=', $post_id)->count();
            $replyCount = PostReply::where('replied_on', '=', $replied_on)->count();
            //return view('frontend.comment_render', $DataBag);
            $status = 'ok';
            $repComtHtml = view('frontend.comment_render', $DataBag)->render();
            return response()->json(['repComtHtml' => $repComtHtml, 'commentCount' => $commentCount, 'replyCount' => $replyCount, 'status' => $status]);
        }
        return response()->json(['status' => $status]);
    }

    public function ajaxAddFavorate(Request $request)
    {
        $status = '';
        $DataBag = array();

        //dd($request->all());
        $post_id = $request->input('post_id');
        $user_id = $request->input('user_id');
        $status = $request->input('status');

        if ($status == 1) {
            //remove favourite

            DB::table('post_favourates')->where('member_id', '=', $user_id)->where('post_id', '=', $post_id)->delete();
        } else {
            //add favourite

            $favourite = array();

            $favourite['member_id'] = $user_id;
            $favourite['post_id'] = $post_id;

            DB::table('post_favourates')->insert($favourite);
        }


        $status = 'ok';
        return response()->json(['status' => $status]);
    }

    public function sendMail(Request $request)
    {
        $email = $request->input('email');
        $title = $request->input('title');
        $info = $request->input('info');

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: <dipanwitachanda1991@gmail.com>' . "\r\n";
        $headers .= 'Cc: karmickdeveloper77@gmail.com' . "\r\n";

        mail($email, $title, $info, $headers);

        return back()->with('msg', 'Mail Sent Successfully..!')
            ->with('msg_class', 'alert alert-success');
    }

    public function memberProfile($member_company)
    {
        //echo $member_company;die;
        //$member_company =html_entity_decode($member_company);
        $user = DB::table('users')->where('slug', '=', $member_company)->first();

        $company_id = company_id($user->id);

        $DataBag = array();
        $DataBag['memberInfo'] = Users::join('stage', 'stage.id', '=', 'users.stage_id')->where('users.id', '=', $company_id)->select('users.*', 'stage.stage_name')->first();


        if (isset($DataBag['memberInfo']->company_type)) {
            $DataBag['company_type'] = CompanyType::where('id', $DataBag['memberInfo']->company_type)->first();
        }

        $DataBag['number_of_post'] = Posts::where('member_id', '=', $company_id)->where('status', '=', '1')->count();

        $DataBag['number_of_reply'] = PostReply::where('replied_by', '=', $company_id)->where('status', '=', '1')->count();


        $DataBag['user_id'] = Auth::user()->id;


        // $DataBag['founders'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();

        // $DataBag['founderscount'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->count();

        $founder_id = $company_id;
        $DataBag['founders'] = Users::where('users.status', 1)->where('user_type', 2)->where('founder_id', $founder_id)->get();

        foreach ($DataBag['founders'] as $key => $user) {
            $area_of_expertise = explode(',', $user->area_of_expertise);

            $str = '';
            foreach ($area_of_expertise as $row) {
                if ($row != '') {
                    $ar = DB::table('industry_expertise')->where('id', '=', $row)->first();

                    if (isset($ar)) {
                        $str .= $ar->industry_expertise . ', ';
                    }
                }
            }

            $DataBag['founders'][$key]['area_of_expertise'] = rtrim($str, ', ');
        }



        $DataBag['founderscount'] = Users::where('users.status', 1)->where('user_type', 2)->where('founder_id', $founder_id)->count();

        $DataBag['buisness'] = DB::table('member_services')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();

        /*foreach ($DataBag['buisness'] as $key => $c) {
        if(!empty($c->buisness_video))
        {
        //echo $c->buisness_video;die;
        $DataBag['buisness'][$key]->buisness_video=getYoutubeEmbedUrl($c->buisness_video);
        }
        }*/

        $DataBag['company_videos'] = DB::table('member_videos')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();

        $DataBag['company_investments'] = DB::table('member_investments')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();


        /*if(count($DataBag['company_videos'])>0)
        {
        foreach ($DataBag['company_videos'] as $key1 => $c1) {
        if(!empty($c1->company_video))
        {
        //echo $c->buisness_video;die;
        $DataBag['company_videos'][$key1]->company_video=getYoutubeEmbedUrl($c1->company_video);
        }
        }
        }*/




        //dd($DataBag);

        $DataBag['buisnesscount'] = DB::table('member_services')->where('member_id', '=', $company_id)->count();

        return view('frontend.member_info', $DataBag);
    }
    public function export()
    {
        $results = DB::table('entrepreneurs')->join('users_dup', 'users_dup.id', '=', 'entrepreneurs.id')->where('users_dup.status', 'Active')->select('entrepreneurs.start_up_stage', 'entrepreneurs.id', 'users_dup.name', 'users_dup.photo', 'entrepreneurs.founding_year', 'entrepreneurs.website', 'entrepreneurs.fund_require', 'entrepreneurs.summary_start_up', 'users_dup.phone', 'users_dup.user_email', 'users_dup.address')->get();

        $insert_array = array();

        foreach ($results as $key => $value) {

            $insert_array[$key]['id'] = $value->id;

            $insert_array[$key]['user_type'] = 2;
            $insert_array[$key]['timestamp_id'] = md5(microtime(TRUE));

            $insert_array[$key]['member_company'] = $value->name;
            $insert_array[$key]['website'] = $value->website;
            $insert_array[$key]['stage_id'] = $value->start_up_stage;
            $insert_array[$key]['about_you'] = $value->summary_start_up;
            $insert_array[$key]['contact_no'] = $value->phone;
            $insert_array[$key]['email_id'] = $value->user_email;
            $insert_array[$key]['address'] = $value->address;



            $insert_array[$key]['image'] = $value->photo;
        }

        DB::table('users')->insert($insert_array);
    }

    public function export_all()
    {

        $results = DB::table('users')->get();

        $insert_array = array();

        //dd($results);






        foreach ($results as $key => $value) {

            $update_data = array(
                'contact_name' => $value->member_company
            );

            //dd($update_data);
            Users::where('id', '=', $value->id)->update($update_data);
        }

        DB::table('users')->insert($insert_array);
    }


    public function mentorStartup()
    {

        $user_id = Auth::user()->id;

        $ids_array = DB::table('member_mentor_rel')->where('mentor_id', '=', $user_id)->get()->toArray();
        $ids = array();
        foreach ($ids_array as $key => $value) {
            $ids[] = $value->member_id;
        }
        //dd($ids);
        // $users = DB::table('users')->whereIn('id', $ids)
        //           ->where('status', 1);

        $users = Users::whereIn('users.id', $ids);

        // $users =Users::where('users.status',1)->where('user_type',2)->whereNull('founder_id');

        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = trim($_GET['search']);
            if (strpos($search, '(') !== false) {
                $search = trim(substr($search, 0, strpos($search, '(')));
            }
            $users->where('users.member_company', 'LIKE', '%' . $search . '%');
        }
        if (isset($_GET['searr_id']) && $_GET['searr_id'] != '') {

            //echo $_GET['search_id'];die;

            $search1 = trim($_GET['searr_id']);
            if (strpos($search1, '(') !== false) {
                $search1 = trim(substr($search1, 0, strpos($search1, '(')));
            }
            $users->where('member_company', 'LIKE', '' . $search1 . '%');
        }

        if (isset($_GET['industry_id']) && $_GET['industry_id'] != '') {

            //echo $_GET['search_id'];die;

            $users = $users->join('member_business', 'users.id', '=', 'member_business.member_id');

            $search2 = trim($_GET['industry_id']);
            if (strpos($search2, '(') !== false) {
                $search2 = trim(substr($search2, 0, strpos($search2, '(')));
            }
            $users->where('member_business.industry_category_id', $search2);
        }

        $users = $users->orderBy('users.member_company', 'asc')->select('users.*')->get();

        $DataBag['users'] = array();

        //dd($users->toArray());

        foreach ($users as $key => $user) {
            $company_id = $user->id;


            //echo $user->id;die;
            $DataBag['users'][$key]['memberInfo'] = Users::join('stage', 'stage.id', '=', 'users.stage_id')->where('users.id', '=', $company_id)->select('users.*', 'stage.stage_name')->first();

            $DataBag['users'][$key]['number_of_post'] = Posts::where('member_id', '=', $company_id)->where('status', '=', '1')->count();

            $DataBag['users'][$key]['number_of_reply'] = PostReply::where('replied_by', '=', $company_id)->where('status', '=', '1')->count();


            $DataBag['users'][$key]['founders'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();

            $DataBag['users'][$key]['founderscount'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->count();
        }

        //dd($DataBag);



        return view('frontend.startup_mentor', $DataBag);
    }
    public function mentorIncubeReports()
    {

        return view('frontend.mentor_incube_report_list');
    }

    public function allcheckpost(Request $request)
    {

        //if($request->post_filter){

        /* $postdata = $request->postdata;
        
        $postAjaxData['ajaxPostData'] = DB::table('post_categories')
        ->join('categories', 'post_categories.category_id', '=', 'categories.id')
        ->join('post_master', 'post_categories.post_id', '=', 'post_master.id')
        ->select('post_master.post_title','categories.name')
        ->whereIn('categories.id', explode(',',$postdata ))
        ->get(); 
        } */

        $postData = array();

        $postData['industry_category'] = DB::table('industry_category')->where('status', 1)
            ->orderBy('industry_category', 'asc')->get();


        $postData['category'] = DB::table('categories')->where('status', 1)
            ->orderBy('name', 'asc')->get();


        if (isset($request->all_post_filter)) {

            $allPostData = $request->all_post_filter;

            return redirect()->route('front.user.account', $allPostData);

            //dd('OK');

        }


        if (isset($request->post_filter)) {

            //$checkData = $request->post_filter;
            $checkData = implode(",", $request->post_filter);


            $postData['checkPosts'] = DB::table('post_categories')
                ->join('categories', 'post_categories.category_id', '=', 'categories.id')
                ->join('post_master', 'post_categories.post_id', '=', 'post_master.id')
                ->join('users', 'post_master.member_id', '=', 'users.id')
                ->select('post_master.*', 'post_master.id as pmID', 'categories.*', 'post_categories.*', 'users.*', 'post_master.image as pmImage')
                ->where('categories.id', '=', $checkData)
                ->orderBy('post_master.id', 'desc')
                //->whereIn('categories.id',$checkData)
                //->whereIn('categories.id', explode(",", $checkData['id']))
                ->paginate(10);
        }


        if (isset($request->industry_filter)) {

            $checkData = $request->industry_filter;

            $postData['checkIndustries'] = DB::table('post_industry')
                ->join('industry_category', 'post_industry.industry_category_id', '=', 'industry_category.id')
                ->join('post_master', 'post_industry.post_id', '=', 'post_master.id')
                ->join('users', 'post_master.member_id', '=', 'users.id')
                ->select('post_master.*', 'post_master.id as pmID', 'post_industry.*', 'industry_category.*', 'users.*', 'post_master.image as pmImage')
                ->where('industry_category.id', '=', $checkData)
                ->orderBy('post_master.id', 'desc')
                ->paginate(10);
            //dd('OK');
            //dd($postData['checkIndustries']);

            //return redirect()->route('front.user.account', $checkData);
            //dd('ok');



        }

        return view('frontend.post_category_search', $postData);
    }

    public function notification()
    {


        $postData = array();

        /* $postData['industry_category'] = DB::table('industry_category')
        ->join('users','industry_category.id','=','users.industry_category')
        ->get(); */


        //dd($postData['industry_category']);
        //$company_id =$user->id;



        //$industryInfo = MemberBusiness::with('industryInfo')->where('member_id','=', Auth::user()->id)->get();
        $industryInfo = DB::table('industry_category')
            ->join('member_business', 'industry_category.id', '=', 'member_business.industry_category_id')
            ->select('member_business.industry_category_id')
            ->where('member_business.member_id', '=', Auth::user()->id)
            ->get();

        $data = json_decode(json_encode($industryInfo), true);
        //dd($data);


        $postData['notification'] = DB::table('post_industry')
            ->join('industry_category', 'post_industry.industry_category_id', '=', 'industry_category.id')
            ->join('post_master', 'post_industry.post_id', '=', 'post_master.id')
            ->join('users', 'post_master.member_id', '=', 'users.id')
            ->select('post_master.*', 'post_master.id as pmID', 'post_industry.*', 'industry_category.*', 'industry_category.id as industryId', 'industry_category.industry_category as industryInfo', 'users.*', 'post_master.image as pmImage')
            //->whereIn('slug',['admin','iimcip-mentors', 'iimcip-portfolio-managers'])
            //->whereIn('post_master.member_id',[1, Auth::user()->id])
            ->where('industry_category_id', '=', $data)
            ->orWhere('post_master.member_id', [1, Auth::user()->id])
            ->orderBy('post_master.id', 'desc')
            ->paginate(10);
        //->get(); 
        //dd($postData['notification']); 

        //$postData['comment'] = Posts::with('totalPostComment')->get();

        //dd($postData['comment']);

        return view('frontend.post_category_search', $postData);
    }

    public function publicProfile()
    {
        $DataBag = array();
        $DataBag['stage'] = DB::table('stage')->orderBy('stage_name', 'asc')->get();

        $DataBag['company_type'] = DB::table('company_type')->orderBy('company_type', 'asc')->get();

        $DataBag['legal_status'] = DB::table('legal_status')->orderBy('legal_status', 'asc')->get();


        $company_id = company_id(Auth::user()->id);

        $DataBag['currentIndusCats'] = MemberBusiness::where('member_id', '=', $company_id)
            ->pluck('industry_category_id')->toArray();

        $DataBag['founders'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();

        $DataBag['founderscount'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->count();

        $DataBag['buisness'] = DB::table('member_services')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();

        $DataBag['company_videos'] = DB::table('member_videos')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();

        //$DataBag['company_investments'] = DB::table('member_investments')->where('member_id', '=',$company_id)->orderBy('id', 'asc')->get();

        //dd($DataBag['founders']);


        /*foreach ($DataBag['buisness'] as $key => $c) {
        if(!empty($c->buisness_video))
        {
        //echo $c->buisness_video;die;
        $DataBag['buisness'][$key]->buisness_video=getYoutubeEmbedUrl($c->buisness_video);
        }
        }*/

        /*foreach ($DataBag['company_videos'] as $key1 => $c1) {
        if(!empty($c1->company_video))
        {
        //echo $c->buisness_video;die;
        $DataBag['company_videos'][$key1]->company_video=getYoutubeEmbedUrl($c1->company_video);
        }
        }*/

        //dd($DataBag['buisness']);die;

        //echo $DataBag['founderscount'];die;
        return view('frontend.public_info', $DataBag);
    }

    public function privateProfile()
    {

        $DataBag = array();

        $company_id = company_id(Auth::user()->id);

        $DataBag['company_investments'] = DB::table('member_investments')->where('member_id', '=', $company_id)->orderBy('id', 'Desc')->get();

        //  $DataBag['company_investments'] = DB::table('member_investments')->orderBy('id', 'asc')->get();

        return view('frontend.private_info', $DataBag);
    }

    public function addCustomer()
    {
        $DataBag = array();
        $company_id = company_id(Auth::user()->id);


        $DataBag['startup_cust'] = StartupCustomer::where('startup_id', '=', $company_id)->orderBy('id', 'Desc')->get();

        return view('frontend.add_customer', $DataBag);
    }

    public function addCustomerAction(Request $request)
    {
        $request->validate([
            'customer_name' => 'required'
        ]);

        $cust = new StartupCustomer;

        $cust->customer_name = $request->input('customer_name');
        $cust->startup_id = Auth::user()->id;

        $cust->save();

        return back()->with('msg', 'Customer Name has been saved successfully!')
            ->with('msg_class', 'alert alert-success');


        return back();
    }

    public function custDestroy($id)
    {
        $custs = StartupCustomer::findOrFail($id);
        $custs->delete();

        return redirect()->back()->with('success', 'Customer Deleted Successfully');
    }

    public function custEdit($id)
    {

        $DataBag['custs'] = StartupCustomer::findOrFail($id);

        return view('frontend.edit_customer', $DataBag);
    }

    public function custUpdate(Request $request, $id)
    {
        $custs = StartupCustomer::findOrFail($id);
        $request->validate([
            'customer_name' => 'required'
        ]);

        $custs->customer_name = $request->customer_name;
        $custs->startup_id = Auth::user()->id;
        $custs->save();

        return redirect()->back()->with('msg', 'Customer Updated Successfully')->with('msg_class', 'alert alert-success');;
    }

    public function addFinancial()
    {
        //
        dd("fddddd");
        $company_id = company_id(Auth::user()->id);

        $DataBag['financials'] = StartupYearlyFinancial::where('startup_id', '=', $company_id)->orderBy('id', 'desc')->get();

        $DataBag['finYear'] = FinancialYear::orderBy('id', 'desc')->get();

        return view('frontend.financials', $DataBag);
    }

    public function addFinancialAction(Request $request)
    {
        $request->validate([
            'financial_year' => 'required'
        ]);

        $fin = new StartupYearlyFinancial;

        $fin->startup_id = Auth::user()->id;

        $fin->financial_year = $request->input('financial_year');

        $fin->revenue = $request->input('revenue');

        $fin->gmv = $request->input('gmv');

        $fin->expense = $request->input('expense');

        $fin->customer_count = $request->input('customer_count');

        $fin->ebitda = $request->input('ebitda');

        $fin->net_profit = $request->input('net_profit');


        $fin->save();

        return back()->with('msg', 'Financial Report has been saved successfully!')
            ->with('msg_class', 'alert alert-success');


        // return back();
    }

    #sumantra dey
    public function financialDestroy($id)
    {
        $custs = StartupYearlyFinancial::findOrFail($id);
        $custs->delete();
        // StartupMonthlySale::where('startup_id', $custs->startup_id)
        //     ->where('financial_year', $custs->financial_year)->delete();

        return redirect()->back()->with('msg', 'Financial Report Deleted Successfully')
            ->with('msg_class', 'alert alert-success');
    }

    public function financialEdit($id)
    {

        $DataBag['finance'] = StartupYearlyFinancial::findOrFail($id);

        return view('frontend.edit_finance', $DataBag);
    }

    public function financialUpdate(Request $request, $id)
    {
        $finance = StartupYearlyFinancial::findOrFail($id);

        $request->validate([
            'financial_year' => 'required'
        ]);


        $finance->startup_id = Auth::user()->id;

        $finance->financial_year = $request->input('financial_year');

        $finance->revenue = $request->input('revenue');

        $finance->gmv = $request->input('gmv');

        $finance->expense = $request->input('expense');

        $finance->customer_count = $request->input('customer_count');

        $finance->ebitda = $request->input('ebitda');

        $finance->net_profit = $request->input('net_profit');


        $finance->save();

        return redirect()->back()->with('msg', 'Financial Year Updated Successfully')->with('msg_class', 'alert alert-success');
    }

    public function addFinancialMonth()
    {

        $company_id = company_id(Auth::user()->id);

        $DataBag['financials'] = StartupMonthlySale::where('startup_id', '=', $company_id)->orderBy('id', 'desc')->get();

        $DataBag['allProducts'] = MemberService::orderBy('id', 'Desc')->get();

        //$DataBag['products'] = StartupMonthlySale::with('getProducts')->get();
        $DataBag['productList'] = StartupMonthlySale::where('startup_id', '=', $company_id)->with('getProducts')->get();

        //dd($DataBag['productList']);


        return view('frontend.financials_month', $DataBag);
    }

    public function addFinancialMonthAction(Request $request)
    {
        $request->validate([
            'financial_year' => 'required'
        ]);

        $prods = $request->product_id;

        foreach ($prods as $prod) {

            $finsale = new StartupMonthlySale;

            $finsale->startup_id = Auth::user()->id;

            $finsale->month = $request->input('month');

            $finsale->financial_year = $request->input('financial_year');

            $finsale->product_id = $prod;

            $finsale->volume = $request->input('volume');

            $finsale->credit_sale = $request->input('credit_sale');

            $finsale->cash_sale = $request->input('cash_sale');

            $finsale->save();
        }

        return back()->with('msg', 'Financial Monthly Sales has been saved successfully!')
            ->with('msg_class', 'alert alert-success');

        // return back();
    }
    #sumantra dey   
    public function financialMonthDestroy($id)
    {
        $finmonth = StartupMonthlySale::findOrFail($id);
        $revenue = $finmonth->credit_sale + $finmonth->cash_sale;
        $finmonth->delete();

       
        // echo '<pre>'; print_r($revenue); exit;


        // $count_yearly_sales = StartupMonthlySale::where('startup_id', $finmonth->startup_id)
        //     ->where('financial_year', $finmonth->financial_year)->count();

        // DB::enableQueryLog();
        // if ($count_yearly_sales > 1) {
        StartupYearlyFinancial::where('startup_id', $finmonth->startup_id)
            ->where('financial_year', $finmonth->financial_year)
            // ->decrement('revenue', )
            ->update([
                'revenue' =>  DB::raw("revenue - $revenue"),
                'net_profit' => DB::raw("revenue - expense")

            ]);

            // $year =   StartupYearlyFinancial::where('startup_id', $finmonth->startup_id)
            // ->where('financial_year', $finmonth->financial_year)
            // // ->decrement('revenue', )
            // ->select([
            //       DB::raw("revenue - $revenue"),
            //       DB::raw("revenue - expense")

            // ])->get();

        // if($StartupYearlyFinancial){
        // $StartupYearlyFinancial->net_profit = $StartupYearlyFinancial->revenue - $StartupYearlyFinancial->expense;
        // $StartupYearlyFinancial->save();
        // }
        // } else {
        //     StartupYearlyFinancial::where('startup_id', $finmonth->startup_id)
        //         ->where('financial_year', $finmonth->financial_year)->delete();
        // }
        // dd(DB::getQueryLog());
        return redirect()->back()->with('msg', 'Financial Month Deleted Successfully')->with('msg_class', 'alert alert-success');
    }

    public function financialMonthEdit($id)
    {

        $DataBag['finmonth'] = StartupMonthlySale::findOrFail($id);

        $DataBag['allProducts'] = MemberService::orderBy('id', 'Desc')->get();

        return view('frontend.edit_finance_month', $DataBag);
    }

    public function financialMonthUpdate(Request $request, $id)
    {


        $finmonth = StartupMonthlySale::findOrFail($id);

        $request->validate([
            'financial_year' => 'required'
        ]);

        $prods = $request->product_id;

        foreach ($prods as $prod) {

            $finmonth->startup_id = Auth::user()->id;

            $finmonth->month = $request->input('month');

            $finmonth->financial_year = $request->input('financial_year');

            $finmonth->product_id = $prod;

            $finmonth->volume = $request->input('volume');

            $finmonth->credit_sale = $request->input('credit_sale');

            $finmonth->cash_sale = $request->input('cash_sale');

            $finmonth->save();
        }

        return redirect()->back()->with('msg', 'Financial Month Updated Successfully')->with('msg_class', 'alert alert-success');;
    }



    public function addFinancialExpenses()
    {

        dd("ddddd");
        $company_id = company_id(Auth::user()->id);

        $DataBag['finex'] = StartupMonthlyExpenditure::where('startup_id', '=', $company_id)->orderBy('id', 'desc')->get();

        $DataBag['finYear'] = FinancialYear::orderBy('id', 'desc')->get();
        $DataBag['finMonth'] = FinancialMonth::orderBy('id', 'desc')->get();

        // dd($DataBag['finex']);


        return view('frontend.financial_expenses', $DataBag);
    }

    public function addFinancialExpenseAction(Request $request)
    {

        $request->validate([
            'financial_year' => 'required'
        ]);

        $finexp = new StartupMonthlyExpenditure;

        $finexp->startup_id = Auth::user()->id;

        $finexp->month = $request->input('month');

        $finexp->financial_year = $request->input('financial_year');

        $finexp->raw_material = $request->input('raw_material');

        $finexp->salary_wages = $request->input('salary_wages');

        $finexp->other_expenses = $request->input('other_expenses');

        $finexp->capex = $request->input('capex');

        $finexp->save();

        return back()->with('msg', 'Financial Expenses has been saved successfully!')
            ->with('msg_class', 'alert alert-success');
    }

    public function financialExpenseEdit($id)
    {

        $DataBag['finexpense'] = StartupMonthlyExpenditure::findOrFail($id);


        return view('frontend.edit_finance_expense', $DataBag);
    }


    public function financialExpenseUpdate(Request $request, $id)
    {

        $finexpense = StartupMonthlyExpenditure::findOrFail($id);

        $request->validate([
            'financial_year' => 'required'
        ]);


        $finexpense->startup_id = Auth::user()->id;

        $finexpense->month = $request->input('month');

        $finexpense->financial_year = $request->input('financial_year');

        $finexpense->raw_material = $request->input('raw_material');

        $finexpense->salary_wages = $request->input('salary_wages');

        $finexpense->other_expenses = $request->input('other_expenses');

        $finexpense->capex = $request->input('capex');

        $finexpense->save();

        return back()->with('msg', 'Financial Expenses has been Updated successfully!')
            ->with('msg_class', 'alert alert-success');
    }
    #sumantra
    public function financialExpenseDestroy($id)
    {
        // dd("frontend controller");

        $finexpense = StartupMonthlyExpenditure::findOrFail($id);
        $update_expense = 
        $finexpense->raw_material + $finexpense->salary_wages + $finexpense->other_expenses + $finexpense->capex;

        $finexpense->delete();

       



        $StartupYearlyFinancial = StartupYearlyFinancial::where('startup_id', $finexpense->startup_id)
            ->where('financial_year', $finexpense->financial_year)
            //->decrement('expense', $update_expense);
            ->update([
                'expense' =>    DB::raw("expense - $update_expense"),
                'net_profit' => DB::raw("revenue - expense")

            ]);
       


        return redirect()->back()->with('msg', 'Financial Expenses Deleted Successfully')->with('msg_class', 'alert alert-success');
    }



    public function addOrderPipeline()
    {

        $company_id = company_id(Auth::user()->id);

        // $DataBag['orders'] = StartupMonthlyOrderPipeline::where('company_id','=', $company_id)->orderBy('id', 'desc')->get();

        $DataBag['allProducts'] = MemberService::where('member_id', '=', $company_id)->orderBy('id', 'Desc')->get();

        $DataBag['finYear'] = FinancialYear::orderBy('id', 'desc')->get();
        $DataBag['finMonth'] = FinancialMonth::orderBy('id', 'desc')->get();

        //$DataBag['products'] = StartupMonthlySale::with('getProducts')->get();
        $DataBag['productList'] = StartupMonthlyOrderPipeline::where('startup_id', '=', $company_id)->with('getProducts')->get();

        // dd($DataBag['finex']);


        return view('frontend.order_pipeline', $DataBag);
    }

    public function addOrderPipelineAction(Request $request)
    {

        $request->validate([
            'financial_year' => 'required'
        ]);

        $prods = $request->product_id;

        foreach ($prods as $prod) {

            $orderspipe = new StartupMonthlyOrderPipeline;

            $orderspipe->startup_id = Auth::user()->id;

            $orderspipe->month = $request->input('month');

            $orderspipe->financial_year = $request->input('financial_year');

            $orderspipe->product_id = $prod;

            $orderspipe->volume = $request->input('volume');

            $orderspipe->amount = $request->input('amount');


            $orderspipe->save();
        }

        return back()->with('msg', 'Order Pipeline has been saved successfully!')
            ->with('msg_class', 'alert alert-success');
    }


    public function OrderPipelineEdit($id)
    {

        $DataBag['orderpipe'] = StartupMonthlyOrderPipeline::findOrFail($id);

        $DataBag['allProducts'] = MemberService::orderBy('id', 'Desc')->get();

        return view('frontend.edit_order_pipe', $DataBag);
    }

    public function OrderPipelineUpdate(Request $request, $id)
    {
        $orderpipe = StartupMonthlyOrderPipeline::findOrFail($id);

        $request->validate([
            'financial_year' => 'required',
            'product_id' => 'required|regex:/^[\pL\s\-]+$/u',
            'volume' => 'required|numeric',
            'amount' =>'required|numeric'
        ]);

        $prods = $request->product_id;

      //  foreach ($prods as $prod) {


            $orderpipe->startup_id = Auth::user()->id;

            $orderpipe->month = $request->input('month');

            $orderpipe->financial_year = $request->input('financial_year');

            $orderpipe->product_id = $prods;

            $orderpipe->volume = $request->input('volume');

            $orderpipe->amount = $request->input('amount');


            $orderpipe->save();
     //   }

        return redirect()->back()->with('msg', 'Order Pipeline Updated Successfully')->with('msg_class', 'alert alert-success');;
    }



    public function OrderPipelineDestroy($id)
    {
        $orderpipe = StartupMonthlyOrderPipeline::findOrFail($id);
        $orderpipe->delete();

        return redirect()->back()->with('msg', 'Financial Month Deleted Successfully')->with('msg_class', 'alert alert-success');
    }

    public function addYearlyTarget()
    {
        $company_id = company_id(Auth::user()->id);

        $DataBag['targets'] = StartupYearlyTarget::where('startup_id', '=', $company_id)->orderBy('id', 'desc')->get();

        // dd($DataBag['finex']);

        return view('frontend.yearly_target', $DataBag);
    }

    public function addYearlyTargetAction(Request $request)
    {

        $yearlytargets = new StartupYearlyTarget;

        $yearlytargets->startup_id = Auth::user()->id;

        $yearlytargets->financial_year = $request->input('financial_year');

        $yearlytargets->revenue = $request->input('revenue');

        $yearlytargets->volume = $request->input('volume');

        $yearlytargets->save();

        return back()->with('msg', 'Yearly Targets has been saved successfully!')
            ->with('msg_class', 'alert alert-success');
    }

    public function yearlyTargetEdit($id)
    {

        $DataBag['targets'] = StartupYearlyTarget::findOrFail($id);


        return view('frontend.edit_yearly_target', $DataBag);
    }


    public function yearlyTargetUpdate(Request $request, $id)
    {

        $targets = StartupYearlyTarget::findOrFail($id);

        $request->validate([
            'financial_year' => 'required'
        ]);


        $targets->startup_id = Auth::user()->id;

        $targets->financial_year = $request->input('financial_year');

        $targets->revenue = $request->input('revenue');

        $targets->volume = $request->input('volume');

        $targets->save();

        return back()->with('msg', 'Yearly Target has been Updated successfully!')
            ->with('msg_class', 'alert alert-success');
    }

    public function yearlyTargetDestroy($id)
    {
        $targets = StartupYearlyTarget::findOrFail($id);
        $targets->delete();

        return redirect()->back()->with('msg', 'Yearly Target Deleted Successfully')->with('msg_class', 'alert alert-success');
    }







    public function addImpacts()
    {

        $company_id = company_id(Auth::user()->id);

        //$DataBag['impacts'] = StartupImpact::where('startup_id','=', $company_id)->orderBy('id', 'desc')->get();

        $DataBag['impacts'] = StartupImpact::with(['getFinancialYear', 'getFinancialMonth'])->where('startup_id', '=', $company_id)->orderBy('id', 'desc')->get();

        $DataBag['finYear'] = FinancialYear::orderBy('id', 'desc')->get();

        $DataBag['finMonth'] = FinancialMonth::orderBy('id', 'desc')->get();

        // dd($DataBag['finex']);

        return view('frontend.impacts', $DataBag);
    }

    public function addImpactsAction(Request $request)
    {

        $impacts = new StartupImpact;

        $impacts->startup_id = Auth::user()->id;

        $impacts->month = $request->input('month');

        $impacts->financial_year = $request->input('financial_year');

        $impacts->head_count_ft = $request->input('head_count_ft');

        $impacts->head_count_pt = $request->input('head_count_pt');

        $impacts->women_employees_ft = $request->input('women_employees_ft');

        $impacts->women_employees_pt = $request->input('women_employees_pt');

        $impacts->save();

        return back()->with('msg', 'Impacts has been saved successfully!')
            ->with('msg_class', 'alert alert-success');
    }

    public function impactsEdit($id)
    {

        $DataBag['impact'] = StartupImpact::findOrFail($id);


        return view('frontend.edit_impact', $DataBag);
    }


    public function impactsUpdate(Request $request, $id)
    {

        $impact = StartupImpact::findOrFail($id);

        $request->validate([
            'indirect_employees' => 'required'
        ]);

        $impact->startup_id = Auth::user()->id;

        $impact->indirect_employees = $request->input('indirect_employees');

        $impact->employee_count = $request->input('employee_count');

        $impact->women_employee_count = $request->input('women_employee_count');

        $impact->total_beneficiaries = $request->input('total_beneficiaries');

        $impact->save();

        return back()->with('msg', 'Impacts has been Updated successfully!')
            ->with('msg_class', 'alert alert-success');
    }

    public function impactsDestroy($id)
    {
        $impact = StartupImpact::findOrFail($id);
        $impact->delete();

        return redirect()->back()->with('msg', 'Impacts Deleted Successfully')->with('msg_class', 'alert alert-success');
    }







    public function addFundingNeeds()
    {


        $company_id = company_id(Auth::user()->id);

        $DataBag['funds'] = StartupFundingNeed::where('startup_id', '=', $company_id)->orderBy('id', 'desc')->get();

        // dd($DataBag['finex']);

        return view('frontend.funding_needs', $DataBag);
    }

    public function addFundingNeedsAction(Request $request)
    {

        $funds = new StartupFundingNeed;

        $funds->startup_id = Auth::user()->id;

        $funds->funding_requirement = $request->input('funding_requirement');

        $funds->save();

        return back()->with('msg', 'Funding Requirement has been saved successfully!')
            ->with('msg_class', 'alert alert-success');
    }

    public function fundingNeedsEdit($id)
    {

        $DataBag['funds'] = StartupFundingNeed::findOrFail($id);


        return view('frontend.edit_funding_needs', $DataBag);
    }


    public function fundingNeedsUpdate(Request $request, $id)
    {

        $funds = StartupFundingNeed::findOrFail($id);

        $request->validate([
            'funding_requirement' => 'required'
        ]);

        $funds->startup_id = Auth::user()->id;

        $funds->funding_requirement = $request->input('funding_requirement');

        $funds->save();

        return back()->with('msg', 'Funding Need has been Updated successfully!')
            ->with('msg_class', 'alert alert-success');
    }

    public function fundingNeedsDestroy($id)
    {
        $funds = StartupFundingNeed::findOrFail($id);
        $funds->delete();

        return redirect()->back()->with('msg', 'Funding Need Deleted Successfully')->with('msg_class', 'alert alert-success');
    }





    public function addComplianceCheck()
    {

        $company_id = company_id(Auth::user()->id);

        $DataBag['compliance'] = StartupComplianceCheck::where('startup_id', '=', $company_id)->orderBy('id', 'desc')->get();


        // dd($DataBag['finex']);

        return view('frontend.compliance_check', $DataBag);
    }

    public function addComplianceCheckAction(Request $request)
    {

        $compliance = new StartupComplianceCheck;

        $compliance->startup_id = Auth::user()->id;

        $compliance->audited_financials = $request->input('audited_financials');
        $compliance->gst_compliance = $request->input('gst_compliance');

        $compliance->save();

        return back()->with('msg', 'Compliance check has been saved successfully!')
            ->with('msg_class', 'alert alert-success');
    }

    public function complianceChecksEdit($id)
    {

        $DataBag['compliance'] = StartupComplianceCheck::findOrFail($id);


        return view('frontend.edit_compliance_check', $DataBag);
    }


    public function complianceChecksUpdate(Request $request, $id)
    {

        $compliance = StartupComplianceCheck::findOrFail($id);

        $request->validate([
            'audited_financials' => 'required'
        ]);

        $compliance->startup_id = Auth::user()->id;

        $compliance->audited_financials = $request->input('audited_financials');
        $compliance->gst_compliance = $request->input('gst_compliance');

        $compliance->save();

        return back()->with('msg', 'Compliance check has been Updated successfully!')
            ->with('msg_class', 'alert alert-success');
    }

    public function complianceChecksDestroy($id)
    {
        $compliance = StartupComplianceCheck::findOrFail($id);
        $compliance->delete();

        return redirect()->back()->with('msg', 'Compliance check Deleted Successfully')->with('msg_class', 'alert alert-success');
    }
}
