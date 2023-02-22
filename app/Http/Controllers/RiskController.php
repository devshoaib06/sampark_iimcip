<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Auth;
use App\Models\Risk;
use DB;

//class RiskController extends \BaseController {
class RiskController extends Controller	
{
    /**
     * Display a listing of the resource.
     * uses from admin
     * @return Response
     */
    public function index() {
        
		$DataBag = array();
           /* $DataBag = array();
            $userid = Auth::user()->id;
            $user = Users::find($userid);
            
            $riskDetails = Risk::where('created_by', '=', $userid)->orderBy('created_at', 'desc')->paginate(10);
            
            $data = [
                'riskDetails' => $riskDetails,
                'start_up_id' => $userid
            ];*/
            
        
			$user_id  = Auth::user()->id;

           /* $ids_array = DB::table('risk')->where('created_by', '=', $user_id)->get()->toArray();
            $ids = array();
            foreach ($ids_array as $key => $value) {
                $ids[] = $value->member_id; 
            }*/
            
            $record =DB::table('risk')->where('created_by', '=', $user_id)->orderBy('created_at', 'desc')->get()->toArray();
            

          //  $users =$users->orderBy('users.member_company', 'asc')->select('users.*')->get();

            $DataBag['record'] = $record;

		
		
        //return View::make('risk.list')->with($data);
		return view('frontend.risk.list', $DataBag);
    }
    
    public function add_risk(){
        /*if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        } else {
             $data = array();
            return View::make('risk.add_risk')->with($data);
        }*/
		$DataBag = array();
		
		return view('frontend.risk.add_risk', $DataBag);
        
    }
    public function edit_risk(Request $request){
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        } else {
            //$risk_id = Input::get('risk_id');
			$risk_id = trim($request->input('risk_id'));
            $riskDetails = Risk::where('id','=',$risk_id)->first();
            $data = [
                'riskDetails' => $riskDetails
            ];
           // return View::make('risk.edit_risk')->with($data);
			
			return view('frontend.risk.edit_risk', $data);
        }
    }
    
	public function save_risk(Request $request){
		
		
        if (!Auth::check()) {
            Session::flash('flash_message', 'Please login.');
            return Redirect::route('home');
        } else {
            $userid = Auth::user()->id;
            
            
			$risk_id = trim($request->input('risk_id'));
			$title = trim($request->input('title'));
			$mitigation_strategy = trim($request->input('mitigation_strategy'));
			$status = trim($request->input('status'));           
            
            
            if(!empty($risk_id)){
                $risk = Risk::find($risk_id);
                $risk->title = stripslashes(trim($title));
                $risk->mitigation_strategy = stripslashes(trim($mitigation_strategy));
                $risk->status = $status;
                if($risk->save()){
                    //Session::flash('flash_message', 'Risk updated.');
					return back()->with('msg', 'Risk updated.')->with('msg_class', 'alert alert-success');
                }else{
                    //Session::flash('flash_message', 'Risk not updated.');					
					return back()->with('msg', 'Risk not updated.')->with('msg_class', 'alert alert-danger');
                }
            }else{
                $risk = new Risk;
                $risk->created_by = $userid;
                $risk->title = $title;
                $risk->mitigation_strategy = $mitigation_strategy;
                $risk->status = $status;
                if($risk->save()){
                    //Session::flash('flash_message', 'Risk added.');
					return back()->with('msg', 'Risk added.')->with('msg_class', 'alert alert-success');
                }else{
                    //Session::flash('flash_message', 'Risk not added.');
					return back()->with('msg', 'Risk not added.')->with('msg_class', 'alert alert-danger');
                }
            }
            //return Redirect::route('risk');
        }
    }
    
}
