<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndustryCategories;
use App\Models\MemberBusiness;
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
use Session;
use Image;
use View;
use Auth;
use DB;
use Mail;
use Illuminate\Support\Str;

class FrontendController extends Controller
{
    public function __construct(Request $request) 
	{	
        
        //dd(\Auth::admin());
        $shareData = array();

        



        $shareData['industry_category'] = DB::table('industry_category')->where('status', 1)
        ->orderBy('industry_category', 'asc')->get();


        $shareData['industry_category_show'] = DB::table('industry_category')->where('status', 1)->where('id','!=', 7)
        ->orderBy('industry_category', 'asc')->get();

        $shareData['category'] = DB::table('categories')->where('status', 1)
        ->orderBy('name', 'asc')->get();

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

        $Users = new Users;
        $Users->user_type = 2;
        $Users->timestamp_id = md5(microtime(TRUE));

        

        $Users->member_company = trim($request->input('member_company'));
        $Users->slug  =Str::slug($Users->member_company, '-');
        $Users->contact_name = trim($request->input('contact_name'));
        $Users->stage_id = trim($request->input('stage_id'));
        $Users->email_id = trim($request->input('email_id'));
        $Users->password = md5(trim($request->input('password')));
        $Users->contact_no = trim($request->input('contact_no'));
        $Users->mobile = trim($request->input('contact_no'));
        $Users->country = trim($request->input('country'));
        $Users->city = trim($request->input('city'));
        $Users->website = trim($request->input('website'));
        $Users->legal_status = trim($request->input('legal_status'));


        $Users->last_login =date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));

        $Users->current_login =date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));

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
            $img->resize(150, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumb_path.'/'.$file_newname);

            $image->move($original_path, $file_newname);
            $Users->image = $file_newname;
        }

        if($Users->save()) {
            $industry_idsArr = $request->input('industry_category_id');
            if(!empty($industry_idsArr)){
                $memberBusiness = array();
                foreach($industry_idsArr as $v) {
                    $arr = array();
                    $arr['member_id'] = $Users->id;
                    $arr['industry_category_id'] = $v;
                    array_push($memberBusiness, $arr);
                }
                if (!empty($memberBusiness)) {
                    MemberBusiness::insert($memberBusiness);
                }
            }

            $email_id = trim($request->input('email_id'));
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

    	if(!empty($loginUser)) {
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
		],[
			'email_id.required' => 'Please enter email-id.',
			'password.required' => 'Please enter password.'
		]);

    	$email_id = trim($request->input('email_id'));
    	$password = md5(trim($request->input('password')));
    	$rm_me = trim($request->input('rm_me'));
    	$norPwd = trim($request->input('password'));


        
    	$loginUser = Users::where('email_id', '=', $email_id)
    	->where('password', '=', $password)->where('user_type', 2)->where('status', '=', '1')->first(); 

        $new_time = date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));

    	if(!empty($loginUser)) {
    		Auth::login($loginUser);

            $user_id = Auth::user()->id;
            $current_login = Auth::user()->current_login;

            if(empty($current_login))
            {
                $current_login =$new_time;
            }

            $update_data = array(
                'current_login' => $new_time
            );
            Users::where('id', '=', $user_id)->update($update_data);

    		if( $rm_me == '1' ) {
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
            return redirect()->route('front.user.feed');
            

    	} else {
    		return back()->with('msg', 'Sorry! Incorrect Login Information.');
    	}
    }

    public function account()
    {
        

        $DataBag = array();
        $DataBag['queryString'] = $_SERVER['QUERY_STRING'];
        
        $postQuery = Posts::with(['memberInfo'])->where('status', '=', '1');

        if (isset($_GET['post']) && $_GET['post'] != '') {
            $postFilter = trim($_GET['post']);
            if ($postFilter == 'public') {
                $postQuery = $postQuery->where(function($postQuery) {
                    $postQuery->where('post_type', '=', '1');
                });
            }
            if ($postFilter == 'private') {
                $postQuery = $postQuery->where(function($postQuery) {
                    $postQuery->where('post_type', '=', '2');
                    $postQuery->where('private_member_id', '=', Auth::user()->id);
                    $postQuery->orWhere('private_sender_id', '=', Auth::user()->id);

                });
            }
            if ($postFilter == 'administrator') {
                $postQuery = $postQuery->where(function($postQuery) {
                    $postQuery->where('post_type', '=', '1');
                    $postQuery->where('member_id', '=', '1');
                });
            }

        } else {
            $postQuery = $postQuery->where(function($postQuery) {
                $postQuery->where('post_type', '=', '1');
                $postQuery->orWhere(function($postQuery) {
                    $postQuery->where('post_type', '=', '2');
                    $postQuery->where('private_member_id', '=', Auth::user()->id);
                });
                // $postQuery->orWhere(function($postQuery) {
                //     $postQuery->where('post_type', '=', '2');
                //     $postQuery->where('member_id', '=', Auth::user()->id);
                // });
            });
            //dd($postQuery->toSql());
        }
        if (isset($_GET['industry']) && $_GET['industry'] != '') {
            $industryCatID = base64_decode($_GET['industry']);
            $postQuery = $postQuery->where( function($postQuery) use($industryCatID) {
                $postQuery = $postQuery->whereHas('postIndistries', function ($postQuery) use($industryCatID) {
                    $postQuery->where('industry_category_id', '=', 7);
                    $postQuery->orWhere('industry_category_id', '=', $industryCatID);
                });
            });
        }
        if (isset($_GET['category']) && $_GET['category'] != '') {
            $categoryCatID = base64_decode($_GET['category']);
            $postQuery = $postQuery->where( function($postQuery) use($categoryCatID) {
                $postQuery = $postQuery->whereHas('postCategories', function ($postQuery) use($categoryCatID) {
                    $postQuery->where('category_id', '=', $categoryCatID);
                });
            });
        }
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = trim($_GET['search']);
            if (strpos($search, '(') !== false) {
                $search = trim(substr($search, 0, strpos($search, '(')));
            }
            $postQuery = $postQuery->where( function($postQuery) use($search) {
                $postQuery->where('post_title', 'LIKE', '%' . $search . '%');
                $postQuery->orWhere('post_info', 'LIKE', '%' . $search . '%');
                $postQuery->orWhereHas('memberInfo', function($postQuery) use ($search) {
                    $postQuery->where('contact_name', 'LIKE', '%' . $search . '%');
                    $postQuery->orWhere('member_company', 'LIKE', '%' . $search . '%');
                });
            });
        }
        $DataBag['normalPost'] = $postQuery->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(15);

        //dd($DataBag['normalPost']);

        $DataBag['mesg']  ="Sorry! No post found.";
        
        return view('frontend.account', $DataBag);
    }

    public function feed()
    {   

        $DataBag = array();

        //echo Auth::user()->id;die;

        $industry_category =MemberBusiness::where('member_id', '=', Auth::user()->id)->select('industry_category_id')->get();

        $category=array();

        foreach ($industry_category as $key => $value) {
            $category[$key]=$value->industry_category_id;
        }

        $myString = '1,2,3';
        $myArray = explode(',', $myString);
        //dd($myArray);
        //dd($category);

        //DB::enableQueryLog();



        $DataBag['myPosts'] = Posts::join('post_industry', 'post_industry.post_id', '=', 'post_master.id')->where(function ($query) use ($category) {
                $query->whereIn('post_industry.industry_category_id', $category);
            })->orWhere(function($query) {
                $query->where('is_bookmarked', 1);   
            })
        ->orderBy('post_master.id', 'desc')->select('post_master.*')->paginate(15);


        //dd($DataBag['myPosts']->toArray());

        //dd(DB::getQueryLog());

        $DataBag['mesg']  ="Sorry! No post found.";
        return view('frontend.feed_post', $DataBag);
    }
    public function startup()
    {
        $users =Users::where('users.status',1)->where('user_type',2)->whereNull('founder_id');

        //echo "hello";die;


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

            $users =$users->join('member_business', 'users.id', '=', 'member_business.member_id');

            $search2 = trim($_GET['industry_id']);
            if (strpos($search2, '(') !== false) {
                $search2 = trim(substr($search2, 0, strpos($search2, '(')));
            }
            $users->where('member_business.industry_category_id',$search2 );
                    
        }

        $users =$users->orderBy('users.member_company', 'asc')->select('users.*')->get();

        $DataBag['users'] = array();

        //dd($users->toArray());

        foreach ($users as $key => $user) {
            $company_id =$user->id;


           //echo $user->id;die;
        $DataBag['users'][$key]['memberInfo'] = Users::join('stage', 'stage.id', '=', 'users.stage_id')->where('users.id', '=', $company_id)->select('users.*','stage.stage_name')->first();

        

        $DataBag['users'][$key]['number_of_post']=Posts::where('member_id', '=', $company_id)->where('status', '=', '1')->count();

        $DataBag['users'][$key]['number_of_reply']=PostReply::where('replied_by', '=', $company_id)->where('status', '=', '1')->count();


        $DataBag['users'][$key]['founders'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();

        $DataBag['users'][$key]['founderscount'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->count();


        
        }

        //dd($DataBag);

        

        return view('frontend.startup', $DataBag);
    }

    public function bookmark()
    {
        

        $DataBag = array();
        $DataBag['queryString'] = $_SERVER['QUERY_STRING'];

        $cur_datev=date('Y-m-d');

        
        $postQuery = Posts::with(['memberInfo'])->where('is_bookmarked', '=', 1)->where('bookmark_end_date', '>=', $cur_datev)->where('status', '=', '1');



        if (isset($_GET['post']) && $_GET['post'] != '') {
            $postFilter = trim($_GET['post']);
            if ($postFilter == 'public') {
                $postQuery = $postQuery->where(function($postQuery) {
                    $postQuery->where('post_type', '=', '1');
                });
            }
            if ($postFilter == 'private') {
                $postQuery = $postQuery->where(function($postQuery) {
                    $postQuery->where('post_type', '=', '2');
                    $postQuery->where('private_member_id', '=', Auth::user()->id);
                    $postQuery->orWhere('private_sender_id', '=', Auth::user()->id);
                });
            }
            if ($postFilter == 'administrator') {
                $postQuery = $postQuery->where(function($postQuery) {
                    $postQuery->where('post_type', '=', '1');
                    $postQuery->where('member_id', '=', '1');
                });
            }

        } else {

            //echo "1";die;
            $postQuery = $postQuery->where(function($postQuery) {
                $postQuery->where('post_type', '=', '1');
                $postQuery->orWhere(function($postQuery) {
                    $postQuery->where('post_type', '=', '2');
                    $postQuery->where('private_member_id', '=', Auth::user()->id);
                });
               
            });
            //dd($postQuery->toSql());
        }

        //dd($postQuery->get());
        if (isset($_GET['industry']) && $_GET['industry'] != '') {
            $industryCatID = base64_decode($_GET['industry']);
            $postQuery = $postQuery->where( function($postQuery) use($industryCatID) {
                $postQuery = $postQuery->whereHas('postIndistries', function ($postQuery) use($industryCatID) {
                    $postQuery->where('industry_category_id', '=', $industryCatID);
                });
            });
        }
        if (isset($_GET['category']) && $_GET['category'] != '') {
            $categoryCatID = base64_decode($_GET['category']);
            $postQuery = $postQuery->where( function($postQuery) use($categoryCatID) {
                $postQuery = $postQuery->whereHas('postCategories', function ($postQuery) use($categoryCatID) {
                    $postQuery->where('category_id', '=', $categoryCatID);
                });
            });
        }
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = trim($_GET['search']);
            if (strpos($search, '(') !== false) {
                $search = trim(substr($search, 0, strpos($search, '(')));
            }
            $postQuery = $postQuery->where( function($postQuery) use($search) {
                $postQuery->where('post_title', 'LIKE', '%' . $search . '%');
                $postQuery->orWhereHas('memberInfo', function($postQuery) use ($search) {
                    $postQuery->where('contact_name', 'LIKE', '%' . $search . '%');
                    $postQuery->orWhere('member_company', 'LIKE', '%' . $search . '%');
                });
            });
        }
        $DataBag['normalPost'] = $postQuery->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(15);

        //dd($DataBag['normalPost']);

        $DataBag['mesg']  ="Sorry! No post found.";
        
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


        $cur_datev=date('Y-m-d');

        $postQuery1 = Posts::with(['memberInfo'])->where('is_bookmarked', '=', 1)->where('bookmark_end_date', '>=', $cur_datev)->where('status', '=', '1');

        $postQuery1 =$postQuery1->where(function($postQuery1) {
                $postQuery1->where('post_type', '=', '1');
                $postQuery1->orWhere(function($postQuery1) {
                    $postQuery1->where('post_type', '=', '2');
                    $postQuery1->where('private_member_id', '=', Auth::user()->id);
                });
               
            });

        $categoryCatID = 1;
        $postQuery1 = $postQuery1->where( function($postQuery1) use($categoryCatID) {
                $postQuery1 = $postQuery1->whereHas('postCategories', function ($postQuery1) use($categoryCatID) {
                    $postQuery1->where('category_id', '=', $categoryCatID);

                });
        });

        $DataBag['newsPostCount'] =$postQuery1->count();

        $DataBag['newsPost'] = $postQuery1->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(5);

        //dd($DataBag['newsPostCount']);

        //Announcements

        $postQuery2 = Posts::with(['memberInfo'])->where('is_bookmarked', '=', 1)->where('bookmark_end_date', '>=', $cur_datev)->where('status', '=', '1');

        $postQuery2 =$postQuery2->where(function($postQuery2) {
                $postQuery2->where('post_type', '=', '1');
                $postQuery2->orWhere(function($postQuery2) {
                    $postQuery2->where('post_type', '=', '2');
                    $postQuery2->where('private_member_id', '=', Auth::user()->id);
                });
               
            });

        $categoryCatID = 2;
        $postQuery2 = $postQuery2->where( function($postQuery2) use($categoryCatID) {
                $postQuery2 = $postQuery2->whereHas('postCategories', function ($postQuery2) use($categoryCatID) {
                    $postQuery2->where('category_id', '=', $categoryCatID);
                });
        });

        $DataBag['announcementsPostCount'] =$postQuery2->count();

        $DataBag['announcementsPost'] = $postQuery2->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(5);



        //Collaboration

        $postQuery3 = Posts::with(['memberInfo'])->where('is_bookmarked', '=', 1)->where('bookmark_end_date', '>=', $cur_datev)->where('status', '=', '1');

        $postQuery3 =$postQuery3->where(function($postQuery3) {
                $postQuery3->where('post_type', '=', '1');
                $postQuery3->orWhere(function($postQuery3) {
                    $postQuery3->where('post_type', '=', '2');
                    $postQuery3->where('private_member_id', '=', Auth::user()->id);
                });
               
            });


        $categoryCatID = 3;
        $postQuery3 = $postQuery3->where( function($postQuery3) use($categoryCatID) {
                $postQuery3 = $postQuery3->whereHas('postCategories', function ($postQuery3) use($categoryCatID) {
                    $postQuery3->where('category_id', '=', $categoryCatID);
                });
        });

        $DataBag['collaborationPostCount'] =$postQuery3->count();

        $DataBag['collaborationPost'] = $postQuery3->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(5);


        //Achievements

        $postQuery4 = Posts::with(['memberInfo'])->where('is_bookmarked', '=', 1)->where('bookmark_end_date', '>=', $cur_datev)->where('status', '=', '1');

        $postQuery4 =$postQuery4->where(function($postQuery4) {
                $postQuery4->where('post_type', '=', '1');
                $postQuery4->orWhere(function($postQuery4) {
                    $postQuery4->where('post_type', '=', '2');
                    $postQuery4->where('private_member_id', '=', Auth::user()->id);
                });
               
            });

        $categoryCatID = 4;
        $postQuery4 = $postQuery4->where( function($postQuery4) use($categoryCatID) {
                $postQuery4 = $postQuery4->whereHas('postCategories', function ($postQuery4) use($categoryCatID) {
                    $postQuery4->where('category_id', '=', $categoryCatID);
                });
        });

        $DataBag['achievementsPostCount'] =$postQuery4->count();

        $DataBag['achievementsPost'] = $postQuery4->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(5);


        //Opportunities

        $postQuery5 = Posts::with(['memberInfo'])->where('is_bookmarked', '=', 1)->where('bookmark_end_date', '>=', $cur_datev)->where('status', '=', '1');

        $postQuery5 =$postQuery5->where(function($postQuery5) {
                $postQuery5->where('post_type', '=', '1');
                $postQuery5->orWhere(function($postQuery5) {
                    $postQuery5->where('post_type', '=', '2');
                    $postQuery5->where('private_member_id', '=', Auth::user()->id);
                });
               
            });

        $categoryCatID = 5;
        $postQuery5 = $postQuery5->where( function($postQuery5) use($categoryCatID) {
                $postQuery5 = $postQuery5->whereHas('postCategories', function ($postQuery5) use($categoryCatID) {
                    $postQuery5->where('category_id', '=', $categoryCatID);
                });
        });

        $DataBag['opportunitiesPostCount'] =$postQuery5->count();

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

        $industry_category =MemberBusiness::where('member_id', '=', Auth::user()->id)->select('industry_category_id')->get();

        $category=array();

        foreach ($industry_category as $key => $value) {
            $category[$key]=$value->industry_category_id;
        }

        $postQuery6 =Posts::join('post_industry', 'post_industry.post_id', '=', 'post_master.id')->where(function ($query) use ($category) {
                $query->whereIn('post_industry.industry_category_id', $category);
            })->orWhere(function($query) {
                $query->where('is_bookmarked', 1);   
            });
        


        $DataBag['allPostCount'] =$postQuery6->count();

        $DataBag['allPost'] = $postQuery6->orderBy('post_master.id', 'desc')->select('post_master.*')->paginate(5);


        $DataBag['allStartUpCount'] =Users::where('status',1)->where('user_type',2)->whereNull('founder_id')->count();





        //dd($DataBag);


        
        
        return view('frontend.dashboard', $DataBag);
    }

    public function accountLast()
    {
        $DataBag = array();
        $DataBag['queryString'] = $_SERVER['QUERY_STRING'];

        if(empty(Auth::user()->last_login))
        {
            $DataBag['normalPost'] =array();
        }
        else
        {
            if(Auth::user()->post_read ==0)
            {
                $postQuery = Posts::with(['memberInfo'])->where('post_master.status', '=', '1')->where('post_master.member_id','!=',Auth::user()->id);


            
                $postQuery->where(function($postQuery) {
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
            }
            else
            {
                $DataBag['normalPost'] =array();
            }
        }

        $DataBag['mesg']  ="Sorry! No new post found.";


        
        
        
        //dd(DB::getQueryLog());
        
        


        //Posts::where('status', '=', '1')->update($update_data);
        
        return view('frontend.account', $DataBag);
    }

    public function saveMyPost(Request $request)
    {
        $category_idsArr = $request->input('category_id');


        $update_data = array(
                        'post_read' => 0,

                    );

        Users::where('status', '=', 1)->update($update_data);



        $Posts = new Posts;
        $Posts->post_title = trim($request->input('post_title'));
        $Posts->post_info = trim(htmlentities($request->input('post_info'), ENT_QUOTES));

        $Posts->video_link = trim($request->input('video_link'));
        $Posts->member_id = Auth::user()->id;

        $new_time = date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes')); 

        $Posts->created_at = $new_time;

        
        $Posts->post_type = $request->input('post_type');
        if($request->input('post_type') == '2') {
            $Posts->private_member_id = $request->input('private_member_id');
            $Posts->private_sender_id = Auth::user()->id;
        }
        if ($Posts->save()) {

            
            $postID = $Posts->id;
            $industry_idsArr = $request->input('industry_category_id');
            if(!empty($industry_idsArr)){
                $postIndustry = array();
                foreach($industry_idsArr as $v) {
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
            if(!empty($category_idsArr)){
                $postCategory = array();
                foreach($category_idsArr as $v1) {
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
            ->where(function($postQuery) use($search) {
                $postQuery->where('post_master.post_title', 'LIKE', '%' . $search . '%');
                $postQuery->orWhere('post_master.post_info', 'LIKE', '%' . $search . '%');
            })->get();
            //return response()->json($postQuery);
            $userQuery = Users::select('contact_name', 'member_company')
            ->where('contact_name', 'LIKE', '%' . $search . '%')
            ->orWhere('member_company', 'LIKE', '%' . $search . '%')
            ->get();
            
            if (!empty($postQuery) && count($postQuery)) {
                foreach($postQuery as $v) {
                    $arr = array();
                    $arr['name'] = $v->post_title;
                    array_push($autoCompleteArr, $arr);
                }
            }

            if (!empty($userQuery) && count($userQuery)) {
                foreach($userQuery as $v) {
                    $arr = array();
                    $arr['name'] = $v->contact_name . ' (' . $v->member_company . ')';
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

    public function manageProfile()
    {
        $DataBag = array();
        $DataBag['stage'] = DB::table('stage')->orderBy('stage_name', 'asc')->get();


        $company_id =company_id(Auth::user()->id);

        $DataBag['currentIndusCats'] = MemberBusiness::where('member_id', '=', $company_id)
        ->pluck('industry_category_id')->toArray();

        $DataBag['founders'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();

        $DataBag['founderscount'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->count();

         $DataBag['buisness'] = DB::table('member_services')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();

         $DataBag['company_videos'] = DB::table('member_videos')->where('member_id', '=',$company_id)->orderBy('id', 'asc')->get();


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

        $Users =new Users();


        $Users->timestamp_id = md5(microtime(TRUE));

        $Users->user_type    =2;
      
        $Users->contact_name = trim($request->input('contact_name'));
       
        $Users->email_id = trim($request->input('email_id'));
        $Users->password = md5(trim($request->input('password')));

        $Users->founder_id = company_id(Auth::user()->id);

        $Users->slug = Auth::user()->slug;


        $Users->created_by = Auth::user()->id;


        $Users->last_login =date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));
       

       

        if($Users->save()) {

            $template_data = EmailTemplate::where('id', '=', '3')->first();
                //dd($template_data);
            $content = $template_data->description;

            
            $content = str_replace("{{email}}", $Users->email_id, $content);
            $content = str_replace("{{username}}", $Users->contact_name, $content);
            $content = str_replace("{{password}}", $request->input('password'), $content);
           

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: <admin@iimcip.com>' . "\r\n";
            $headers .= 'Cc: karmickdeveloper77@gmail.com' . "\r\n";

            mail($Users->email_id,'Add Company User',$content,$headers);

            return back()->with('msg', 'User saved successfully, Thanks')
            ->with('msg_class', 'alert alert-success');
        }

        return back();
    }

    public function updateProfile(Request $request)
    {
        $company_id =company_id(Auth::user()->id);
        
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

        $Users->slug  =Str::slug($Users->member_company, '-');
        
        $Users->contact_name = trim($request->input('contact_name'));
        $Users->stage_id = trim($request->input('stage_id'));
        $Users->email_id = trim($request->input('email_id'));
        $Users->password = md5(trim($request->input('password')));
        $Users->contact_no = trim($request->input('contact_no'));
        $Users->mobile = trim($request->input('contact_no'));
        $Users->country = trim($request->input('country'));
        $Users->city = trim($request->input('city'));
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

        //dd($Users);



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
            $img->resize(150, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumb_path.'/'.$file_newname);

            $image->move($original_path, $file_newname);
            $Users->image = $file_newname;
        }

        if($Users->save()) {

            $childs =Users::where('founder_id', '=', $company_id)->get();

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

            $founder_img=$request->file('founder_img');

            //dd($founder_img);

            $founder_img_hidden=$request->input('founder_img_hidden');

            if(!empty($founder_name))
            {
                if(!empty($founder_img_hidden)){

                    $count_hidden =count($founder_img_hidden);
                    $memberFounder = array();
                    foreach($founder_name as $key=>$v) {

                        
                        if(!empty($v))
                        {
                            $memberFounder[$key]['member_id'] = $Users->id;
                            $memberFounder[$key]['name'] = $v;
                            $memberFounder[$key]['profile'] = $founder_profile[$key];
                            $memberFounder[$key]['linkdin_profile'] = $founder_linc_profile[$key];

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

                    //dd($memberFounder);

                    
                    if (!empty($memberFounder)) {
                        FounderTransaction::where('member_id', '=', $UserID)->delete();
                        FounderTransaction::insert($memberFounder);
                    }
                }
                else
                {
                    
                    $memberFounder = array();
                    foreach($founder_name as $key=>$v) {

                        if(!empty($v))
                        {
                        
                            $memberFounder[$key]['member_id'] = $Users->id;
                            $memberFounder[$key]['name'] = $v;
                            $memberFounder[$key]['profile'] = $founder_profile[$key];
                            $memberFounder[$key]['linkdin_profile'] = $founder_linc_profile[$key];

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
                            $memberService[$key]['member_id'] = $Users->id;
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

                    //dd($memberService);

                    
                    if (!empty($memberService)) {
                        MemberService::where('member_id', '=', $UserID)->delete();
                        MemberService::insert($memberService);
                    }
                }
                else
                {
                    $memberService = array();
                    foreach($buisness_caption as $key=>$v) {

                        
                        if(!empty($v))
                        {
                            $memberService[$key]['member_id'] = $Users->id;
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
            if(!empty($industry_idsArr)){
                $memberBusiness = array();
                foreach($industry_idsArr as $v) {
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
                    MemberVideo::where('member_id', '=', $UserID)->delete();
                    MemberVideo::insert($memberVideo);
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
        
        $Posts->post_type = $request->input('post_type');
        if($request->input('post_type') == '2') {
            $Posts->private_member_id = $request->input('private_member_id');
            $Posts->private_sender_id = Auth::user()->id;
        } else {
            $Posts->private_member_id = '0';
        }
        if ($Posts->save()) {
            $postID = $Posts->id;
            $industry_idsArr = $request->input('industry_category_id');
            if(!empty($industry_idsArr)){
                $postIndustry = array();
                foreach($industry_idsArr as $v) {
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
            if(!empty($category_idsArr)){
                $postCategory = array();
                foreach($category_idsArr as $v1) {
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
            $files=$request->file('images');

            if($request->hasFile('images'))
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
            

                

            

            //post video end



            $DataBag['postInfo'] = $PostReply;
            $DataBag['postInfo']['image'] =PostMedia::where('status',1)->where('media_type','I')->where('post_reply_id',$PostReply->id)->get();

            $DataBag['postInfo']['video'] =PostMedia::where('status',1)->where('media_type','V')->where('post_reply_id',$PostReply->id)->get();

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

        if($status==1)
        {
            //remove favourite

            DB::table('post_favourates')->where('member_id', '=', $user_id)->where('post_id', '=', $post_id)->delete();
        }
        else
        {
            //add favourite

            $favourite =array();

            $favourite['member_id'] = $user_id;
            $favourite['post_id'] = $post_id;

            DB::table('post_favourates')->insert($favourite);
        }
       

        $status = 'ok';
        return response()->json(['status' => $status]);
    }

    public function memberProfile($member_company)
    {
        //echo $member_company;die;
        //$member_company =html_entity_decode($member_company);
        $user =DB::table('users')->where('slug', '=', $member_company)->first();

        $company_id =company_id($user->id);


        $DataBag = array();
        $DataBag['memberInfo'] = Users::join('stage', 'stage.id', '=', 'users.stage_id')->where('users.id', '=', $company_id)->select('users.*','stage.stage_name')->first();

        $DataBag['number_of_post']=Posts::where('member_id', '=', $company_id)->where('status', '=', '1')->count();

        $DataBag['number_of_reply']=PostReply::where('replied_by', '=', $company_id)->where('status', '=', '1')->count();


        $DataBag['founders'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();

        $DataBag['founderscount'] = DB::table('founder_transactions')->where('member_id', '=', $company_id)->count();


        $DataBag['buisness'] = DB::table('member_services')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();

        /*foreach ($DataBag['buisness'] as $key => $c) {


             if(!empty($c->buisness_video))
              {
                 //echo $c->buisness_video;die;
                 $DataBag['buisness'][$key]->buisness_video=getYoutubeEmbedUrl($c->buisness_video);
              }
         }*/

        $DataBag['company_videos'] = DB::table('member_videos')->where('member_id', '=', $company_id)->orderBy('id', 'asc')->get();


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
        $results =DB::table('entrepreneurs')->join('users_dup', 'users_dup.id', '=', 'entrepreneurs.id')->where('users_dup.status', 'Active')->select('entrepreneurs.start_up_stage', 'entrepreneurs.id', 'users_dup.name', 'users_dup.photo', 'entrepreneurs.founding_year', 'entrepreneurs.website', 'entrepreneurs.fund_require', 'entrepreneurs.summary_start_up','users_dup.phone','users_dup.user_email','users_dup.address')->get();

        $insert_array=array();

        foreach ($results as $key => $value) {

            $insert_array[$key]['id'] = $value->id;

            $insert_array[$key]['user_type'] = 2;
            $insert_array[$key]['timestamp_id'] = md5(microtime(TRUE));
           
            $insert_array[$key]['member_company'] =$value->name;
            $insert_array[$key]['website'] =$value->website;
            $insert_array[$key]['stage_id'] =$value->start_up_stage;
            $insert_array[$key]['about_you'] =$value->summary_start_up;
             $insert_array[$key]['contact_no'] =$value->phone;
            $insert_array[$key]['email_id'] =$value->user_email;
            $insert_array[$key]['address'] =$value->address;


            
            $insert_array[$key]['image'] =$value->photo;

        }

        DB::table('users')->insert($insert_array);
        
    }

    public function export_all()
    {
        
        $results =DB::table('users')->get();

        $insert_array=array();

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
}
