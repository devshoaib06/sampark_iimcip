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
use App\Models\Message;
use App\Models\CompanyType;
use App\Models\MemberPmRel;
use App\Models\TaskList;
use App\Models\TaskReview;
use Session;
use Image;
use View;
use Auth;
use DB;
use Mail;
use Illuminate\Support\Str; 
use App\Models\Invitations;
use App\Jobs\MailSendJob;
use Illuminate\Support\Facades\Redirect;
class TaskController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
     public function __construct()
    {
         //$user_id=Session::get('userid');
         //if($user_id==''){ 
             // Redirect::route('admin')->with('message','Please Login');
         //}
    }
	public function index($id=null)
	{
	}


     public function update_complete_task(){
          $task_id = $_POST['taskID']; 
          $taskList=TaskList::find($task_id);
          //dd($taskList->is_complete);
         if($taskList->is_complete == 0){
          //echo "here"; die;
            $taskList->is_complete = '1';
         }
         else{
         // echo "here123"; die;
               $taskList->is_complete= '0';
         }
         $taskList->save();
         echo "1";
       
         
     }
    
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$sessionid = Session::get('userid');
	 if (!isset($sessionid) && $sessionid=='')
	  {
		return Redirect::route('admin')->with('message','Please Login');
	  }

		$categoryList=TaskCategory::with('children')->where('parent_id',0)->get();
		
		return View::make('admin.task_categories.create',array('categoryList' => $categoryList));
	}



	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		 if (isset($_POST['_token']) && !empty($_POST['_token'])) {
			$category = new TaskCategory;
			
			//$category->parent_id = $_POST['parent_id'];
			
			$category->parent_id="0";
			
			$category->name = $_POST['name'];
			if($category->save()) {
			Session::flash('flash_message', 'Category Added Successfully');
			return Redirect::route('admin.list_task_categories');
		    }
		    else {
		     Session::flash('flash_message', 'Sorry!! Category Cannot be added.');
		    return Redirect::route('admin.list_task_categories');
		   
		    }
		}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{  
		$sessionid = Session::get('userid');
		 if (!isset($sessionid) && $sessionid=='')
		  {
			return Redirect::route('admin')->with('message','Please Login');
		  }

		$id=base64_decode($id);
		$category = TaskCategory::find($id);
		$categoryList=TaskCategory::with('children')->where('parent_id',0)->get();
		return View::make('admin.task_categories.edit',compact('category','categoryList'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		
		
	}
public function add_task($start_up_id){

  $userId = Auth::user()->id; 
  $ids_array = DB::table('member_mentor_rel')->where('mentor_id', '=', $userId)->get()->toArray();
  $ids = array();
        foreach ($ids_array as $key => $value) {
            $ids[] = $value->member_id; 
        }
        //dd($ids);
  $dataBag = array();
  $dataBag['start_up_id'] = $start_up_id;
  $dataBag['userList'] = DB::table('users')->whereIn('id', $ids)
                        ->where('status', 1)
                        ->get();

  //dd($dataBag['userList']);
  return view('frontend.task.add_task', $dataBag);
}
   public function task_list($start_up_id){
             $mentor_id = Auth::user()->id; 
               $taskHistory=TaskList::leftJoin('users','users.id','=','task_list.start_up_id')
                ->leftJoin('users as pm','pm.id','=','task_list.assignd_by')
                ->leftJoin('users as assigned','assigned.id','=','task_list.assigned_to')
                ->select('task_list.id','task_list.title','task_list.answer','task_list.description','pm.user_type','task_list.created_at','task_list.dead_line','task_list.assignd_by','task_list.is_complete','users.id as startup_id','users.member_company as startup_name','pm.first_name as assigned_by','assigned.member_company as assigned_to','assigned.user_type as assigned_user_type','task_list.pm_view')
                ->where('start_up_id','=',$start_up_id)->orderBy('task_list.is_complete','ASC')
                       ->orderBy('task_list.created_at','DESC')
                        ->paginate(15);
               $startup_details = Users::where('id',$start_up_id)->get();
            
                $data = [
                           'taskHistory' =>$taskHistory,
                           //'start_up'=>$start_up,
                           'startup_details' =>$startup_details,
                           //'start_up_id'=>$start_up_id
                     ]; 
	   //	dd($data);

		return View::make('frontend.task.task_list')->with($data);
         }
         public function get_mentor($startup_id){
             $start_inv_rel = StartUpInvestorRel::where('start_up_id',$startup_id)->get();
             return $start_inv_rel;
         }
	 public function create_task(){
    //echo "1"; die;
        $userId = Auth::user()->id; 
        $taskList = new TaskList;
        $taskList->title = $_POST['title'];
        $taskList->description = $_POST['description'];
        //$taskList->dead_line = $_POST['dead_line'];
        $taskList->dead_line = date("Y-m-d", strtotime($_POST['dead_line']));
        $taskList->start_up_id = $_POST['assigned_to'];
        $taskList->assignd_by = $userId;
        $taskList->assigned_to = $_POST['assigned_to'];
       //dd($taskList);             
      if($taskList->save()){
                    //Session::flash('flash_message', 'Risk added.');
          return back()->with('msg', 'Task added.')->with('msg_class', 'alert alert-success');
                }else{
                    //Session::flash('flash_message', 'Risk not added.');
          return back()->with('msg', 'Task not added.')->with('msg_class', 'alert alert-danger');
                }
    
    // return Redirect::back();
}



 public function send_task_email($taskDetails,$start_up_id,$assigned_by,$mentor_email){
      require_once Config::get('app.base_url')."common/helpers.php";
       
       $startUp=User::find($start_up_id);
       $startup_to= $startUp->user_email;
       $startup_name= $startUp->name;
       $subject='New  task for '.$startup_name.' Added by '.$assigned_by.'(PM)';
       $message="<h3>Task Details</h3>";
       $message.="<b>Startup Name: ".$startup_name;
       $message.="<table width='100%' border='1'><thead><tr><th></th><th>Title</th><th>Description</th><th>Deadline</th><th></th></tr></thead><tbody>";
       $i=0;
       foreach($taskDetails as $task){ $i++;
        $link="<a href='".Config::get('app.url')."/entrepreneur/task_list'>Details</a>";
        $message.="<tr><td>".$i."</td><td>".$task['title']."</td><td>".$task['description']."</td><td>".date('jS M Y',strtotime($task['dead_line']))."</td><td>".$link."</td></tr>";
       }

     $message.="</tbody></table>";


       $send=  sendEmailCommon($startup_to,$subject,$message);
       
       if(!empty($mentor_email)){
            $send=  sendEmailCommon($mentor_email,$subject,$message);
       }

      return true;

     }
public function review_task(){
   $user_id = Auth::user()->id;
                if($user_id==''){ 
                    //return Redirect::route('admin')->with('message','Please Login');
                }
       $userId = Auth::user()->id;
      $task_id =   $_GET['task_id'];
       $url = $_GET['relation']; 
       
         $taskList=TaskList::find($task_id);
         $start_up_id=$taskList->start_up_id;
          $taskList->pm_view = 1;
          $taskList->save();   
               $taskReview=TaskReview::leftJoin('users','users.id','=','task_review.reviewed_by')
                ->select('task_review.id','task_review.remarks','users.user_type','task_review.created_at','users.id','users.member_company','users.contact_name')
                ->where('task_review.task_id','=',$task_id)->orderBy('task_review.created_at','DESC')->paginate(10);
       $data=[
           'task_id'=>$task_id,
           'taskReview'=>$taskReview,
           'pm_id'=>$userId,
           'taskList' =>$taskList,
           'start_up_id'=>$start_up_id,
            'url' =>$url
       ];
       
        return $returnHTML = View::make('frontend.task.task_review')->with($data);
}
  public function create_review(){
      $user_id = Auth::user()->id;
                if($user_id==''){ 
                    return Redirect::route('admin')->with('message','Please Login');
                }
       $user_id = Auth::user()->id;
       
       if(isset($_POST['submit'])){
            $remark = $_POST['remark'];
            $pm_id = $_POST['investor_id'];
            $task_id=$_POST['task_id'];
            $taskList=TaskList::find($task_id);
            $taskList->startup_view = 0;
            $taskList->mentor_view = 0;
            $taskList->pm_view = 0;
            $taskList->save();
            $taskReview=new TaskReview;
            $taskReview->remarks = $remark;
            $taskReview->reviewed_by = $pm_id;
            $taskReview->task_id=$task_id;
                        //$taskReview->save();

                        //database insert query goes here
                 
   

        //Session::flash('flash_message', 'Task Remark Added Successfully');
      //  return Redirect::back();
      //return Redirect::route('admin.task_list',[$taskList->start_up_id]);

 if($taskReview->save()){    
        return back()->with('msg', 'Task Review added.')->with('msg_class', 'alert alert-success');
                }else{
          return back()->with('msg', 'Task Review not added.')->with('msg_class', 'alert alert-danger');
                }

         }
     }
     public function extend_task_date(){
         $task_id=Input::get('task_id');
         $extend_date=Input::get('ext_date');
          $taskList= TaskList::find($task_id);
          $taskList->dead_line=$extend_date;
          $taskList->save();
          
         return date('jS M Y',strtotime($extend_date));
     }
}
