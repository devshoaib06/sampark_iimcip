<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MemberBusiness;
use App\Models\Users;
use App\Models\Posts;
use App\Models\PostReply;
use App\Models\Incubatee;
use App\Models\TodoMaster;
use App\Models\ResponseBrief;
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
use App\Models\FounderTransaction;
use App\Models\MemberDiagnostic;
use App\Models\MemberInvestment;
use App\Models\MemberService;
use App\Models\MemberVideo;
use App\Models\Parameter;
use App\Models\Question;
use App\Models\StartupComplianceCheck;
use App\Models\StartupCustomer;
use App\Models\StartupFundingNeed;
use App\Models\StartupImpact;
use App\Models\StartupMonthlyExpenditure;
use App\Models\StartupMonthlyOrderPipeline;
use App\Models\StartupMonthlySale;
use App\Models\StartupYearlyFinancial;
use App\Models\StartupYearlyTarget;
use Illuminate\Support\Facades\File;
use Symfony\Component\VarDumper\Cloner\Data;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class IncubationController extends Controller
{
    public function allSite()
    {

        //dd('ok');

        return view('incubation.test');
    } // End Method

    public function index()
    {
        $DataBag = array();
        $DataBag['stage'] = DB::table('stage')->orderBy('stage_name', 'asc')->get();
        return view('incubation.index', $DataBag);
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


        // dd($loginUser);

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
                return redirect()->route('incubatee.mentor.startup');
            } else {
                return redirect()->route('incubatee.user.feed');
            }
        } else {
            return back()->with('msg', 'Sorry! Incorrect Login Information..');
        }
    }

    public function mentorStartup()
    {

        $user_id  = Auth::user()->id;

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
            $startUpId = $user->id;


            //echo $user->id;die;
            $DataBag['users'][$key]['memberInfo'] = Users::join('stage', 'stage.id', '=', 'users.stage_id')->where('users.id', '=', $startUpId)->select('users.*', 'stage.stage_name')->first();

            $DataBag['users'][$key]['number_of_post'] = Posts::where('member_id', '=', $startUpId)->where('status', '=', '1')->count();

            $DataBag['users'][$key]['number_of_reply'] = PostReply::where('replied_by', '=', $startUpId)->where('status', '=', '1')->count();


            $DataBag['users'][$key]['founders'] = DB::table('founder_transactions')->where('member_id', '=', $startUpId)->orderBy('id', 'asc')->get();

            $DataBag['users'][$key]['founderscount'] = DB::table('founder_transactions')->where('member_id', '=', $startUpId)->count();
        }

        //dd($DataBag);



        return view('incubation.startup_mentor', $DataBag);
    }

    public function feed()
    {

        $DataBag = array();

        //echo Auth::user()->id;die;
        $startUpCount = Users::where(function (Builder $query) {
            /***:- checking for mentor -:***/
            if (Auth()->user()->user_type == 6) {
                $query->whereHas('getMentor', function (Builder $query) {
                    $query->where('member_mentor_rel.mentor_id',  Auth()->user()->id);
                });
            } else {
                /***:- for portfolio manager -:***/
                $query->whereHas('startUpCount', function (Builder $query) {
                    $query->where('member_pm_rel.pm_id',  Auth()->user()->id);
                });
            }
        });

        $DataBag['incubatees'] = $startUpCount->where('status', 1)->where('user_type', 2)
            ->whereNull('founder_id')->count();

        // $DataBag['todos'] = TodoMaster::count();
        $DataBag['todos'] = TodoMaster::with('getUserName')
            ->where('user_id', Auth::user()->id)
            ->orWhere('assigned_by', Auth::user()->id)
            ->orderBy('id', 'Desc')
            ->count();

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

        $DataBag['mesg']  = "Sorry! No post found.";
        return view('incubation.feed_post', $DataBag);
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
                $postQuery->orWhere(function ($postQuery) {
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
            $postQuery = $postQuery->where(function ($postQuery) use ($industryCatID) {
                $postQuery = $postQuery->whereHas('postIndistries', function ($postQuery) use ($industryCatID) {
                    $postQuery->where('industry_category_id', '=', 7);
                    $postQuery->orWhere('industry_category_id', '=', $industryCatID);
                });
            });
        }


        if (isset($_GET['category']) && $_GET['category'] != '') {
            $categoryCatID = base64_decode($_GET['category']);
            $postQuery = $postQuery->where(function ($postQuery) use ($categoryCatID) {
                $postQuery = $postQuery->whereHas('postCategories', function ($postQuery) use ($categoryCatID) {
                    $postQuery->where('category_id', '=', $categoryCatID);
                });
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
                $postQuery->orWhereHas('memberInfo', function ($postQuery) use ($search) {
                    $postQuery->where('contact_name', 'LIKE', '%' . $search . '%');
                    $postQuery->orWhere('member_company', 'LIKE', '%' . $search . '%');
                });
            });
        }
        if (isset($_GET['category']) && $_GET['category'] != '') {
            $categoryCatID = base64_decode($_GET['category']);
            $postQuery = $postQuery->where(function ($postQuery) use ($categoryCatID) {
                $postQuery = $postQuery->whereHas('postCategories', function ($postQuery) use ($categoryCatID) {
                    $postQuery->where('category_id', '=', $categoryCatID);
                });
            });
        }




        $DataBag['normalPost'] = $postQuery->orderBy('post_type', 'desc')->orderBy('id', 'desc')->paginate(15);

        //dd($DataBag['normalPost']);

        $DataBag['mesg']  = "Sorry! No post found.";





        return view('incubation.account', $DataBag);
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
        return redirect()->route('signin');
    }
    public function todo()
    {

        // mentor+startup;

        $DataBag['tasks'] = TodoMaster::with('getUserName')
            ->where('user_id', Auth::user()->id)
            ->orWhere('assigned_by', Auth::user()->id)
            ->orderBy('id', 'Desc')
            ->get();

        // dd($DataBag['tasks'], Auth::user()->id);

        $DataBag['statuses'] = [
            [
                'label' => 'Pending',
                'value' => '0',
            ],
            [
                'label' => 'Completed',
                'value' => '1',
            ],
            [
                'label' => 'In Process',
                'value' => '2',
            ]
        ];
        //dd($DataBag['tasks']);
        return view('incubation.todo_list', $DataBag);
    }
    public function todoStatus(Request $request)
    {
        //dd('ok');
        $DataBag['tasks'] = TodoMaster::orderBy('id', 'Desc')->get();

        if (isset($request->status)) {
            $DataBag['statuses'] = [
                [
                    'label' => 'Pending',
                    'value' => '0',
                ],
                [
                    'label' => 'Completed',
                    'value' => '1',
                ],
                [
                    'label' => 'In Process',
                    'value' => '2',
                ]
            ];

            $DataBag['status'] = TodoMaster::where('status', $request->status)->get();
        }

        // dd( $DataBag['status']);



        return view('incubation.todo_status', $DataBag);
    }

    public function todoCreate()
    {
        // dd(Auth::user()->user_type);
        //$DataBag['task'] = TodoMaster::findOrFail($id);
        $DataBag['selfName'] = Auth::user()->first_name;
        $DataBag['startupName'] = Auth::user()->contact_name;

        if (Auth::user()->user_type == 4) {
            /***:- startup list -:***/
            $startupList = Users::join('member_pm_rel', 'member_pm_rel.member_id', '=', 'users.id')
                ->select('users.*')
                ->where('member_pm_rel.pm_id', '=', Auth::user()->id);
            /***:- mentor list -:***/
            $mentorList = Users::whereIn('user_type', [6])->orderBy('id', 'desc')->union($startupList)->get();
            $DataBag['userList'] = $mentorList;
        }
        if (Auth::user()->user_type == 6) {
            $DataBag['selfName'] = Auth::user()->first_name != '' ? Auth::user()->first_name : Auth::user()->contact_name;
            /***:- startup list -:***/
            $startupList = Users::join('member_mentor_rel', 'member_mentor_rel.member_id', '=', 'users.id')
                ->select('users.*')
                ->where('member_mentor_rel.mentor_id', '=', Auth::user()->id)->get();
            $DataBag['userList'] = $startupList;
        }

        // $DataBag['allStartupUsers'] = Users::orderBy('id', 'Desc')
        //     ->whereNull('founder_id')
        //     ->where('user_type', '=', 2)
        //     ->get();


        $DataBag['statuses'] = [
            [
                'label' => 'Pending',
                'value' => '0',
            ],
            [
                'label' => 'Completed',
                'value' => '1',
            ],
            [
                'label' => 'In Process',
                'value' => '2',
            ]
        ];
        return view('incubation.create', $DataBag);
    }

    public function todoStore(Request $request)
    {
        $request->validate([
            'task_title' => 'required'
        ]);

        $assigned = $request->assigned_by;
        foreach ($assigned as $assign) {

            $task = new TodoMaster;

            $task->task_title = $request->input('task_title');
            $task->task_details = $request->input('task_details');
            $task->notes = $request->input('notes');
            // $task->assigned_by = $request->input($test);
            // $task->assigned_by = $request->input('assigned_by');
            $task->assigned_by = Auth::user()->id;
            $task->deadline = $request->input('deadline');
            $task->user_id =  $assign;
            $task->status = $request->status;
            $task->save();
        }

        return redirect()->back()->with('success', 'Todo Created Successfully');
    }

    public function todoEdit($id)
    {

        $DataBag['task'] = TodoMaster::with(['getUserName'])->findOrFail($id);

        $DataBag['allActiveUsers'] = Users::orderBy('id', 'Desc')->get();
        $DataBag['statuses'] = [
            [
                'label' => 'Pending',
                'value' => '0',
            ],
            [
                'label' => 'Completed',
                'value' => '1',
            ],
            [
                'label' => 'In Process',
                'value' => '2',
            ]
        ];
        return view('incubation.edit', $DataBag);
    }

    public function todoView($id)
    {
        $DataBag = array();
        $DataBag['task'] = TodoMaster::findOrFail($id);
        return view('incubation.todo_view', $DataBag);
    }

    public function todoUpdate(Request $request, $id)
    {
        $task = TodoMaster::findOrFail($id);
        $request->validate([
            'task_title' => 'required'
        ]);

        $task->task_title = $request->task_title;
        $task->task_details = $request->task_details;
        $task->notes = $request->notes;
        // $task->user_id = Auth::user()->id;
        $task->status = $request->status;
        $task->save();
        return redirect()->back()->with('success', 'Todo Updated Successfully');
    }

    public function todoDestroy($id)
    {
        $task = TodoMaster::findOrFail($id);
        $task->delete();
        return redirect()->back()->with('success', 'Todo Deleted Successfully');
    }

    public function incubateeView()
    {

        $DataBag = array();
        $mentor = Users::with(['getServiceLocation', 'getProgramme', 'getMentor', 'getCompanyType']);


        /***:- checking for mentor -:***/
        if (Auth()->user()->user_type == 6) {
            $mentor->whereHas('getMentor', function (Builder $query) {
                $query->where('member_mentor_rel.mentor_id',  Auth()->user()->id);
            });
        } else {
            /***:- for portfolio manager -:***/
            $mentor->whereHas('startUpCount', function (Builder $query) {
                $query->where('member_pm_rel.pm_id',  Auth()->user()->id);
            });
        }



        $DataBag['mentorList'] = $mentor->where('status', 1)->where('user_type', 2)->whereNull('founder_id')->get();

        $DataBag['memberList'] = Users::whereIn('user_type', [6])->orderBy('id', 'desc')->get();



        return view('incubation.incubatee_view', $DataBag);
    }


    public function viewParameterList(Request $request)
    {
        $DataBag = array();
        $mentor_id = $request->input('mentor_id');
        $incubatee_id = $request->input('incubatee_id');
        $diagnostic_id = $request->input('diagnostic_id');

        $parameterList = Parameter::with(['getResponseBriefData' => function ($q) use ($request) {
            $q->where('response_briefs.mentor_id', $request->input('mentor_id'));
            $q->where('response_briefs.incubatee_id', $request->input('incubatee_id'));
            $q->where('response_briefs.diagnostic_id', $request->input('diagnostic_id'));
        }])->get();
        // dd($parameterList);
        $DataBag['diagnostic_id'] = $diagnostic_id;
        $DataBag['incubatee_id'] = $incubatee_id;
        $DataBag['mentor_id'] = $mentor_id;
        $DataBag['parameterList'] = $parameterList;

        /*ResponseBrief::with(['getParameter'])->select([DB::raw('DISTINCT(mentor_id), incubatee_id,parameter_id,diagnostic_id,id,parameter_score,comment')])
            ->where('mentor_id', $request->input('mentor_id'))
            ->where('incubatee_id', $request->input('incubatee_id'))
            ->where('diagnostic_id', $request->input('diagnostic_id'))
            ->get();*/
        return view('incubation.diagnostics.view_parameter_list', $DataBag);
    }

    public function viewQuestAnsList(Request $request)
    {
        $DataBag = array();
        $mentor_id = $request->input('mentor_id');
        $incubatee_id = $request->input('incubatee_id');
        $parameter_id = $request->input('parameter_id');
        $diagnostic_id = $request->input('diagnostic_id');
        $DataBag['parameter'] = Parameter::findOrFail($parameter_id);
        $DataBag['questions'] = Question::where('parameter_id', $parameter_id)->where('status', '1')->get();
        $DataBag['responseBrief'] = ResponseBrief::with(['getResponseDetails'])
            ->where('parameter_id', $parameter_id)
            ->where('mentor_id', $mentor_id)
            ->where('incubatee_id', $incubatee_id)
            ->where('diagnostic_id', $diagnostic_id)
            ->first();
        $DataBag['quest'] = [];


        if ($DataBag['responseBrief']) {
            if (count($DataBag['responseBrief']->getResponseDetails)) {
                $DataBag['quest'] = $DataBag['responseBrief']->getResponseDetails->pluck('question_input', 'question_id');
            }
        }
        return view('incubation.diagnostics.view_quest_ans', $DataBag);
    }

    public function diagnosticsList($startUpId)
    {
        $DataBag = array();
        $DataBag['startUpId']  = $startUpId;
        $mentor  = MemberDiagnostic::with(['getMentor', 'getIncubatee']);

        /***:- checking for mentor -:***/
        if (Auth()->user()->user_type == 6) {
            $mentor->where('mentor_id', Auth()->user()->id);
        }
        $DataBag['diagnosticList']  = $mentor->where('incubatee_id', $startUpId)->get();

        return view('incubation.diagnostics.list', $DataBag);
    }
    public function addDiagnostic(Request $request, $startUpId)
    {
        $DataBag = array();
        $DataBag['startUpId']  = $startUpId;

        if ($request->isMethod('post')) {
            MemberDiagnostic::create([
                'mentor_id' => $request->input('mentor_id'),
                'title' => $request->input('title'),
                'status' => 1,
                'incubatee_id' => $startUpId
            ]);

            return redirect()->route('diagnosticsList', ['startUpId' => $startUpId]);
        }
        $DataBag['mentorList'] = Users::whereIn('user_type', [6])->orderBy('id', 'desc')->get();


        return view('incubation.diagnostics.add', $DataBag);
    }


    public function editDiagnostic(Request $request, $startUpId, $diagnosticId)
    {
        $DataBag = array();
        $DataBag['startUpId']  = $startUpId;
        $diagnostic = MemberDiagnostic::findOrFail($diagnosticId);

        if ($request->isMethod('post')) {
            MemberDiagnostic::where('id', $diagnosticId)->update([
                'mentor_id' => $request->input('mentor_id'),
                'title' => $request->input('title'),
                'status' => $request->input('status'),
                'incubatee_id' => $startUpId
            ]);

            return redirect()->route('diagnosticsList', ['startUpId' => $startUpId]);
        }
        $DataBag['diagnostic'] = $diagnostic;
        $DataBag['mentorList'] = Users::whereIn('user_type', [6])->orderBy('id', 'desc')->get();


        return view('incubation.diagnostics.add', $DataBag);
    }

    public function startupViewChart($startUpId)
    {

        $DataBag['startUpId']  = $startUpId;

        $DataBag['results'] = StartupYearlyFinancial::with('getFinancialYear')->orderBy('id', 'asc')->where('startup_id', '=', $startUpId)->get();



        $DataBag['results_month'] = StartupMonthlySale::with(['getProducts'])->select([DB::raw('DISTINCT(product_id), month, credit_sale, cash_sale')])
            //->orderBy(DB::raw('month'),'desc')
            ->groupBy(DB::raw('month'))
            ->where('startup_id', '=', $startUpId)
            ->get();


        //$res[] = ['Months','Products', 'Credit Sale','Cash Sale', 'Total'];
        $res[] = ['Months', 'Total'];
        foreach ($DataBag['results_month'] as $key => $val) {

            $total = (int)$val->credit_sale + (int)$val->cash_sale;

            $res[++$key] = [$val->getFinancialMonth->display_month, (int)$total];

            // $res[++$key] = [$val->month, (int)$val->getProducts->caption, (int)$val->credit_sale, (int)$val->cash_sale, (int)$total ];

        }
        $DataBag['data_month'] = json_encode($res);
        // dd($DataBag['data_month']);

        $DataBag['data'] = "";


        foreach ($DataBag['results'] as $result) {

            $DataBag['data'] .= "['" . $result->getFinancialYear->display_year . "'," . $result->revenue . "," . $result->expense . "," . $result->net_profit . "],";
        }

        return view('incubation.chart_view', $DataBag);
    }


    public function startupView(Request $request, $startUpId)
    {


        $DataBag = array();
        $DataBag['startUpId']  = $startUpId;
        $mentor = Users::with(['getServiceLocation', 'getProgramme', 'getMentor', 'getCompanyType']);


        /***:- checking for mentor -:***/
        if (Auth()->user()->user_type == 6) {
            $mentor->whereHas('getMentor', function (Builder $query) {
                $query->where('member_mentor_rel.mentor_id',  Auth()->user()->id);
            });
        } else {
            /***:- for portfolio manager -:***/
            $mentor->whereHas('startUpCount', function (Builder $query) {
                $query->where('member_pm_rel.pm_id',  Auth()->user()->id);
            });
        }

        $request->session()->put('startUpId', $startUpId);


        $DataBag['mentorList'] = $mentor->where('status', 1)->where('user_type', 2)->whereNull('founder_id')->where('id', '=',  $startUpId)->get();



        return view('incubation.startup_view', $DataBag);
    }

    public function startupManageProfile($startUpId)
    {

        $DataBag = array();

        $DataBag['startUpId']  = $startUpId;


        $DataBag['stage'] = DB::table('stage')->orderBy('stage_name', 'asc')->get();

        $DataBag['company_type'] = DB::table('company_type')->orderBy('company_type', 'asc')->get();

        $DataBag['legal_status'] = DB::table('legal_status')->orderBy('legal_status', 'asc')->get();

        $DataBag['industry_category'] = DB::table('industry_category')->where('status', 1)
            ->orderBy('industry_category', 'asc')->get();


        $DataBag['currentIndusCats'] = MemberBusiness::where('member_id', '=', $startUpId)
            ->pluck('industry_category_id')->toArray();



        $DataBag['founders'] = DB::table('founder_transactions')->where('member_id', '=', $startUpId)->orderBy('id', 'asc')->get();

        $DataBag['founderscount'] = DB::table('founder_transactions')->where('member_id', '=', $startUpId)->count();

        $DataBag['buisness'] = DB::table('member_services')->where('member_id', '=', $startUpId)->orderBy('id', 'asc')->get();

        $DataBag['company_videos'] = DB::table('member_videos')->where('member_id', '=', $startUpId)->orderBy('id', 'asc')->get();

        $DataBag['company_investments'] = DB::table('member_investments')->where('member_id', '=', $startUpId)->orderBy('id', 'asc')->get();


        return view('incubation.edit_manage_profile', $DataBag);
    }

    public function  startupUpdateProfile(Request $request, $startUpId)
    {

        $DataBag = array();

        $DataBag['startUpId']  = $startUpId;

        //$startUpId = startUpId(Auth::user()->id);

        $ckEmail = Users::where('email_id', trim($request->input('email_id')))
            ->where('id', '!=', $startUpId)->first();
        // if (!empty($ckEmail)) {
        //     return back()->with('msg', 'Email-id already exist, try another')
        //         ->with('msg_class', 'alert alert-danger')
        //         ->with('signupError', 'email_exist');
        // }

        $UserID = $startUpId;
        $Users = Users::findOrFail($UserID);
        $Users->member_company = trim($request->input('member_company'));

        $Users->slug  = Str::slug($Users->member_company, '-');

        $Users->company_name = trim($request->input('company_name'));
        $Users->stage_id = trim($request->input('stage_id'));
        $Users->email_id = trim($request->input('email_id'));
        $Users->password = md5(trim($request->input('password')));
        $Users->company_mobile = trim($request->input('company_mobile'));
        // $Users->mobile = trim($request->input('contact_no'));
        $Users->company_code = trim($request->input('company_code'));
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

        // $Users->buisness_info = trim($request->input('buisness_info'));

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

            $childs = Users::where('founder_id', '=', $startUpId)->get();

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
            //if(count($buisness_caption))
            if ($buisness_caption) {
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
            $funders_name = $request->input('funders_name');
            $year = $request->input('year');
            $other_details = $request->input('other_details');
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
                        $memberInvest[$key]['funders_name'] = $funders_name[$key];
                        $memberInvest[$key]['year'] = $year[$key];
                        $memberInvest[$key]['other_details'] = $other_details[$key];
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

    public function privateProfile($startUpId)
    {

        $DataBag = array();

        $DataBag['startUpId'] = $startUpId;

        $DataBag['company_investments'] = DB::table('member_investments')->where('member_id', '=', $startUpId)->orderBy('id', 'Desc')->get();

        //  $DataBag['company_investments'] = DB::table('member_investments')->orderBy('id', 'asc')->get();

        return view('incubation.private_info', $DataBag);
    }

    public function addCustomer($startUpId)
    {
        $DataBag = array();

        $DataBag['startUpId'] = $startUpId;


        $DataBag['startup_cust'] = StartupCustomer::where('startup_id', '=', $startUpId)->orderBy('id', 'Desc')->get();

        return view('incubation.add_customer', $DataBag);
    }

    public function addCustomerAction(Request $request, $startUpId)
    {
        $request->validate([
            'customer_name' => 'required'
        ]);

        $DataBag['startUpId'] = $startUpId;

        $cust = new StartupCustomer;

        $cust->customer_name = $request->input('customer_name');
        $cust->startup_id = $startUpId;

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

        return view('incubation.edit_customer', $DataBag);
    }

    public function custUpdate(Request $request, $id)
    {
        $custs = StartupCustomer::findOrFail($id);
        $request->validate([
            'customer_name' => 'required'
        ]);

        $custs->customer_name = $request->customer_name;
        //$custs->startup_id = $startUpId;
        $custs->save();

        return redirect()->back()->with('msg', 'Customer Updated Successfully')->with('msg_class', 'alert alert-success');;
    }

    public function addFinancial($startUpId)
    {
        $DataBag = array();
        $DataBag['startUpId'] = $startUpId;

        $DataBag['financials'] = StartupYearlyFinancial::with('getFinancialYear')->where('startup_id', '=', $startUpId)->orderBy('id', 'desc')->get();

        $DataBag['finYear'] = FinancialYear::orderBy('id', 'desc')->get();

        //dd($DataBag['financials']) ;

        return view('incubation.financials', $DataBag);
    }

    public function addFinancialAction(Request $request, $startUpId)
    {
        $request->validate([
            'financial_year' => 'required'
        ]);

        $DataBag['startUpId'] = $startUpId;

        $fin = new StartupYearlyFinancial;

        $fin->startup_id = $startUpId;

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
    }

    public function financialDestroy($id)
    {
        $custs = StartupYearlyFinancial::findOrFail($id);
        $custs->delete();

        return redirect()->back()->with('msg', 'Financial Report Deleted Successfully')->with('msg_class', 'alert alert-success');;
    }

    public function financialEdit($id)
    {

        $DataBag['finance'] = StartupYearlyFinancial::findOrFail($id);
        $DataBag['finYear'] = FinancialYear::orderBy('id', 'desc')->get();

        return view('incubation.edit_finance', $DataBag);
    }

    public function financialUpdate(Request $request, $id)
    {
        $finance = StartupYearlyFinancial::findOrFail($id);

        $request->validate([
            'financial_year' => 'required'
        ]);


        //$finance->startup_id = Auth::user()->id;

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


    public function addFinancialMonth($startUpId)
    {

        $DataBag = array();

        $DataBag['startUpId'] = $startUpId;

        $DataBag['financials'] = StartupMonthlySale::with(['getFinancialYear', 'getFinancialMonth'])->where('startup_id', '=', $startUpId)->orderBy('id', 'desc')->get();

        $DataBag['finYear'] = FinancialYear::orderBy('id', 'desc')->get();
        $DataBag['finMonth'] = FinancialMonth::orderBy('id', 'desc')->get();


        $DataBag['allProducts'] = MemberService::orderBy('id', 'Desc')->get();

        //$DataBag['products'] = StartupMonthlySale::with('getProducts')->get();
        $DataBag['productList'] = StartupMonthlySale::where('startup_id', '=', $startUpId)->with('getProducts')->get();

        //dd( $DataBag['financials']);


        return view('incubation.financials_month', $DataBag);
    }

    public function  addFinancialMonthAction(Request $request, $startUpId)
    {
        $request->validate([
            'financial_year' => 'required'
        ]);

        $prods = $request->product_id;

        foreach ($prods as $prod) {

            $finsale = new StartupMonthlySale;

            $finsale->startup_id = $startUpId;

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
    }

    public function financialMonthDestroy($id)
    {
        $finmonth = StartupMonthlySale::findOrFail($id);
        $finmonth->delete();

        return redirect()->back()->with('msg', 'Financial Month Deleted Successfully')->with('msg_class', 'alert alert-success');
    }

    public function financialMonthEdit($id)
    {

        $DataBag['finmonth'] = StartupMonthlySale::findOrFail($id);

        $DataBag['allProducts'] = MemberService::orderBy('id', 'Desc')->get();

        $DataBag['finYear'] = FinancialYear::orderBy('id', 'desc')->get();

        $DataBag['finMonth'] = FinancialMonth::orderBy('id', 'desc')->get();

        return view('incubation.edit_finance_month', $DataBag);
    }

    public function financialMonthUpdate(Request $request, $id)
    {
        $finmonth = StartupMonthlySale::findOrFail($id);

        $request->validate([
            'financial_year' => 'required'
        ]);

        $prods = $request->product_id;

        foreach ($prods as $prod) {

            //$finmonth->startup_id = Auth::user()->id;

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

    public function addFinancialExpenses($startUpId)
    {

        $DataBag = array();
        $DataBag['startUpId'] = $startUpId;

        $DataBag['finex'] = StartupMonthlyExpenditure::with(['getFinancialYear', 'getFinancialMonth'])->where('startup_id', '=', $startUpId)->orderBy('id', 'desc')->get();

        $DataBag['finYear'] = FinancialYear::orderBy('id', 'desc')->get();
        $DataBag['finMonth'] = FinancialMonth::orderBy('id', 'desc')->get();


        return view('incubation.financial_expenses', $DataBag);
    }

    public function addFinancialExpenseAction(Request $request, $startUpId)
    {

        $request->validate([
            'financial_year' => 'required'
        ]);

        $DataBag['startUpId'] = $startUpId;

        $finexp = new StartupMonthlyExpenditure;

        $finexp->startup_id = $startUpId;

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

        $DataBag['finYear'] = FinancialYear::orderBy('id', 'desc')->get();

        $DataBag['finMonth'] = FinancialMonth::orderBy('id', 'desc')->get();

        return view('incubation.edit_finance_expense', $DataBag);
    }


    public function financialExpenseUpdate(Request $request, $id)
    {

        $finexpense = StartupMonthlyExpenditure::findOrFail($id);

        $request->validate([
            'financial_year' => 'required'
        ]);


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

    public function financialExpenseDestroy($id)
    {
        $finexpense = StartupMonthlyExpenditure::findOrFail($id);
        $finexpense->delete();

        return redirect()->back()->with('msg', 'Financial Expenses Deleted Successfully')->with('msg_class', 'alert alert-success');
    }

    public function addOrderPipeline($startUpId)
    {

        $DataBag['startUpId'] = $startUpId;

        $DataBag['allProducts'] = MemberService::orderBy('id', 'Desc')->get();

        $DataBag['finYear'] = FinancialYear::orderBy('id', 'desc')->get();
        $DataBag['finMonth'] = FinancialMonth::orderBy('id', 'desc')->get();

        $DataBag['productList'] = StartupMonthlyOrderPipeline::where('startup_id', '=', $startUpId)->with(['getProducts', 'getFinancialYear', 'getFinancialMonth'])->get();



        return view('incubation.order_pipeline', $DataBag);
    }

    public function addOrderPipelineAction(Request $request, $startUpId)
    {

        $request->validate([
            'financial_year' => 'required'
        ]);

        $prods = $request->product_id;

        foreach ($prods as $prod) {

            $orderspipe = new StartupMonthlyOrderPipeline;

            $orderspipe->startup_id = $startUpId;

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

        $DataBag['finYear'] = FinancialYear::orderBy('id', 'desc')->get();

        $DataBag['finMonth'] = FinancialMonth::orderBy('id', 'desc')->get();

        $DataBag['allProducts'] = MemberService::orderBy('id', 'Desc')->get();

        return view('incubation.edit_order_pipe', $DataBag);
    }

    public function OrderPipelineUpdate(Request $request, $id)
    {
        $orderpipe = StartupMonthlyOrderPipeline::findOrFail($id);

        $request->validate([
            'financial_year' => 'required'
        ]);

        $prods = $request->product_id;

        foreach ($prods as $prod) {


            //$orderpipe->startup_id = Auth::user()->id;

            $orderpipe->month = $request->input('month');

            $orderpipe->financial_year = $request->input('financial_year');

            $orderpipe->product_id = $prod;

            $orderpipe->volume = $request->input('volume');

            $orderpipe->amount = $request->input('amount');


            $orderpipe->save();
        }

        return redirect()->back()->with('msg', 'Order Pipeline Updated Successfully')->with('msg_class', 'alert alert-success');;
    }



    public function OrderPipelineDestroy($id)
    {
        $orderpipe = StartupMonthlyOrderPipeline::findOrFail($id);
        $orderpipe->delete();

        return redirect()->back()->with('msg', 'Financial Month Deleted Successfully')->with('msg_class', 'alert alert-success');
    }


    public function addYearlyTarget($startUpId)
    {

        $DataBag['startUpId'] = $startUpId;

        $DataBag['targets'] = StartupYearlyTarget::with('getFinancialYear')->where('startup_id', '=', $startUpId)->orderBy('id', 'desc')->get();
        $DataBag['finYear'] = FinancialYear::orderBy('id', 'desc')->get();

        return view('incubation.yearly_target', $DataBag);
    }

    public function addYearlyTargetAction(Request $request, $startUpId)
    {


        $yearlytargets = new StartupYearlyTarget;

        $yearlytargets->startup_id = $startUpId;

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
        $DataBag['finYear'] = FinancialYear::orderBy('id', 'desc')->get();

        return view('incubation.edit_yearly_target', $DataBag);
    }


    public function yearlyTargetUpdate(Request $request, $id)
    {

        $targets = StartupYearlyTarget::findOrFail($id);

        $request->validate([
            'financial_year' => 'required'
        ]);


        //$targets->startup_id = Auth::user()->id;

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



    public function addImpacts($startUpId)
    {

        $DataBag['startUpId'] = $startUpId;
        $DataBag['impacts'] = StartupImpact::where('startup_id', '=', $startUpId)->orderBy('id', 'desc')->get();

        return view('incubation.impacts', $DataBag);
    }

    public function addImpactsAction(Request $request, $startUpId)
    {

        $impacts = new StartupImpact;

        $impacts->startup_id = $startUpId;

        $impacts->indirect_employees = $request->input('indirect_employees');

        $impacts->employee_count = $request->input('employee_count');

        $impacts->women_employee_count = $request->input('women_employee_count');

        $impacts->total_beneficiaries = $request->input('total_beneficiaries');

        $impacts->save();

        return back()->with('msg', 'Impacts has been saved successfully!')
            ->with('msg_class', 'alert alert-success');
    }

    public function impactsEdit($id)
    {

        $DataBag['impact'] = StartupImpact::findOrFail($id);


        return view('incubation.edit_impact', $DataBag);
    }


    public function impactsUpdate(Request $request, $id)
    {

        $impact = StartupImpact::findOrFail($id);

        $request->validate([
            'indirect_employees' => 'required'
        ]);

        //$impact->startup_id = Auth::user()->id;

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


    public function  addFundingNeeds($startUpId)
    {


        $DataBag['startUpId'] = $startUpId;

        $DataBag['funds'] = StartupFundingNeed::where('startup_id', '=', $startUpId)->orderBy('id', 'desc')->get();


        return view('incubation.funding_needs', $DataBag);
    }

    public function addFundingNeedsAction(Request $request, $startUpId)
    {

        $funds = new StartupFundingNeed;

        $funds->startup_id = $startUpId;

        $funds->funding_requirement = $request->input('funding_requirement');

        $funds->save();

        return back()->with('msg', 'Funding Requirement has been saved successfully!')
            ->with('msg_class', 'alert alert-success');
    }

    public function fundingNeedsEdit($id)
    {

        $DataBag['funds'] = StartupFundingNeed::findOrFail($id);


        return view('incubation.edit_funding_needs', $DataBag);
    }


    public function fundingNeedsUpdate(Request $request, $id)
    {

        $funds = StartupFundingNeed::findOrFail($id);

        $request->validate([
            'funding_requirement' => 'required'
        ]);

        //$funds->startup_id = Auth::user()->id;

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


    public function addComplianceCheck($startUpId)
    {

        $DataBag['startUpId'] = $startUpId;

        $DataBag['compliance'] = StartupComplianceCheck::with('getFinancialYear')->where('startup_id', '=', $startUpId)->orderBy('id', 'desc')->get();

        $DataBag['finYear'] = FinancialYear::orderBy('id', 'desc')->get();

        //dd($DataBag['compliance']);

        return view('incubation.compliance_check', $DataBag);
    }

    public function addComplianceCheckAction(Request $request, $startUpId)
    {


        $compliance = new StartupComplianceCheck;

        $compliance->startup_id = $startUpId;

        $compliance->financial_year = $request->input('financial_year');
        $compliance->audited_financials = $request->input('audited_financials');
        $compliance->gst_compliance = $request->input('gst_compliance');

        $compliance->save();

        return back()->with('msg', 'Compliance check has been saved successfully!')
            ->with('msg_class', 'alert alert-success');
    }

    public function complianceChecksEdit($id)
    {

        $DataBag['compliance'] = StartupComplianceCheck::findOrFail($id);
        $DataBag['finYear'] = FinancialYear::orderBy('id', 'desc')->get();

        return view('incubation.edit_compliance_check', $DataBag);
    }


    public function complianceChecksUpdate(Request $request, $id)
    {

        $compliance = StartupComplianceCheck::findOrFail($id);

        $request->validate([
            'audited_financials' => 'required'
        ]);
        $compliance->financial_year = $request->input('financial_year');
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
