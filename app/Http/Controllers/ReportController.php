<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;



use App\Models\Users;
use Auth;
use App\Models\NewReportIncubateMaster;
use App\Models\Entrepreneur;

use App\Models\NewReportFinancialInformation;
use App\Models\NewReportTargetAchievement;
use App\Models\NewReportTeamDetails;
use App\Models\NewReportFundingStatus;
use App\Models\NewReportFinancialInformationPlan;
use App\Models\StartupTargetAchievement;

use DB;


//class ReportController extends \BaseController {
class ReportController extends Controller {		

    /**
     * Display a listing of the resource.
     * uses from admin
     * @return Response
     */
    public function index($id) {
       //$searchYear =  Input::get('year'); 
       //$searchMonth = Input::get('month');
	   
	   $searchYear = trim($request->input('year'));
	   $searchMonth = trim($request->input('month'));
	   
       $where = "1=1";
            if(!empty ($searchMonth)){
                if($searchMonth=='last3'){
                    $currentMonthNo = date('m');
                    $prevMonthNo = $currentMonthNo-2;
                    $where.= " AND MONTH(new_report_incubate_master.submit_date) BETWEEN '".$prevMonthNo."' AND '".$currentMonthNo."'  ";
                }elseif($searchMonth=='last6'){
                    $currentMonthNo = date('m');
                    $prevMonthNo = $currentMonthNo-5;
                    $where.= " AND MONTH(new_report_incubate_master.submit_date) BETWEEN '".$prevMonthNo."' AND '".$currentMonthNo."'  ";
                }else{
                    $where.= " AND MONTH(new_report_incubate_master.submit_date)='".$searchMonth."' ";
                }
            }
            if(!empty ($searchYear)){
                $where.= " AND YEAR(new_report_incubate_master.submit_date)='".$searchYear."' ";
            }
            $myReports = NewReportIncubateMaster::
                    join('users as users','users.id','=','new_report_incubate_master.startup_id')
                    ->select('new_report_incubate_master.submit_date','new_report_incubate_master.id','users.name','new_report_incubate_master.is_draft','users.individual_name')
                    ->where('startup_id','=',$id)->whereRaw($where)->orderBy('submit_date')->paginate(10);
            $current_month_info = NewReportIncubateMaster::where('new_report_incubate_master.startup_id', '=', $id)->whereRaw(" MONTH(new_report_incubate_master.submit_date)='".date('m')."' ")->first();
            
             $data = [
                 'start_up_id'=>$id,
                 'searchYear'=>$searchYear,
                 'searchMonth'=>$searchMonth,
                 'current_month_info'=>$current_month_info,
                 'myReports'=>$myReports
             ];

        return View::make('admin.report.list_report')->with($data);
    }
    
    public function add_report($id){
        $data = array();
        $startupDetails = User::join('start_up_investor_rel','start_up_investor_rel.start_up_id','=','users.id')
                ->join('users as user2','user2.id','=','start_up_investor_rel.investor_id')
                ->select('users.*','user2.individual_name as investor_name')
                ->where('users.id', '=', $id)->where('users.status', '=', 'Active')->first();
        $startupInfoDetails = Entrepreneur::where('id', '=', $id)->where('status', '=', 'Active')->first();
        
        $join_date = $startupDetails->created_at;
        
        $reportFinancialInformationDetails = ReportFinancialInformation::where('startup_id', '=', $id)->get();
        $lastFinancialInformation = ReportFinancialInformation::where('startup_id', '=', $id)->orderBy('id', 'desc')->first();
        $last_financial_id = 0;
        $reportFundingStatusDetails = $reportOtherDetails = $reportTargetAchievementDetails = $reportTeamDetails = array();
        if(isset($lastFinancialInformation['id']) && !empty($lastFinancialInformation['id'])){
            $last_financial_id = $lastFinancialInformation['id'];
            $reportFundingStatusDetails = ReportFundingStatus::where('financial_info_id','=',$last_financial_id)->first();
            $reportOtherDetails = ReportOtherDetails::where('financial_info_id','=',$last_financial_id)->first();
            $reportTargetAchievementDetails = ReportTargetAchievement::where('financial_info_id','=',$last_financial_id)->first();
            $reportTeamDetails = ReportTeamDetails::where('financial_info_id','=',$last_financial_id)->first();
        }
        $data['join_date'] = $join_date;
        $data['startupDetails'] = $startupDetails;
        $data['lastFinancialInformation'] = $lastFinancialInformation;
        $data['startupInfoDetails'] = $startupInfoDetails;
        $data['reportFinancialInformationDetails'] = $reportFinancialInformationDetails;
        $data['reportFundingStatusDetails'] = $reportFundingStatusDetails;
        $data['reportOtherDetails'] = $reportOtherDetails;
        $data['reportTargetAchievementDetails'] = $reportTargetAchievementDetails;
        $data['reportTeamDetails'] = $reportTeamDetails;
        return $returnHTML = View::make('admin.report.add_report')->with('data',$data);
    }
    
    public function save_report(){//echo Input::get('final_save'); die();
        $startup_id = Input::get('startup_id');
        $editable_date = Input::get('editable_date');
        $current = date('Y');
        $comming = date('y')+1;
        $financial_year = $current.'-'.$comming;
        if(!empty($startup_id) && !empty($editable_date)){
            $reportFinancialYear = new ReportFinancialYear;
            $reportFinancialYear->startup_id = $startup_id;
            $reportFinancialYear->financial_year = $financial_year;
            $reportFinancialYear->created_at = date('Y-m-d H:i:s');
            if($reportFinancialYear->save()){
                $year_master_id = $reportFinancialYear->id;
                
                $cash_in_hand = Input::get('cash_in_hand');
                $revenue = Input::get('revenue');
                $expense = Input::get('expense');
                $burn_rate = Input::get('burn_rate');
                
                $reportFinancialInformation = new ReportFinancialInformation;
                $reportFinancialInformation->startup_id = $startup_id;
                $reportFinancialInformation->year_master_id = $year_master_id;
                $reportFinancialInformation->month_year = date('Y-m-d',strtotime($editable_date));
                $reportFinancialInformation->cash_in_hand = $cash_in_hand;
                $reportFinancialInformation->revenue = $revenue;
                $reportFinancialInformation->expense = $expense;
                $reportFinancialInformation->burn_rate = $burn_rate;
                $reportFinancialInformation->created_at = date('Y-m-d H:i:s');
                if($reportFinancialInformation->save()){
                    $financial_info_id = $reportFinancialInformation->id;
                    
                    $remarks_fund_from_own_resources = Input::get('remarks_fund_from_own_resources');
                    $remarks_fund_form_external = Input::get('remarks_fund_form_external');
                    $remarks_iimcip_investment = Input::get('remarks_iimcip_investment');
                    $reportFundingStatus = new ReportFundingStatus;
                    $reportFundingStatus->startup_id = $startup_id;
                    $reportFundingStatus->financial_info_id = $financial_info_id;
                    $reportFundingStatus->remarks_fund_from_own_resources = $remarks_fund_from_own_resources;
                    $reportFundingStatus->remarks_fund_form_external = $remarks_fund_form_external;
                    $reportFundingStatus->remarks_iimcip_investment = $remarks_iimcip_investment;
                    $reportFundingStatus->created_at = date('Y-m-d H:i:s');
                    $reportFundingStatus->save();
                    
                    $challenges = Input::get('challenges');
                    $progress_key_activities = Input::get('progress_key_activities');
                    $funding_conversation = Input::get('funding_conversation');
                    $planning = Input::get('planning');
                    $comments = Input::get('comments');
                    $support = Input::get('support');
                    $reportOtherDetails = new ReportOtherDetails;
                    $reportOtherDetails->startup_id = $startup_id;
                    $reportOtherDetails->financial_info_id = $financial_info_id;
                    $reportOtherDetails->challenges = $challenges;
                    $reportOtherDetails->progress_key_activities = $progress_key_activities;
                    $reportOtherDetails->funding_conversation = $funding_conversation;
                    $reportOtherDetails->planning = $planning;
                    $reportOtherDetails->comments = $comments;
                    $reportOtherDetails->support = $support;
                    $reportOtherDetails->created_at = date('Y-m-d H:i:s');
                    $reportOtherDetails->save();
                    
                    $volume_annual_target = Input::get('volume_annual_target');
                    $sales_annual_target = Input::get('sales_annual_target');
                    $volume_achived_till_date = Input::get('volume_achived_till_date');
                    $sales_achived_till_date = Input::get('sales_achived_till_date');
                    $volume_sales_revenue = Input::get('volume_sales_revenue');
                    $sales_sales_revenue = Input::get('sales_sales_revenue');
                    $order_pipeline = Input::get('order_pipeline');
                    $reportTargetAchievement = new ReportTargetAchievement;
                    $reportTargetAchievement->startup_id = $startup_id;
                    $reportTargetAchievement->financial_info_id = $financial_info_id;
                    $reportTargetAchievement->volume_annual_target = $volume_annual_target;
                    $reportTargetAchievement->sales_annual_target = $sales_annual_target;
                    $reportTargetAchievement->volume_achived_till_date = $volume_achived_till_date;
                    $reportTargetAchievement->sales_achived_till_date = $sales_achived_till_date;
                    $reportTargetAchievement->volume_sales_revenue = $volume_sales_revenue;
                    $reportTargetAchievement->sales_sales_revenue = $sales_sales_revenue;
                    $reportTargetAchievement->order_pipeline = $order_pipeline;
                    $reportTargetAchievement->created_at = date('Y-m-d H:i:s');
                    $reportTargetAchievement->save();
                    
                    $total_employee = Input::get('total_employee');
                    $fulltime_employee = Input::get('fulltime_employee');
                    $parttime_employee = Input::get('parttime_employee');
                    $founder_name = Input::get('founder_name');
                    $role_function = Input::get('role_function');
                    $reportTeamDetails = new ReportTeamDetails;
                    $reportTeamDetails->startup_id = $startup_id;
                    $reportTeamDetails->financial_info_id = $financial_info_id;
                    $reportTeamDetails->total_employee = $total_employee;
                    $reportTeamDetails->fulltime_employee = $fulltime_employee;
                    $reportTeamDetails->parttime_employee = $parttime_employee;
                    $reportTeamDetails->founder_name = $founder_name;
                    $reportTeamDetails->role_function = $role_function;
                    $reportTeamDetails->created_at = date('Y-m-d H:i:s');
                    $reportTeamDetails->save();
                    
                    return Redirect::route('admin.list_entreprenaurs');
                }else{
                    echo '1';
                }
            }else{
                echo '2';
            }
        }else{
            echo '3';
        }
    }
    
    public function edit_report($id){
		
        $data = array();
        $where = " 1=1 ";
        $master_id = $data['master_id'] = $id;
        $master_details = NewReportIncubateMaster::where('id','=',$master_id)->first();
        if(!empty($master_details)){  // && count($master_details)>0
            $startup_id = $master_details['startup_id'];
            //echo '<pre>'; print_r($master_details['startup_id']); die();
            /*$startupDetails = Users::
                    join('start_up_investor_rel','start_up_investor_rel.start_up_id','=','users.id')
                    ->join('users as user2','user2.id','=','start_up_investor_rel.investor_id')
                    ->select('users.*','user2.individual_name as investor_name')
                    ->where('users.id', '=', $startup_id)->where('users.status', '=', '1')->first();*/
					
					
			$startupDetails = Users::
            //join('start_up_investor_rel','start_up_investor_rel.start_up_id','=','users.id')
               // ->join('users as user2','user2.id','=','start_up_investor_rel.investor_id')
                select('users.*') //,'user2.contact_name as contact_name'
                ->where('users.id', '=', $startup_id)->where('users.status', '=', '1')->first();		

            $startupInfoDetails = Entrepreneur::where('id', '=', $startup_id)->where('status', '=', 'Active')->first();
            $last_month_info = NewReportIncubateMaster::where('startup_id', '=', $startup_id)->where('id','=',$master_id)->whereRaw($where)->first();

            /*$prev_month = date('m',strtotime($last_month_info['submit_date']))-1;
            $prev_prev_month =  date('m',strtotime($last_month_info['submit_date']))-2;
            $prev_prev_prev_month =  date('m',strtotime($last_month_info['submit_date']))-3;
            $currnt_month =  date('m',strtotime($last_month_info['submit_date']));
            $next_month =  date('m',strtotime($last_month_info['submit_date']))+1;
            $next_next_month =  date('m',strtotime($last_month_info['submit_date']))+2;*/
            
            $prev_month = date("Y-m-2", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." -1 Month"));
            $prev_prev_month =  date("Y-m-2", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." -2 Month"));
            $prev_prev_prev_month =  date("Y-m-2", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." -3 Month"));
            $currnt_month =  date("Y-m-2", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." +0 Month"));
            $next_month =  date("Y-m-2", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." +1 Month"));
            $next_next_month =  date("Y-m-2", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." +2 Month"));

            //$where = " MONTH(new_report_incubate_master.submit_date)='".$prev_month."' ";
			
			$where = " MONTH(new_report_incubate_master.submit_date)='".date('m',strtotime($prev_month))."' and YEAR(new_report_incubate_master.submit_date)='".date('Y',strtotime($prev_month))."'";

            $reportFundingStatusDetails = $reportTargetAchievementDetails = $reportTeamDetails = $prevInformationDetails = $prev_prevInformationDetails = $prev_prev_prev_InformationDetails = $currentInformationDetails = $next_monthInformationDetails = $next_next_monthInformationDetails = array();

            if(isset($last_month_info)){// && count($last_month_info)>0
                $reportTargetAchievementDetails = NewReportTargetAchievement::where('master_id','=',$master_id)->first();
                $reportFundingStatusDetails = NewReportFundingStatus::where('master_id','=',$master_id)->first();
                $reportTeamDetails = NewReportTeamDetails::where('master_id','=',$master_id)->first();            

                $prevInformationDetails = NewReportFinancialInformation::
                        where('startup_id', '=', $startup_id)
                       // ->where('master_id','=',$master_id)
                        ->whereRaw(" new_report_financial_information.submit_date='".$prev_month."' ")
                        ->orderBy('new_report_financial_information.id', 'desc')
                        ->first();
                $prev_prevInformationDetails = NewReportFinancialInformation::
                        where('startup_id', '=', $startup_id)
                        //->where('master_id','=',$master_id)
                        ->whereRaw(" new_report_financial_information.submit_date='".$prev_prev_month."' ")
                        ->orderBy('new_report_financial_information.id', 'desc')
                        ->first();
                $prev_prev_prev_InformationDetails = NewReportFinancialInformation::
                        where('startup_id', '=', $startup_id)
                        //->where('master_id','=',$master_id)
                        ->whereRaw(" new_report_financial_information.submit_date='".$prev_prev_prev_month."' ")
                        ->orderBy('new_report_financial_information.id', 'desc')
                        ->first();

                $currentInformationDetails = NewReportFinancialInformationPlan::
                        where('startup_id', '=', $startup_id)
                        //->where('master_id','=',$master_id)
                        ->whereRaw(" new_report_financial_information_plan.submit_date='".$currnt_month."' ")
                        ->orderBy('new_report_financial_information_plan.id', 'desc')
                        ->first();
                $next_monthInformationDetails = NewReportFinancialInformationPlan::
                        where('startup_id', '=', $startup_id)
                        //->where('master_id','=',$master_id)
                        ->whereRaw(" new_report_financial_information_plan.submit_date='".$next_month."' ")
                        ->orderBy('new_report_financial_information_plan.id', 'desc')
                        ->first();
                $next_next_monthInformationDetails = NewReportFinancialInformationPlan::
                        where('startup_id', '=', $startup_id)
                        //->where('master_id','=',$master_id)
                        ->whereRaw(" new_report_financial_information_plan.submit_date='".$next_next_month."' ")
                        ->orderBy('new_report_financial_information_plan.id', 'desc')
                        ->first();

            }

            $data['master_id'] = $id;
            $data['startupDetails'] = $startupDetails;
            $data['last_month_info'] = $last_month_info;
            $data['startupInfoDetails'] = $startupInfoDetails;
            $data['reportFundingStatusDetails'] = $reportFundingStatusDetails;
            $data['reportTargetAchievementDetails'] = $reportTargetAchievementDetails;
            $data['reportTeamDetails'] = $reportTeamDetails;

            $data['prevInformationDetails'] = $prevInformationDetails;
            $data['prev_prevInformationDetails'] = $prev_prevInformationDetails;
            $data['prev_prev_prev_InformationDetails'] = $prev_prev_prev_InformationDetails;

            $data['currentInformationDetails'] = $currentInformationDetails;
            $data['next_monthInformationDetails'] = $next_monthInformationDetails;
            $data['next_next_monthInformationDetails'] = $next_next_monthInformationDetails;
            
			//return $returnHTML = View::make('admin.report.edit_report_demo')->with('data',$data);
			
			$data['GparentMenu'] = 'rpt_management';
			$data['parentMenu'] = 'rpt_Management';
			$data['childMenu'] = 'rpt_List';
			
			
        	return view('dashboard.pm.report.edit_report_demo')->with('data',$data);	
			//return view('dashboard.pm.report.edit_report_demo')->with($data);
			
			
        }else{
            return Redirect::route('admin.list_entreprenaurs');
        }
    }
    
    public function update_report(Request $request){// echo '<pre>';print_r($_POST); die();
        
        //$master_id = Input::get('master_id');    
        $master_id = trim($request->input('master_id'));				
		
        if(!empty($master_id)){
            /*$challenges = Input::get('challenges');
            $progress_key_activities = Input::get('progress_key_activities');
            $funding_conversation = Input::get('funding_conversation');
            $planning = Input::get('planning');
            $awards_won = Input::get('awards_won');
            $comments = Input::get('comments');
            $support = Input::get('support');
            $cash_in_hand = Input::get('cash_in_hand');
            $is_draft = Input::get('is_draft');
            $type = Input::get('type');
            $technology = Input::get('technology');
            $dateof_disbursement = Input::get('dateof_disbursement');
            $share_transfer_status = Input::get('share_transfer_status');
            $share_transfer_count = Input::get('share_transfer_count');
            $additional_share = Input::get('additional_share');
            $incubate_status = Input::get('incubate_status');
            $investible_status = Input::get('investible_status');
            $fund_type = Input::get('fund_type');*/
			
			$challenges = trim($request->input('challenges'));
			$progress_key_activities = trim($request->input('progress_key_activities'));
			$funding_conversation = trim($request->input('funding_conversation'));
			$planning = trim($request->input('planning'));
			$awards_won = trim($request->input('awards_won'));
			$comments = trim($request->input('comments'));
			$support = trim($request->input('support'));
			$cash_in_hand = trim($request->input('cash_in_hand'));
			$is_draft = trim($request->input('is_draft'));
			$type = trim($request->input('type'));
			$technology = trim($request->input('technology'));
			$dateof_disbursement = trim($request->input('dateof_disbursement'));
			$share_transfer_status = trim($request->input('share_transfer_status'));
			$share_transfer_count = trim($request->input('share_transfer_count'));
			$additional_share = trim($request->input('additional_share'));
			$incubate_status = trim($request->input('incubate_status'));
			$investible_status = trim($request->input('investible_status'));
			$fund_type = trim($request->input('fund_type'));
			
            $incubateMaster = NewReportIncubateMaster::find($master_id);
            $incubateMaster->progress_key_activities = $progress_key_activities;
            $incubateMaster->funding_conversation = $funding_conversation;
            $incubateMaster->planning = $planning;
            $incubateMaster->awards_won = $awards_won;
            $incubateMaster->challenges = $challenges;
            $incubateMaster->comments = $comments;
            $incubateMaster->support = $support;
            $incubateMaster->cash_in_hand = $cash_in_hand;
            $incubateMaster->updated_at = date('Y-m-d H:i:s');
            $incubateMaster->is_draft = $is_draft;
            $incubateMaster->type = $type;
            $incubateMaster->technology = $technology;
            $incubateMaster->dateof_disbursement = $dateof_disbursement;
            $incubateMaster->share_transfer_status = $share_transfer_status;
            $incubateMaster->share_transfer_count = $share_transfer_count;
            $incubateMaster->additional_share = $additional_share;
            $incubateMaster->incubate_status = $incubate_status;
            $incubateMaster->investible_status = $investible_status;
            $incubateMaster->fund_type = $fund_type;
            if($incubateMaster->save()){
                $startup_id = $incubateMaster->startup_id;
                /*$id_revenue_3 = Input::get('id_revenue_3');
                $date_revenue_3 = Input::get('date_revenue_3');
                $revenue_3 = Input::get('revenue_3');
                $expense_3 = Input::get('expense_3');
                $burn_rate_3 = Input::get('burn_rate_3');*/
				
				$id_revenue_3 = trim($request->input('id_revenue_3'));
				$date_revenue_3 = trim($request->input('date_revenue_3'));
				$revenue_3 = trim($request->input('revenue_3'));
				$expense_3 = trim($request->input('expense_3'));
				$burn_rate_3 = trim($request->input('burn_rate_3'));
				
                if(!empty($revenue_3) && !empty($expense_3)){
                    if(!empty($id_revenue_3)){
                        $financial_report_3 =NewReportFinancialInformation::find($id_revenue_3);
                        $financial_report_3->revenue = $revenue_3;
                        $financial_report_3->expense = $expense_3;
                        $financial_report_3->burn_rate = $burn_rate_3;
                        $financial_report_3->is_draft = $is_draft;
                        $financial_report_3->updated_at = date('Y-m-d H:i:s');
                    }else{
                        $financial_report_3 = new NewReportFinancialInformation;
                        $financial_report_3->startup_id = $startup_id;
                        $financial_report_3->master_id = $master_id;
                        $financial_report_3->submit_date = $date_revenue_3;                        
                        $financial_report_3->month_year	 = date('Y-m-d');
                        $financial_report_3->created_at = date('Y-m-d H:i:s');
                    }
                    $financial_report_3->revenue = $revenue_3;
                    $financial_report_3->expense = $expense_3;
                    $financial_report_3->burn_rate = $burn_rate_3;
                    $financial_report_3->is_draft = $is_draft;
                    $financial_report_3->save();
                }
                /*$id_revenue_2 = Input::get('id_revenue_2');
                $date_revenue_2 = Input::get('date_revenue_2');
                $revenue_2 = Input::get('revenue_2');
                $expense_2 = Input::get('expense_2');
                $burn_rate_2 = Input::get('burn_rate_2');*/
				
				$id_revenue_2 = trim($request->input('id_revenue_2'));
				$date_revenue_2 = trim($request->input('date_revenue_2'));
				$revenue_2 = trim($request->input('revenue_2'));
				$expense_2 = trim($request->input('expense_2'));
				$burn_rate_2 = trim($request->input('burn_rate_2'));
				
                if(!empty($revenue_2) && !empty($expense_2)){
                    if(!empty($id_revenue_2)){
                        $financial_report_2 = NewReportFinancialInformation::find($id_revenue_2);
                        $financial_report_2->updated_at = date('Y-m-d H:i:s');
                    }else{
                        $financial_report_2 = new NewReportFinancialInformation;
                        $financial_report_2->startup_id = $startup_id;
                        $financial_report_2->master_id = $master_id;
                        $financial_report_2->submit_date = $date_revenue_2;                        
                        $financial_report_2->month_year	 = date('Y-m-d');
                        $financial_report_2->created_at = date('Y-m-d H:i:s');
                    }
                    $financial_report_2->revenue = $revenue_2;
                    $financial_report_2->expense = $expense_2;
                    $financial_report_2->burn_rate = $burn_rate_2;
                    $financial_report_2->is_draft = $is_draft;
                    $financial_report_2->save();
                }
                /*$id_revenue_1 = Input::get('id_revenue_1');
                $date_revenue_1 = Input::get('date_revenue_1');
                $revenue_1 = Input::get('revenue_1');
                $expense_1 = Input::get('expense_1');
                $burn_rate_1 = Input::get('burn_rate_1');*/
				
				$id_revenue_1 = trim($request->input('id_revenue_1'));
				$date_revenue_1 = trim($request->input('date_revenue_1'));
				$revenue_1 = trim($request->input('revenue_1'));
				$expense_1 = trim($request->input('expense_1'));
				$burn_rate_1 = trim($request->input('burn_rate_1'));
				
                if(!empty($revenue_1) && !empty($expense_1)){
                    if(!empty($id_revenue_1)){
                        $financial_report_1 = NewReportFinancialInformation::find($id_revenue_1);
                        $financial_report_1->updated_at = date('Y-m-d H:i:s');
                    }else{
                        $financial_report_1 = new NewReportFinancialInformation;
                        $financial_report_1->startup_id = $startup_id;
                        $financial_report_1->master_id = $master_id;
                        $financial_report_1->submit_date = $date_revenue_1;                        
                        $financial_report_1->month_year	 = date('Y-m-d');
                        $financial_report_1->created_at = date('Y-m-d H:i:s');
                    }
                    $financial_report_1->revenue = $revenue_1;
                    $financial_report_1->expense = $expense_1;
                    $financial_report_1->burn_rate = $burn_rate_1;
                    $financial_report_1->is_draft = $is_draft;
                    $financial_report_1->save();
                }
                //end
                //NewReportFinancialInformationPlan
                /*$id_plan_0 = Input::get('id_revenue_plan_0');
                $submit_plan_0 = Input::get('date_revenue_plan_0');
                $revenue_plan_0 = Input::get('revenue_plan_0');
                $expense_plan_0 = Input::get('expense_plan_0');
                $burn_rate_plan_0 = Input::get('burn_rate_plan_0');*/
				
				$id_plan_0 = trim($request->input('id_revenue_plan_0'));
				$submit_plan_0 = trim($request->input('date_revenue_plan_0'));
				$revenue_plan_0 = trim($request->input('revenue_plan_0'));
				$expense_plan_0 = trim($request->input('expense_plan_0'));
				$burn_rate_plan_0 = trim($request->input('burn_rate_plan_0'));
				
				
                if(!empty($revenue_plan_0) && !empty($expense_plan_0)){
                    if(!empty($id_plan_0)){
                        $financial_report_plan = NewReportFinancialInformationPlan::find($id_plan_0);
                        $financial_report_plan->updated_at = date('Y-m-d H:i:s');
                    }else{
                        $financial_report_plan = new NewReportFinancialInformationPlan;
                        $financial_report_plan->startup_id = $startup_id;
                        $financial_report_plan->master_id = $master_id;
                        $financial_report_plan->submit_date = $submit_plan_0;
                        $financial_report_plan->month_year = date('Y-m-d');
                        $financial_report_plan->created_at = date('Y-m-d H:i:s');
                    }
                    $financial_report_plan->revenue = $revenue_plan_0;
                    $financial_report_plan->expense = $expense_plan_0;
                    $financial_report_plan->burn_rate = $burn_rate_plan_0;
                    $financial_report_plan->is_draft = $is_draft;
                    $financial_report_plan->save();
                }
                /*$id_plan_1 = Input::get('id_revenue_plan_1');
                $submit_plan_1 = Input::get('date_revenue_plan_1');
                $revenue_plan_1 = Input::get('revenue_plan_1');
                $expense_plan_1 = Input::get('expense_plan_1');
                $burn_rate_plan_1 = Input::get('burn_rate_plan_1');*/
				
				$id_plan_1 = trim($request->input('id_revenue_plan_1'));
				$submit_plan_1 = trim($request->input('date_revenue_plan_1'));
				$revenue_plan_1 = trim($request->input('revenue_plan_1'));
				$expense_plan_1 = trim($request->input('expense_plan_1'));
				$burn_rate_plan_1 = trim($request->input('burn_rate_plan_1'));
				
				
                if(!empty($revenue_plan_1) && !empty($expense_plan_1)){
                    
                    if(!empty($id_plan_1)){
                        $financial_report_plan_1 = NewReportFinancialInformationPlan::find($id_plan_1);
                        $financial_report_plan_1->updated_at = date('Y-m-d H:i:s');
                    }else{
                        $financial_report_plan_1 = new NewReportFinancialInformationPlan;
                        $financial_report_plan_1->startup_id = $startup_id;
                        $financial_report_plan_1->master_id = $master_id;
                        $financial_report_plan_1->submit_date = $submit_plan_1;
                        $financial_report_plan_1->month_year = date('Y-m-d');
                        $financial_report_plan_1->created_at = date('Y-m-d H:i:s');
                    }
                    $financial_report_plan_1->revenue = $revenue_plan_1;
                    $financial_report_plan_1->expense = $expense_plan_1;
                    $financial_report_plan_1->burn_rate = $burn_rate_plan_1;
                    $financial_report_plan_1->is_draft = $is_draft;
                    $financial_report_plan_1->save();
                    
                }
                /*$id_plan_2 = Input::get('id_revenue_plan_2');
                $submit_plan_2 = Input::get('date_revenue_plan_2');
                $revenue_plan_2 = Input::get('revenue_plan_2');
                $expense_plan_2 = Input::get('expense_plan_2');
                $burn_rate_plan_2 = Input::get('burn_rate_plan_2');*/
				
				$id_plan_2 = trim($request->input('id_revenue_plan_2'));
				$submit_plan_2 = trim($request->input('date_revenue_plan_2'));
				$revenue_plan_2 = trim($request->input('revenue_plan_2'));
				$expense_plan_2 = trim($request->input('expense_plan_2'));
				$burn_rate_plan_2 = trim($request->input('burn_rate_plan_2'));
				
                if(!empty($revenue_plan_2) && !empty($expense_plan_2)){
                    if(!empty($id_plan_2)){
                        $financial_report_plan_2 = NewReportFinancialInformationPlan::find($id_plan_2);
                        $financial_report_plan_2->updated_at = date('Y-m-d H:i:s');
                    }else{
                        $financial_report_plan_2 = new NewReportFinancialInformationPlan;
                        $financial_report_plan_2->startup_id = $startup_id;
                        $financial_report_plan_2->master_id = $master_id;
                        $financial_report_plan_2->submit_date = $submit_plan_2;
                        $financial_report_plan_2->month_year = date('Y-m-d');
                        $financial_report_plan_2->created_at = date('Y-m-d H:i:s');
                    }
                    $financial_report_plan_2->revenue = $revenue_plan_2;
                    $financial_report_plan_2->expense = $expense_plan_2;
                    $financial_report_plan_2->burn_rate = $burn_rate_plan_2;
                    $financial_report_plan_2->is_draft = $is_draft;
                    $financial_report_plan_2->save();
                }
                //ReportTeamDetails::where('master_id', $master_id)->firstOrFail();
                /*$remarks_fund_from_own_resources = Input::get('remarks_fund_from_own_resources');
                $remarks_fund_form_external = Input::get('remarks_fund_form_external');
                $remarks_iimcip_investment = Input::get('remarks_iimcip_investment');*/
				
				$remarks_fund_from_own_resources = trim($request->input('remarks_fund_from_own_resources'));
				$remarks_fund_form_external = trim($request->input('remarks_fund_form_external'));
				$remarks_iimcip_investment = trim($request->input('remarks_iimcip_investment'));
				
				
                $reportFundingStatus = NewReportFundingStatus::where('master_id', $master_id)->firstOrFail();
                $reportFundingStatus->remarks_fund_from_own_resources = $remarks_fund_from_own_resources;
                $reportFundingStatus->remarks_fund_form_external = $remarks_fund_form_external;
                $reportFundingStatus->remarks_iimcip_investment = $remarks_iimcip_investment;
                $reportFundingStatus->updated_at = date('Y-m-d H:i:s');
                $reportFundingStatus->save();

                /*$volume_annual_target = Input::get('volume_annual_target');
                $sales_annual_target = Input::get('sales_annual_target');
                $volume_achived_till_date = Input::get('volume_achived_till_date');
                $sales_achived_till_date = Input::get('sales_achived_till_date');
                $volume_sales_revenue = Input::get('volume_sales_revenue');
                $sales_sales_revenue = Input::get('sales_sales_revenue');
                $order_pipeline = Input::get('order_pipeline');*/
				
				$volume_annual_target = trim($request->input('volume_annual_target'));
				$sales_annual_target = trim($request->input('sales_annual_target'));
				$volume_achived_till_date = trim($request->input('volume_achived_till_date'));
				$sales_achived_till_date = trim($request->input('sales_achived_till_date'));
				$volume_sales_revenue = trim($request->input('volume_sales_revenue'));
				$sales_sales_revenue = trim($request->input('sales_sales_revenue'));
				$order_pipeline = trim($request->input('order_pipeline'));
				
				
                $reportTargetAchievement = NewReportTargetAchievement::where('master_id', $master_id)->firstOrFail();
                $reportTargetAchievement->volume_annual_target = $volume_annual_target;
                $reportTargetAchievement->sales_annual_target = $sales_annual_target;
                $reportTargetAchievement->volume_achived_till_date = $volume_achived_till_date;
                $reportTargetAchievement->sales_achived_till_date = $sales_achived_till_date;
                $reportTargetAchievement->volume_sales_revenue = $volume_sales_revenue;
                $reportTargetAchievement->sales_sales_revenue = $sales_sales_revenue;
                $reportTargetAchievement->order_pipeline = $order_pipeline;
                $reportTargetAchievement->updated_at = date('Y-m-d H:i:s');
                $reportTargetAchievement->save();

                /*$total_employee = Input::get('total_employee');
                $fulltime_employee = Input::get('fulltime_employee');
                $parttime_employee = Input::get('parttime_employee');
                $founder_name = Input::get('founder_name');
                $role_function = Input::get('role_function');*/
				
				$total_employee = trim($request->input('total_employee'));
				$fulltime_employee = trim($request->input('fulltime_employee'));
				$parttime_employee = trim($request->input('parttime_employee'));
				$founder_name = trim($request->input('founder_name'));
				$role_function = trim($request->input('role_function'));
				
                $reportTeamDetails = NewReportTeamDetails::where('master_id', $master_id)->firstOrFail();
                $reportTeamDetails->total_employee = $total_employee;
                $reportTeamDetails->fulltime_employee = $fulltime_employee;
                $reportTeamDetails->parttime_employee = $parttime_employee;
                $reportTeamDetails->founder_name = $founder_name;
                $reportTeamDetails->role_function = $role_function;
                $reportTeamDetails->updated_at = date('Y-m-d H:i:s');
                $reportTeamDetails->save();
            }
            return \Redirect::back();
            //return redirect('admin/edit_report/12');
            //return Redirect::route('admin/list_report', 12);
//            //return Redirect::URL('admin/list_report/'.$startup_id); //$startup_id
        }else{
            return Redirect::route('admin.list_entreprenaurs');
        }
    }
    
    public function download_report($id){
		
		require_once Config::get('app.base_url')."common/helpers.php";
		
        require_once Config::get('app.base_url')."mpdf/vendor/autoload.php";

        $mpdf = new mPDF();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        ///$pdf_name = "test.pdf";
        ob_start();

        $data = array();
        $where = " 1=1 ";
        $master_id = $data['master_id'] = $id;
        $info = NewReportIncubateMaster::where('id','=',$master_id)->whereRaw($where)->first();
        $startup_id = $info['startup_id'];

        $startupDetails = User::
        join('start_up_investor_rel','start_up_investor_rel.start_up_id','=','users.id')
            ->join('users as user2','user2.id','=','start_up_investor_rel.investor_id')
            ->leftJoin('countries as cnt1','users.country_id','=','cnt1.sortname')
            ->leftJoin('cities as cty1','users.city_id','=','cty1.id')
            ->leftJoin('countries as cnt2','users.country_id2','=','cnt2.sortname')
            ->leftJoin('cities as cty2','users.city_id2','=','cty2.id')
            ->select('users.*','user2.individual_name as investor_name','cnt1.name as country1','cnt2.name as country2','cty1.city_name as city1','cty2.city_name as city2')
            ->where('users.id', '=', $startup_id)->where('users.status', '=', 'Active')->first();
        # echo '<pre>';print_r($startupDetails);
        $startupInfoDetails = Entrepreneur::
        leftJoin("structure_of_companies as soc","soc.id","=","entrepreneurs.structure_of_company")
            ->select('entrepreneurs.*','soc.name as soc_name')
            ->where('entrepreneurs.id', '=', $startup_id)->where('entrepreneurs.status', '=', 'Active')->first();
        $last_month_info = NewReportIncubateMaster::where('startup_id', '=', $startup_id)->where('id','=',$master_id)->whereRaw($where)->first();
		
		//$effectiveDate = strtotime("-3 months", strtotime("2018-03-12")); // returns timestamp
		
		//echo date('Y-m-d',$effectiveDate); // formatted version
		//echo "<br>";
		
		
        $prev_month = date('Y-m-d',strtotime('-1 months', strtotime($last_month_info['submit_date']))); //date('m',strtotime($last_month_info['submit_date']))-1;
        $prev_prev_month =  date('Y-m-d',strtotime('-2 months', strtotime($last_month_info['submit_date'])));
        $prev_prev_prev_month =  date('Y-m-d',strtotime('-3 months', strtotime($last_month_info['submit_date'])));
		
		/*
        $currnt_month =  date('m',strtotime($last_month_info['submit_date']));
        $next_month =  date('m',strtotime($last_month_info['submit_date']))+1;
        $next_next_month =  date('m',strtotime($last_month_info['submit_date']))+2;
		*/
		
		$currnt_month =  $last_month_info['submit_date'];
        $next_month =  date('Y-m-d',strtotime('+1 months', strtotime($last_month_info['submit_date'])));
        $next_next_month = date('Y-m-d',strtotime('+2 months', strtotime($last_month_info['submit_date'])));
		
        $where = " MONTH(new_report_incubate_master.submit_date)='".$prev_month."' ";

        $reportFundingStatusDetails = $reportTargetAchievementDetails = $reportTeamDetails = $prevInformationDetails = $prev_prevInformationDetails = $prev_prev_prev_InformationDetails = $currentInformationDetails = $next_monthInformationDetails = $next_next_monthInformationDetails = array();

        if(isset($last_month_info) && count($last_month_info)>0){
            $reportTargetAchievementDetails = NewReportTargetAchievement::where('master_id','=',$master_id)->first();
            $reportFundingStatusDetails = NewReportFundingStatus::where('master_id','=',$master_id)->first();
            $reportTeamDetails = NewReportTeamDetails::where('master_id','=',$master_id)->first();

            $prevInformationDetails = NewReportFinancialInformation::
            where('startup_id', '=', $startup_id)
                ->where('master_id','=',$master_id)
                ->whereRaw(" MONTH(new_report_financial_information.submit_date)='".date("m",strtotime($prev_month))."' and YEAR(new_report_financial_information.submit_date)='".date("Y",strtotime($prev_month))."' ")
                ->first();
            $prev_prevInformationDetails = NewReportFinancialInformation::
            where('startup_id', '=', $startup_id)
                //->where('master_id','=',$master_id)
                ->whereRaw(" MONTH(new_report_financial_information.submit_date)='".date("m",strtotime($prev_prev_month))."' and YEAR(new_report_financial_information.submit_date)='".date("Y",strtotime($prev_prev_month))."' ")
                ->first();
            $prev_prev_prev_InformationDetails = NewReportFinancialInformation::
            where('startup_id', '=', $startup_id)
                //->where('master_id','=',$master_id)
                ->whereRaw(" MONTH(new_report_financial_information.submit_date)='".date("m",strtotime($prev_prev_prev_month))."' and YEAR(new_report_financial_information.submit_date)='".date("Y",strtotime($prev_prev_prev_month))."' ")
                ->first();

            $currentInformationDetails = NewReportFinancialInformationPlan::
            where('startup_id', '=', $startup_id)
                ->where('master_id','=',$master_id)
                ->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".date("m",strtotime($currnt_month))."' and YEAR(new_report_financial_information_plan.submit_date)='".date("Y",strtotime($currnt_month))."' ")
                ->orderBy('new_report_financial_information_plan.id', 'desc')
                ->first();
            $next_monthInformationDetails = NewReportFinancialInformationPlan::
            where('startup_id', '=', $startup_id)
                ->where('master_id','=',$master_id)
                ->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".date("m",strtotime($next_month))."' and YEAR(new_report_financial_information_plan.submit_date)='".date("Y",strtotime($next_month))."' ")
                ->orderBy('new_report_financial_information_plan.id', 'desc')
                ->first();
            $next_next_monthInformationDetails = NewReportFinancialInformationPlan::
            where('startup_id', '=', $startup_id)
                ->where('master_id','=',$master_id)
                ->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".date("m",strtotime($next_next_month))."' and YEAR(new_report_financial_information_plan.submit_date)='".date("Y",strtotime($next_next_month))."' ")
                ->orderBy('new_report_financial_information_plan.id', 'desc')
                ->first();

        }
        $promoterDetails = Promoter::where('user_id', '=', $startup_id)
            ->orderBy('id', 'asc')
            ->first();
        $pdf_name = $startupDetails['name'].'-'.date('M y',strtotime($last_month_info['submit_date'])).'.pdf';

        $where2 = " startup_target_achievement.created_at BETWEEN '" . checkFinancialStart(date('Y')) . "' AND '" . checkFinancialEnd(date('Y')) . "'  ";
        $current_target_achievement = StartupTargetAchievement::where('startup_id', $startup_id)->whereRaw($where2)->first();

        $where1 = " startup_target_achievement.created_at BETWEEN '" . checkFinancialStart(date('Y') - 1) . "' AND '" . checkFinancialEnd(date('Y') - 1) . "'  ";
        $current_target_achievement1 = StartupTargetAchievement::where('startup_id', $startup_id)->whereRaw($where1)->first();

        $data['current_target_achievement'] = $current_target_achievement;
        $data['current_target_achievement1'] = $current_target_achievement1;

        $data['startupDetails'] = $startupDetails;
        $data['last_month_info'] = $last_month_info;
        $data['startupInfoDetails'] = $startupInfoDetails;
        $data['reportFundingStatusDetails'] = $reportFundingStatusDetails;
        $data['reportTargetAchievementDetails'] = $reportTargetAchievementDetails;
        $data['reportTeamDetails'] = $reportTeamDetails;

        $data['prevInformationDetails'] = $prevInformationDetails;
        $data['prev_prevInformationDetails'] = $prev_prevInformationDetails;
        $data['prev_prev_prev_InformationDetails'] = $prev_prev_prev_InformationDetails;

        $data['promoterDetails'] = $promoterDetails;
        $data['financial_year'] = date('Y');//$financial_year;
        $data['currentInformationDetails'] = $currentInformationDetails;
        $data['next_monthInformationDetails'] = $next_monthInformationDetails;
        $data['next_next_monthInformationDetails'] = $next_next_monthInformationDetails;
        $data['pdf_name'] = $pdf_name;
        $html = View::make('admin.report.generatePDF')->with('data',$data);
        
        //$html = 'test';
        ob_end_clean();
        $mpdf->WriteHTML($html);
        $mpdf->Output($pdf_name, 'D');
        echo $pdf_name;
    }
    
    public function index_startup() {
        $id = Auth::user()->id;
       $searchYear =  Input::get('year'); 
       $searchMonth = Input::get('month');
       $where = "1=1";
       if(!empty ($searchMonth)){
           if($searchMonth=='last3'){
               $currentMonthNo = date('m');
               $prevMonthNo = $currentMonthNo-2;
               $where.= " AND MONTH(report_financial_information.month_year) BETWEEN '".$prevMonthNo."' AND '".$currentMonthNo."'  ";
           }elseif($searchMonth=='last6'){
               $currentMonthNo = date('m');
               $prevMonthNo = $currentMonthNo-5;
               $where.= " AND MONTH(report_financial_information.month_year) BETWEEN '".$prevMonthNo."' AND '".$currentMonthNo."'  ";
           }else{
               $where.= " AND MONTH(report_financial_information.month_year)='".$searchMonth."' ";
           }
       }
       if(!empty ($searchYear)){
           $where.= " AND report_financial_year_master.financial_year='".$searchYear."' ";
       }
       $reportFinancialInformationDetails = ReportFinancialInformation::
               join('report_financial_year_master', 'report_financial_year_master.id', '=', 'report_financial_information.year_master_id')
               ->select('report_financial_year_master.financial_year','report_financial_information.*')
               ->where('report_financial_information.startup_id', '=', $id)
               ->whereRaw($where)
               ->get();//paginate(20);
       //dd(DB::getQueryLog());
       $lastFinancialInformation = ReportFinancialInformation::select('month_year')->where('startup_id', '=', $id)->orderBy('id', 'desc')->first();
        $data = [
            'start_up_id'=>$id,
            'searchYear'=>$searchYear,
            'searchMonth'=>$searchMonth,
            'lastFinancialInformation'=>$lastFinancialInformation,
            'reportFinancialInformationDetails'=>$reportFinancialInformationDetails
        ];

        return View::make('report.list_report')->with($data);
    }
    
    public function add_report_startup(){
        $id = Auth::user()->id;
        $data = array();
        $startupDetails = User::
                join('start_up_investor_rel','start_up_investor_rel.start_up_id','=','users.id')
                ->join('users as user2','user2.id','=','start_up_investor_rel.investor_id')
                ->select('users.*','user2.individual_name as investor_name')
                ->where('users.id', '=', $id)->where('users.status', '=', 'Active')->first();
        $startupInfoDetails = Entrepreneur::where('id', '=', $id)->where('status', '=', 'Active')->first();
        
        $join_date = $startupDetails->created_at;
        
        $reportFinancialInformationDetails = ReportFinancialInformation::where('startup_id', '=', $id)->get();
        $lastFinancialInformation = ReportFinancialInformation::where('startup_id', '=', $id)->orderBy('id', 'desc')->first();
        $last_financial_id = 0;
        $reportFundingStatusDetails = $reportOtherDetails = $reportTargetAchievementDetails = $reportTeamDetails = array();
        if(isset($lastFinancialInformation['id']) && !empty($lastFinancialInformation['id'])){
            $last_financial_id = $lastFinancialInformation['id'];
            $reportFundingStatusDetails = ReportFundingStatus::where('financial_info_id','=',$last_financial_id)->first();
            $reportOtherDetails = ReportOtherDetails::where('financial_info_id','=',$last_financial_id)->first();
            $reportTargetAchievementDetails = ReportTargetAchievement::where('financial_info_id','=',$last_financial_id)->first();
            $reportTeamDetails = ReportTeamDetails::where('financial_info_id','=',$last_financial_id)->first();
        }
        $data['join_date'] = $join_date;
        $data['startupDetails'] = $startupDetails;
        $data['lastFinancialInformation'] = $lastFinancialInformation;
        $data['startupInfoDetails'] = $startupInfoDetails;
        $data['reportFinancialInformationDetails'] = $reportFinancialInformationDetails;
        $data['reportFundingStatusDetails'] = $reportFundingStatusDetails;
        $data['reportOtherDetails'] = $reportOtherDetails;
        $data['reportTargetAchievementDetails'] = $reportTargetAchievementDetails;
        $data['reportTeamDetails'] = $reportTeamDetails;
        return $returnHTML = View::make('report.add_report')->with('data',$data);
    }
    
    public function save_report_startup(){
        $startup_id = Auth::user()->id;//Input::get('startup_id');
        $editable_date = Input::get('editable_date');
        $current = date('Y');
        $comming = date('y')+1;
        $financial_year = $current.'-'.$comming;
        if(!empty($startup_id) && !empty($editable_date)){
            $reportFinancialYear = new ReportFinancialYear;
            $reportFinancialYear->startup_id = $startup_id;
            $reportFinancialYear->financial_year = $financial_year;
            $reportFinancialYear->created_at = date('Y-m-d H:i:s');
            if($reportFinancialYear->save()){
                $year_master_id = $reportFinancialYear->id;
                
                $cash_in_hand = Input::get('cash_in_hand');
                $revenue = Input::get('revenue');
                $expense = Input::get('expense');
                $burn_rate = Input::get('burn_rate');
                $is_draft = Input::get('is_draft');
                
                $reportFinancialInformation = new ReportFinancialInformation;
                $reportFinancialInformation->startup_id = $startup_id;
                $reportFinancialInformation->year_master_id = $year_master_id;
                $reportFinancialInformation->month_year = date('Y-m-d',strtotime($editable_date));
                $reportFinancialInformation->cash_in_hand = $cash_in_hand;
                $reportFinancialInformation->revenue = $revenue;
                $reportFinancialInformation->expense = $expense;
                $reportFinancialInformation->burn_rate = $burn_rate;
                $reportFinancialInformation->is_draft = $is_draft;
                $reportFinancialInformation->created_at = date('Y-m-d H:i:s');
                if($reportFinancialInformation->save()){
                    $financial_info_id = $reportFinancialInformation->id;
                    
                    $remarks_fund_from_own_resources = Input::get('remarks_fund_from_own_resources');
                    $remarks_fund_form_external = Input::get('remarks_fund_form_external');
                    $remarks_iimcip_investment = Input::get('remarks_iimcip_investment');
                    $reportFundingStatus = new ReportFundingStatus;
                    $reportFundingStatus->startup_id = $startup_id;
                    $reportFundingStatus->financial_info_id = $financial_info_id;
                    $reportFundingStatus->remarks_fund_from_own_resources = $remarks_fund_from_own_resources;
                    $reportFundingStatus->remarks_fund_form_external = $remarks_fund_form_external;
                    $reportFundingStatus->remarks_iimcip_investment = $remarks_iimcip_investment;
                    $reportFundingStatus->created_at = date('Y-m-d H:i:s');
                    $reportFundingStatus->save();
                    
                    $challenges = Input::get('challenges');
                    $progress_key_activities = Input::get('progress_key_activities');
                    $funding_conversation = Input::get('funding_conversation');
                    $planning = Input::get('planning');
                    $comments = Input::get('comments');
                    $support = Input::get('support');
                    $reportOtherDetails = new ReportOtherDetails;
                    $reportOtherDetails->startup_id = $startup_id;
                    $reportOtherDetails->financial_info_id = $financial_info_id;
                    $reportOtherDetails->challenges = $challenges;
                    $reportOtherDetails->progress_key_activities = $progress_key_activities;
                    $reportOtherDetails->funding_conversation = $funding_conversation;
                    $reportOtherDetails->planning = $planning;
                    $reportOtherDetails->comments = $comments;
                    $reportOtherDetails->support = $support;
                    $reportOtherDetails->created_at = date('Y-m-d H:i:s');
                    $reportOtherDetails->save();
                    
                    $volume_annual_target = Input::get('volume_annual_target');
                    $sales_annual_target = Input::get('sales_annual_target');
                    $volume_achived_till_date = Input::get('volume_achived_till_date');
                    $sales_achived_till_date = Input::get('sales_achived_till_date');
                    $volume_sales_revenue = Input::get('volume_sales_revenue');
                    $sales_sales_revenue = Input::get('sales_sales_revenue');
                    $order_pipeline = Input::get('order_pipeline');
                    $reportTargetAchievement = new ReportTargetAchievement;
                    $reportTargetAchievement->startup_id = $startup_id;
                    $reportTargetAchievement->financial_info_id = $financial_info_id;
                    $reportTargetAchievement->volume_annual_target = $volume_annual_target;
                    $reportTargetAchievement->sales_annual_target = $sales_annual_target;
                    $reportTargetAchievement->volume_achived_till_date = $volume_achived_till_date;
                    $reportTargetAchievement->sales_achived_till_date = $sales_achived_till_date;
                    $reportTargetAchievement->volume_sales_revenue = $volume_sales_revenue;
                    $reportTargetAchievement->sales_sales_revenue = $sales_sales_revenue;
                    $reportTargetAchievement->order_pipeline = $order_pipeline;
                    $reportTargetAchievement->created_at = date('Y-m-d H:i:s');
                    $reportTargetAchievement->save();
                    
                    $total_employee = Input::get('total_employee');
                    $fulltime_employee = Input::get('fulltime_employee');
                    $parttime_employee = Input::get('parttime_employee');
                    $founder_name = Input::get('founder_name');
                    $role_function = Input::get('role_function');
                    $reportTeamDetails = new ReportTeamDetails;
                    $reportTeamDetails->startup_id = $startup_id;
                    $reportTeamDetails->financial_info_id = $financial_info_id;
                    $reportTeamDetails->total_employee = $total_employee;
                    $reportTeamDetails->fulltime_employee = $fulltime_employee;
                    $reportTeamDetails->parttime_employee = $parttime_employee;
                    $reportTeamDetails->founder_name = $founder_name;
                    $reportTeamDetails->role_function = $role_function;
                    $reportTeamDetails->created_at = date('Y-m-d H:i:s');
                    $reportTeamDetails->save();
                    
                    return Redirect::route('list_report');
                }else{
                    echo '1';
                }
            }else{
                echo '2';
            }
        }else{
            echo '3';
        }
    }
    
    public function edit_report_startup($id){
        $data = array();
        $data['financial_info_id'] = $id;
        
        $singleFinanceReport = ReportFinancialInformation::where('id', '=', $id)->first();
        $startup_id = $singleFinanceReport['startup_id'];
        $startupDetails = User::                
                join('start_up_investor_rel','start_up_investor_rel.start_up_id','=','users.id')
                ->join('users as user2','user2.id','=','start_up_investor_rel.investor_id')
                ->select('users.*','user2.individual_name as investor_name')
                ->where('users.id', '=', $startup_id)->where('users.status', '=', 'Active')->first();
        $startupInfoDetails = Entrepreneur::where('id', '=', $startup_id)->where('status', '=', 'Active')->first();
        $reportFinancialInformationDetails = ReportFinancialInformation::where('startup_id', '=', $singleFinanceReport['startup_id'])->get();
        #echo '<pre>'; echo count($reportFinancialInformationDetails);print_r($reportFinancialInformationDetails);
        $reportFundingStatusDetails = ReportFundingStatus::where('financial_info_id','=',$id)->first();
        $reportOtherDetails = ReportOtherDetails::where('financial_info_id','=',$id)->first();
        $reportTargetAchievementDetails = ReportTargetAchievement::where('financial_info_id','=',$id)->first();
        $reportTeamDetails = ReportTeamDetails::where('financial_info_id','=',$id)->first();
        
        $data['startupDetails'] = $startupDetails;
        $data['singleFinanceReport'] = $singleFinanceReport;
        $data['startupInfoDetails'] = $startupInfoDetails;
        $data['reportFinancialInformationDetails'] = $reportFinancialInformationDetails;
        $data['reportFundingStatusDetails'] = $reportFundingStatusDetails;
        $data['reportOtherDetails'] = $reportOtherDetails;
        $data['reportTargetAchievementDetails'] = $reportTargetAchievementDetails;
        $data['reportTeamDetails'] = $reportTeamDetails;
        return $returnHTML = View::make('report.edit_report')->with('data',$data);
    }
    
    public function update_report_startup(){
        
        $financial_info_id = Input::get('financial_info_id');        
        if(!empty($financial_info_id)){
            $cash_in_hand = Input::get('cash_in_hand');
            $revenue = Input::get('revenue');
            $expense = Input::get('expense');
            $burn_rate = Input::get('burn_rate');
            $is_draft = Input::get('is_draft');
            $reportFinancialInformation = ReportFinancialInformation::where('id', $financial_info_id)->firstOrFail();
            $startup_id = $reportFinancialInformation['startup_id'];
            /*echo '<pre>';
            print_r($reportFinancialInformation['startup_id']);
            die();*/
            $reportFinancialInformation->cash_in_hand = $cash_in_hand;
            $reportFinancialInformation->revenue = $revenue;
            $reportFinancialInformation->expense = $expense;
            $reportFinancialInformation->burn_rate = $burn_rate;
            $reportFinancialInformation->is_draft = $is_draft;
            $reportFinancialInformation->updated_at = date('Y-m-d H:i:s');
            if($reportFinancialInformation->save()){
                $volume_annual_target = Input::get('volume_annual_target');
                $sales_annual_target = Input::get('sales_annual_target');
                $volume_achived_till_date = Input::get('volume_achived_till_date');
                $sales_achived_till_date = Input::get('sales_achived_till_date');
                $volume_sales_revenue = Input::get('volume_sales_revenue');
                $sales_sales_revenue = Input::get('sales_sales_revenue');
                $order_pipeline = Input::get('order_pipeline');

                $reportTargetAchievement = ReportTargetAchievement::where('financial_info_id', $financial_info_id)->firstOrFail();
                $reportTargetAchievement->volume_annual_target = $volume_annual_target;
                $reportTargetAchievement->sales_annual_target = $sales_annual_target;
                $reportTargetAchievement->volume_achived_till_date = $volume_achived_till_date;
                $reportTargetAchievement->sales_achived_till_date = $sales_achived_till_date;
                $reportTargetAchievement->volume_sales_revenue = $volume_sales_revenue;
                $reportTargetAchievement->sales_sales_revenue = $sales_sales_revenue;
                $reportTargetAchievement->order_pipeline = $order_pipeline;
                $reportTargetAchievement->updated_at = date('Y-m-d H:i:s');
                $reportTargetAchievement->save();

                $total_employee = Input::get('total_employee');
                $fulltime_employee = Input::get('fulltime_employee');
                $parttime_employee = Input::get('parttime_employee');
                $founder_name = Input::get('founder_name');
                $role_function = Input::get('role_function');

                $reportTeamDetails = ReportTeamDetails::where('financial_info_id', $financial_info_id)->firstOrFail();
                $reportTeamDetails->total_employee = $total_employee;
                $reportTeamDetails->fulltime_employee = $fulltime_employee;
                $reportTeamDetails->parttime_employee = $parttime_employee;
                $reportTeamDetails->founder_name = $founder_name;
                $reportTeamDetails->role_function = $role_function;
                $reportTeamDetails->updated_at = date('Y-m-d H:i:s');
                $reportTeamDetails->save();

                $challenges = Input::get('challenges');
                $progress_key_activities = Input::get('progress_key_activities');
                $funding_conversation = Input::get('funding_conversation');
                $planning = Input::get('planning');
                $comments = Input::get('comments');
                $support = Input::get('support');

                $reportOtherDetails = ReportOtherDetails::where('financial_info_id', $financial_info_id)->firstOrFail();
                $reportOtherDetails->challenges = $challenges;
                $reportOtherDetails->progress_key_activities = $progress_key_activities;
                $reportOtherDetails->funding_conversation = $funding_conversation;
                $reportOtherDetails->planning = $planning;
                $reportOtherDetails->comments = $comments;
                $reportOtherDetails->support = $support;
                $reportOtherDetails->updated_at = date('Y-m-d H:i:s');
                $reportOtherDetails->save();

                $total_employee = Input::get('total_employee');
                $fulltime_employee = Input::get('fulltime_employee');
                $parttime_employee = Input::get('parttime_employee');
                $founder_name = Input::get('founder_name');
                $role_function = Input::get('role_function');

                $reportTeamDetails = ReportTeamDetails::where('financial_info_id', $financial_info_id)->firstOrFail();
                $reportTeamDetails->total_employee = $total_employee;
                $reportTeamDetails->fulltime_employee = $fulltime_employee;
                $reportTeamDetails->parttime_employee = $parttime_employee;
                $reportTeamDetails->founder_name = $founder_name;
                $reportTeamDetails->role_function = $role_function;
                $reportTeamDetails->updated_at = date('Y-m-d H:i:s');
                $reportTeamDetails->save();
            }else{

            }
            return Redirect::route('list_report');
            //return Redirect::URL('admin/list_report/'.$startup_id); //$startup_id
        }
    }
    
    public function download_report_startup_old($id){
        require_once Config::get('app.base_url')."mpdf/vendor/autoload.php";

        $mpdf = new mPDF();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        //$pdf_name = "test.pdf";
        ob_start();
        
        $data = array();
        $data['financial_info_id'] = $id;
        $singleFinanceReport = ReportFinancialInformation::where('id', '=', $id)->first();
        $startup_id = $singleFinanceReport['startup_id'];
        $month_year = $singleFinanceReport['month_year'];
        $startupDetails = User::
                join('start_up_investor_rel','start_up_investor_rel.start_up_id','=','users.id')
                ->join('users as user2','user2.id','=','start_up_investor_rel.investor_id')
                ->select('users.*','user2.individual_name as investor_name')
                ->where('users.id', '=', $startup_id)->where('users.status', '=', 'Active')->first();
        $startupInfoDetails = Entrepreneur::where('id', '=', $startup_id)->where('status', '=', 'Active')->first();
        $reportFinancialInformationDetails = ReportFinancialInformation::where('startup_id', '=', $singleFinanceReport['startup_id'])->get();
        $reportFundingStatusDetails = ReportFundingStatus::where('financial_info_id','=',$id)->first();
        $reportOtherDetails = ReportOtherDetails::where('financial_info_id','=',$id)->first();
        $reportTargetAchievementDetails = ReportTargetAchievement::where('financial_info_id','=',$id)->first();
        $reportTeamDetails = ReportTeamDetails::where('financial_info_id','=',$id)->first();
        
        $pdf_name =$startupDetails['name'].'-'.date('M y',strtotime($singleFinanceReport['month_year'])).'.pdf';
        
        $data['startupDetails'] = $startupDetails;
        $data['financial_year'] = $month_year;
        $data['startupInfoDetails'] = $startupInfoDetails;
        $data['singleFinanceReport'] = $singleFinanceReport;
        $data['reportFinancialInformationDetails'] = $reportFinancialInformationDetails;
        $data['reportFundingStatusDetails'] = $reportFundingStatusDetails;
        $data['reportOtherDetails'] = $reportOtherDetails;
        $data['reportTargetAchievementDetails'] = $reportTargetAchievementDetails;
        $data['reportTeamDetails'] = $reportTeamDetails;
        $html = View::make('report.generatePDF')->with('data',$data);
        
        //$html = 'test';
        ob_end_clean();
        $mpdf->WriteHTML($html);
        $mpdf->Output($pdf_name, 'D');
        echo $pdf_name;
    }
    
	public function download_report_startup($id){
		//require_once Config::get('app.base_url')."common/helpers.php";
		require_once $_SERVER['DOCUMENT_ROOT']."/iimcip-network/common/helpers.php";
		
        if(\Auth::check()){
            //require_once Config::get('app.base_url')."mpdf/vendor/autoload.php";
			//require_once('iimcip-network/mpdf/vendor/autoload.php');
			
			//echo $_SERVER['DOCUMENT_ROOT'];die;
			require_once $_SERVER['DOCUMENT_ROOT']."/iimcip-network/mpdf/vendor/autoload.php";
			
			//config("constants.site_name")

            $mpdf = new \mPDF();
            $mpdf->autoScriptToLang = true;
            $mpdf->autoLangToFont = true;
            //$pdf_name = "test.pdf";
            ob_start();
            $current = date('Y');
            $comming = date('y')+1;
            $financial_year = $current.'-'.$comming;

            $where = " 1=1 ";            
			
			//$startup_id = Auth::user()->id; //938
			$incubateMaster = NewReportIncubateMaster::find($id);
            $startup_id = $incubateMaster->startup_id;
			
			
			
			
			
            $master_id = $data['master_id'] = $id;
            $startupDetails = Users::
            //join('start_up_investor_rel','start_up_investor_rel.start_up_id','=','users.id')
                //->join('users as user2','user2.id','=','start_up_investor_rel.investor_id')
                //->leftJoin('countries as cnt1','users.country_id','=','cnt1.sortname')
                //->leftJoin('cities as cty1','users.city_id','=','cty1.id')
                //->leftJoin('countries as cnt2','users.country_id2','=','cnt2.sortname')
                //->leftJoin('cities as cty2','users.city_id2','=','cty2.id')
                select('users.*') //,'user2.contact_name as contact_name','cnt1.name as country1','cnt2.name as country2','cty1.city_name as city1','cty2.city_name as city2'
                ->where('users.id', '=', $startup_id)->where('users.status', '=', '1')->first();
            #echo '<pre>';print_r($startupDetails);
            /*$startupInfoDetails = Entrepreneur::
                leftJoin("structure_of_companies as soc","soc.id","=","entrepreneurs.structure_of_company")
                ->select('entrepreneurs.*','soc.name as soc_name')
            ->where('entrepreneurs.id', '=', $startup_id)->where('entrepreneurs.status', '=', 'Active')->first();*/
			
			
            $last_month_info = NewReportIncubateMaster::where('startup_id', '=', $startup_id)->where('id','=',$master_id)->whereRaw($where)->first();
			
			/*
            $prev_month = date("m", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." -1 Month"));
            $prev_prev_month =  date("m", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." -2 Month"));
            $prev_prev_prev_month =  date("m", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." -3 Month"));
            $currnt_month =  date("m", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." +0 Month"));
            $next_month =  date("m", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." +1 Month"));
            $next_next_month =  date("m", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." +2 Month"));
            */
			
		$prev_month = date('Y-m-d',strtotime('-1 months', strtotime($last_month_info['submit_date']))); //date('m',strtotime($last_month_info['submit_date']))-1;
        $prev_prev_month =  date('Y-m-d',strtotime('-2 months', strtotime($last_month_info['submit_date'])));
        $prev_prev_prev_month =  date('Y-m-d',strtotime('-3 months', strtotime($last_month_info['submit_date'])));
		
		
		
		$currnt_month =  $last_month_info['submit_date'];
        $next_month =  date('Y-m-d',strtotime('+1 months', strtotime($last_month_info['submit_date'])));
        $next_next_month = date('Y-m-d',strtotime('+2 months', strtotime($last_month_info['submit_date'])));
			
            
            $where = " MONTH(new_report_incubate_master.submit_date)='".$prev_month."' ";

            $reportFundingStatusDetails = $reportTargetAchievementDetails = $reportTeamDetails = $prevInformationDetails = $prev_prevInformationDetails = $prev_prev_prev_InformationDetails = $currentInformationDetails = $next_monthInformationDetails = $next_next_monthInformationDetails = array();

            if(isset($last_month_info)){ // && count($last_month_info)>0
                $reportTargetAchievementDetails = NewReportTargetAchievement::where('master_id','=',$master_id)->first();
                $reportFundingStatusDetails = NewReportFundingStatus::where('master_id','=',$master_id)->first();
                $reportTeamDetails = NewReportTeamDetails::where('master_id','=',$master_id)->first();

                $prevInformationDetails = NewReportFinancialInformation::
                where('startup_id', '=', $startup_id)
                    ->where('master_id','=',$master_id)
                    //->whereRaw(" MONTH(new_report_financial_information.submit_date)='".$prev_month."' ")
					->whereRaw(" MONTH(new_report_financial_information.submit_date)='".date("m",strtotime($prev_month))."' and YEAR(new_report_financial_information.submit_date)='".date("Y",strtotime($prev_month))."' ")
                    ->first();
                $prev_prevInformationDetails = NewReportFinancialInformation::
                where('startup_id', '=', $startup_id)
                    //->where('master_id','=',$master_id)
                    //->whereRaw(" MONTH(new_report_financial_information.submit_date)='".$prev_prev_month."' ")
					->whereRaw(" MONTH(new_report_financial_information.submit_date)='".date("m",strtotime($prev_prev_month))."' and YEAR(new_report_financial_information.submit_date)='".date("Y",strtotime($prev_prev_month))."' ")
                    ->first();
                $prev_prev_prev_InformationDetails = NewReportFinancialInformation::
                where('startup_id', '=', $startup_id)
                    //->where('master_id','=',$master_id)
                    //->whereRaw(" MONTH(new_report_financial_information.submit_date)='".$prev_prev_prev_month."' ")
					->whereRaw(" MONTH(new_report_financial_information.submit_date)='".date("m",strtotime($prev_prev_prev_month))."' and YEAR(new_report_financial_information.submit_date)='".date("Y",strtotime($prev_prev_prev_month))."' ")
                    ->first();

                $currentInformationDetails = NewReportFinancialInformationPlan::
                where('startup_id', '=', $startup_id)
                    ->where('master_id','=',$master_id)
                    //->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".$currnt_month."' ")
					->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".date("m",strtotime($currnt_month))."' and YEAR(new_report_financial_information_plan.submit_date)='".date("Y",strtotime($currnt_month))."' ")
                    ->orderBy('new_report_financial_information_plan.id', 'desc')
                    ->first();
                $next_monthInformationDetails = NewReportFinancialInformationPlan::
                where('startup_id', '=', $startup_id)
                    ->where('master_id','=',$master_id)
                    //->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".$next_month."' ")
					->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".date("m",strtotime($next_month))."' and YEAR(new_report_financial_information_plan.submit_date)='".date("Y",strtotime($next_month))."' ")
                    ->orderBy('new_report_financial_information_plan.id', 'desc')
                    ->first();
                $next_next_monthInformationDetails = NewReportFinancialInformationPlan::
                where('startup_id', '=', $startup_id)
                    ->where('master_id','=',$master_id)
                   // ->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".$next_next_month."' ")
				   ->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".date("m",strtotime($next_next_month))."' and YEAR(new_report_financial_information_plan.submit_date)='".date("Y",strtotime($next_next_month))."' ")
                    ->orderBy('new_report_financial_information_plan.id', 'desc')
                    ->first();

            }
            /*$promoterDetails = Promoter::where('user_id', '=', $startup_id)
                ->orderBy('id', 'asc')
                ->first();*/
            $pdf_name = $startupDetails['member_company'].'-'.date('M y',strtotime($last_month_info['submit_date'])).'.pdf';

            $where = " startup_target_achievement.created_at BETWEEN '" . checkFinancialStart(date('Y')) . "' AND '" . checkFinancialEnd(date('Y')) . "'  ";
            $current_target_achievement = StartupTargetAchievement::where('startup_id', $startup_id)->whereRaw($where)->first();

            $where1 = " startup_target_achievement.created_at BETWEEN '" . checkFinancialStart(date('Y') - 1) . "' AND '" . checkFinancialEnd(date('Y') - 1) . "'  ";
            $current_target_achievement1 = StartupTargetAchievement::where('startup_id', $startup_id)->whereRaw($where1)->first();

            $data['current_target_achievement'] = $current_target_achievement;
            $data['current_target_achievement1'] = $current_target_achievement1;
            $data['startupDetails'] = $startupDetails;
            $data['last_month_info'] = $last_month_info;
            $data['startupInfoDetails'] = $startupInfoDetails;
            $data['reportFundingStatusDetails'] = $reportFundingStatusDetails;
            $data['reportTargetAchievementDetails'] = $reportTargetAchievementDetails;
            $data['reportTeamDetails'] = $reportTeamDetails;

            $data['prevInformationDetails'] = $prevInformationDetails;
            $data['prev_prevInformationDetails'] = $prev_prevInformationDetails;
            $data['prev_prev_prev_InformationDetails'] = $prev_prev_prev_InformationDetails;

            //$data['promoterDetails'] = $promoterDetails;
            $data['financial_year'] = date('Y');//$financial_year;
            $data['currentInformationDetails'] = $currentInformationDetails;
            $data['next_monthInformationDetails'] = $next_monthInformationDetails;
            $data['next_next_monthInformationDetails'] = $next_next_monthInformationDetails;
            $data['pdf_name'] = $pdf_name;
//            echo $data['financial_year'] ; die();

            //$html = View::make('new_report.demo.generatePDF')->with('data',$data);
			
			$html = view('frontend.new_report.demo.generatePDF')->with('data',$data);

//            $html = 'test';
            ob_end_clean();

//            $mpdf->SetDisplayMode('fullpage');
//            $mpdf->list_indent_first_level = 0;
            $mpdf->WriteHTML($html);
            $mpdf->Output($pdf_name, 'D');
            echo $pdf_name;
        }else{
            return Redirect::route('home')->with('message','Please Login');
        }
    }
	
	
    public function new_index_startup() {
       if(\Auth::check()){
            $searchYear =  Input::get('year'); 
            $searchMonth = Input::get('month');
            $id = Auth::user()->id;
            $where = "1=1";
            if(!empty ($searchMonth)){
                if($searchMonth=='last3'){
                    $currentMonthNo = date('m');
                    $prevMonthNo = $currentMonthNo-2;
                    $where.= " AND MONTH(new_report_incubate_master.submit_date) BETWEEN '".$prevMonthNo."' AND '".$currentMonthNo."'  ";
                }elseif($searchMonth=='last6'){
                    $currentMonthNo = date('m');
                    $prevMonthNo = $currentMonthNo-5;
                    $where.= " AND MONTH(new_report_incubate_master.submit_date) BETWEEN '".$prevMonthNo."' AND '".$currentMonthNo."'  ";
                }else{
                    $where.= " AND MONTH(new_report_incubate_master.submit_date)='".$searchMonth."' ";
                }
            }
            if(!empty ($searchYear)){
                $where.= " AND YEAR(new_report_incubate_master.submit_date)='".$searchYear."' ";
            }
            $myReports = NewReportIncubateMaster::
                    join('users as users','users.id','=','new_report_incubate_master.startup_id')
                    ->select('new_report_incubate_master.submit_date','new_report_incubate_master.id','users.name','new_report_incubate_master.is_draft','users.individual_name')
                    ->where('startup_id','=',$id)->whereRaw($where)->orderBy('submit_date')->paginate(10);
            $current_month_info = NewReportIncubateMaster::where('new_report_incubate_master.startup_id', '=', $id)->whereRaw(" MONTH(new_report_incubate_master.submit_date)='".date('m')."' ")->first();
            
             $data = [
                 'start_up_id'=>$id,
                 'searchYear'=>$searchYear,
                 'searchMonth'=>$searchMonth,
                 'current_month_info'=>$current_month_info,
                 'myReports'=>$myReports
             ];

             return View::make('new_report.list_report')->with($data);
       }else{
           return Redirect::route('home')->with('message','Please Login');
       }
    }
    
    public function new_add_report_startup(){
        if(\Auth::check()){
            $id = Auth::user()->id;
            $data = array();
            $startupDetails = User::
                    join('start_up_investor_rel','start_up_investor_rel.start_up_id','=','users.id')
                    ->join('users as user2','user2.id','=','start_up_investor_rel.investor_id')
                    ->select('users.*','user2.individual_name as investor_name')
                    ->where('users.id', '=', $id)->where('users.status', '=', 'Active')->first();
            
            $startupInfoDetails = Entrepreneur::where('id', '=', $id)->where('status', '=', 'Active')->first();
            $join_date = $startupDetails->created_at;
            $prev_month = date('m')-1;
            $prev_prev_month = date('m')-2;
            $prev_prev_prev_month = date('m')-3;
            $currnt_month = date('m');
            $next_month = date('m')+1;
            $next_next_month = date('m')+2;
            
            $where = " MONTH(new_report_incubate_master.submit_date)='".$prev_month."' ";
            $last_month_info = NewReportIncubateMaster::where('startup_id', '=', $id)->whereRaw($where)->first();
            $reportFundingStatusDetails = $reportTargetAchievementDetails = $reportTeamDetails = array();
            if(isset($last_month_info) && count($last_month_info)>0){
                $master_id = $last_month_info['id'];
                $reportTargetAchievementDetails = NewReportTargetAchievement::where('master_id','=',$master_id)->first();
                $reportFundingStatusDetails = NewReportFundingStatus::where('master_id','=',$master_id)->first();
                $reportTeamDetails = NewReportTeamDetails::where('master_id','=',$master_id)->first();
            }
            
            $prevInformationDetails = NewReportFinancialInformation::where('startup_id', '=', $id)
                    ->whereRaw(" MONTH(new_report_financial_information.submit_date)='".$prev_month."' ")->first();
            $prev_prevInformationDetails = NewReportFinancialInformation::where('startup_id', '=', $id)
                    ->whereRaw(" MONTH(new_report_financial_information.submit_date)='".$prev_prev_month."' ")->first();
            $prev_prev_prev_InformationDetails = NewReportFinancialInformation::where('startup_id', '=', $id)
                    ->whereRaw(" MONTH(new_report_financial_information.submit_date)='".$prev_prev_prev_month."' ")->first();
            
            $currentInformationDetails = NewReportFinancialInformationPlan::where('startup_id', '=', $id)
                    ->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".$currnt_month."' ")->first();
            $next_monthInformationDetails = NewReportFinancialInformationPlan::where('startup_id', '=', $id)
                    ->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".$next_month."' ")->first();
            $next_next_monthInformationDetails = NewReportFinancialInformationPlan::where('startup_id', '=', $id)
                    ->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".$next_next_month."' ")->first();
            
           
            $data['join_date'] = $join_date;
            $data['startupDetails'] = $startupDetails;
            $data['last_month_info'] = $last_month_info;
            $data['startupInfoDetails'] = $startupInfoDetails;
            $data['reportFundingStatusDetails'] = $reportFundingStatusDetails;
            $data['reportTargetAchievementDetails'] = $reportTargetAchievementDetails;
            $data['reportTeamDetails'] = $reportTeamDetails;
            
            $data['prevInformationDetails'] = $prevInformationDetails;
            $data['prev_prevInformationDetails'] = $prev_prevInformationDetails;
            $data['prev_prev_prev_InformationDetails'] = $prev_prev_prev_InformationDetails;
            
            $data['currentInformationDetails'] = $currentInformationDetails;
            $data['next_monthInformationDetails'] = $next_monthInformationDetails;
            $data['next_next_monthInformationDetails'] = $next_next_monthInformationDetails;
            
            return $returnHTML = View::make('new_report.add_report')->with('data',$data);
        }else{
           return Redirect::route('home')->with('message','Please Login');
       }
    }
    
    public function new_save_report_startup(){
        if(\Auth::check()){
            $startup_id = Auth::user()->id;
            
            if(!empty($startup_id)){
                $challenges = Input::get('challenges');
                $progress_key_activities = Input::get('progress_key_activities');
                $funding_conversation = Input::get('funding_conversation');
                $planning = Input::get('planning');
                $awards_won = Input::get('awards_won');
                $comments = Input::get('comments');
                $support = Input::get('support');
                $cash_in_hand = Input::get('cash_in_hand');
                $is_draft = Input::get('is_draft');
                
                //check already this month report submitted or not
                $current_month_info = NewReportIncubateMaster::where('startup_id', '=', $startup_id)->whereRaw(" MONTH(new_report_incubate_master.submit_date)='".date('m')."' ")->first();
                //end                
                if(isset($current_month_info) && count($current_month_info)>0){
                    $incubateMaster = NewReportIncubateMaster::find($current_month_info['id']);
                    $incubateMaster->challenges = $challenges;
                    $incubateMaster->progress_key_activities = $progress_key_activities;
                    $incubateMaster->funding_conversation = $funding_conversation;
                    $incubateMaster->planning = $planning;
                    $incubateMaster->awards_won = $awards_won;
                    $incubateMaster->comments = $comments;
                    $incubateMaster->support = $support;
                    $incubateMaster->cash_in_hand = $cash_in_hand;
                    $incubateMaster->submit_date = date('Y-m-d');
                    $incubateMaster->is_draft = $is_draft;
                }else{
                    $incubateMaster = new NewReportIncubateMaster;                
                    $incubateMaster->startup_id = $startup_id;
                    $incubateMaster->challenges = $challenges;
                    $incubateMaster->progress_key_activities = $progress_key_activities;
                    $incubateMaster->funding_conversation = $funding_conversation;
                    $incubateMaster->planning = $planning;
                    $incubateMaster->awards_won = $awards_won;
                    $incubateMaster->comments = $comments;
                    $incubateMaster->support = $support;
                    $incubateMaster->cash_in_hand = $cash_in_hand;
                    $incubateMaster->submit_date = date('Y-m-d');
                    $incubateMaster->is_draft = $is_draft;
                    $incubateMaster->created_at = date('Y-m-d H:i:s');
                }
                if($incubateMaster->save()){
                    $master_id = $incubateMaster->id;
                    
                    //NewReportFinancialInformation                    
                    $date_revenue_3 = Input::get('date_revenue_3');
                    $revenue_3 = Input::get('revenue_3');
                    $expense_3 = Input::get('expense_3');
                    $burn_rate_3 = Input::get('burn_rate_3');
                    if(!empty($revenue_3) && !empty($expense_3)){
                        $financial_report_3 = new NewReportFinancialInformation;
                        $financial_report_3->startup_id = $startup_id;
                        $financial_report_3->master_id = $master_id;
                        $financial_report_3->submit_date = $date_revenue_3;
                        $financial_report_3->revenue = $revenue_3;
                        $financial_report_3->expense = $expense_3;
                        $financial_report_3->burn_rate = $burn_rate_3;
                        $financial_report_3->is_draft = $is_draft;
                        $financial_report_3->month_year	 = date('Y-m-d');
                        $financial_report_3->save();
                    }
                    $date_revenue_2 = Input::get('date_revenue_2');
                    $revenue_2 = Input::get('revenue_2');
                    $expense_2 = Input::get('expense_2');
                    $burn_rate_2 = Input::get('burn_rate_2');
                    if(!empty($revenue_2) && !empty($expense_2)){
                        $financial_report_2 = new NewReportFinancialInformation;
                        $financial_report_2->startup_id = $startup_id;
                        $financial_report_2->master_id = $master_id;
                        $financial_report_2->submit_date = $date_revenue_2;//date('Y-m-d');
                        $financial_report_2->revenue = $revenue_2;
                        $financial_report_2->expense = $expense_2;
                        $financial_report_2->burn_rate = $burn_rate_2;
                        $financial_report_2->is_draft = $is_draft;
                        $financial_report_2->month_year	 = date('Y-m-d');
                        $financial_report_2->save();
                    }
                    $date_revenue_1 = Input::get('date_revenue_1');
                    $revenue_1 = Input::get('revenue_1');
                    $expense_1 = Input::get('expense_1');
                    $burn_rate_1 = Input::get('burn_rate_1');
                    if(!empty($revenue_1) && !empty($expense_1)){
                        $financial_report_1 = new NewReportFinancialInformation;
                        $financial_report_1->startup_id = $startup_id;
                        $financial_report_1->master_id = $master_id;
                        $financial_report_1->submit_date = $date_revenue_1;//date('Y-m-d');
                        $financial_report_1->revenue = $revenue_1;
                        $financial_report_1->expense = $expense_1;
                        $financial_report_1->burn_rate = $burn_rate_1;
                        $financial_report_1->is_draft = $is_draft;
                        $financial_report_1->month_year	 = date('Y-m-d');
                        $financial_report_1->save();
                    }
                    //end
                    //NewReportFinancialInformationPlan
                    $submit_plan_0 = Input::get('date_revenue_plan_0');
                    $revenue_plan_0 = Input::get('revenue_plan_0');
                    $expense_plan_0 = Input::get('expense_plan_0');
                    $burn_rate_plan_0 = Input::get('burn_rate_plan_0');
                    if(!empty($revenue_plan_0) && !empty($expense_plan_0)){
                        $financial_report_plan = new NewReportFinancialInformationPlan;
                        $financial_report_plan->startup_id = $startup_id;
                        $financial_report_plan->master_id = $master_id;
                        $financial_report_plan->submit_date = $submit_plan_0;//date('Y-m-d');
                        $financial_report_plan->revenue = $revenue_plan_0;
                        $financial_report_plan->expense = $expense_plan_0;
                        $financial_report_plan->burn_rate = $burn_rate_plan_0;
                        $financial_report_plan->is_draft = $is_draft;
                        $financial_report_plan->month_year = date('Y-m-d');
                        $financial_report_plan->save();
                    }
                    $submit_plan_1 = Input::get('date_revenue_plan_1');
                    $revenue_plan_1 = Input::get('revenue_plan_1');
                    $expense_plan_1 = Input::get('expense_plan_1');
                    $burn_rate_plan_1 = Input::get('burn_rate_plan_1');
                    if(!empty($revenue_plan_1) && !empty($expense_plan_1)){
                        $financial_report_plan_1 = new NewReportFinancialInformationPlan;
                        $financial_report_plan_1->startup_id = $startup_id;
                        $financial_report_plan_1->master_id = $master_id;
                        $financial_report_plan_1->submit_date = $submit_plan_1;
                        $financial_report_plan_1->revenue = $revenue_plan_1;
                        $financial_report_plan_1->expense = $expense_plan_1;
                        $financial_report_plan_1->burn_rate = $burn_rate_plan_1;
                        $financial_report_plan_1->is_draft = $is_draft;
                        $financial_report_plan_1->month_year = date('Y-m-d');
                        $financial_report_plan_1->save();
                    }
                    $submit_plan_2 = Input::get('date_revenue_plan_2');
                    $revenue_plan_2 = Input::get('revenue_plan_2');
                    $expense_plan_2 = Input::get('expense_plan_2');
                    $burn_rate_plan_2 = Input::get('burn_rate_plan_2');
                    if(!empty($revenue_plan_2) && !empty($expense_plan_2)){
                        $financial_report_plan_2 = new NewReportFinancialInformationPlan;
                        $financial_report_plan_2->startup_id = $startup_id;
                        $financial_report_plan_2->master_id = $master_id;
                        $financial_report_plan_2->submit_date = $submit_plan_2;
                        $financial_report_plan_2->revenue = $revenue_plan_2;
                        $financial_report_plan_2->expense = $expense_plan_2;
                        $financial_report_plan_2->burn_rate = $burn_rate_plan_2;
                        $financial_report_plan_2->is_draft = $is_draft;
                        $financial_report_plan_2->month_year = date('Y-m-d');
                        $financial_report_plan_2->save();
                    }
                    //end

                    $remarks_fund_from_own_resources = Input::get('remarks_fund_from_own_resources');
                    $remarks_fund_form_external = Input::get('remarks_fund_form_external');
                    $remarks_iimcip_investment = Input::get('remarks_iimcip_investment');
                    $reportFundingStatus = new NewReportFundingStatus;
                    $reportFundingStatus->startup_id = $startup_id;
                    $reportFundingStatus->master_id = $master_id;
                    $reportFundingStatus->remarks_fund_from_own_resources = $remarks_fund_from_own_resources;
                    $reportFundingStatus->remarks_fund_form_external = $remarks_fund_form_external;
                    $reportFundingStatus->remarks_iimcip_investment = $remarks_iimcip_investment;
                    $reportFundingStatus->created_at = date('Y-m-d H:i:s');
                    $reportFundingStatus->save();

                    $volume_annual_target = Input::get('volume_annual_target');
                    $sales_annual_target = Input::get('sales_annual_target');
                    $volume_achived_till_date = Input::get('volume_achived_till_date');
                    $sales_achived_till_date = Input::get('sales_achived_till_date');
                    $volume_sales_revenue = Input::get('volume_sales_revenue');
                    $sales_sales_revenue = Input::get('sales_sales_revenue');
                    $order_pipeline = Input::get('order_pipeline');
                    $reportTargetAchievement = new NewReportTargetAchievement;
                    $reportTargetAchievement->startup_id = $startup_id;
                    $reportTargetAchievement->master_id = $master_id;
                    $reportTargetAchievement->volume_annual_target = $volume_annual_target;
                    $reportTargetAchievement->sales_annual_target = $sales_annual_target;
                    $reportTargetAchievement->volume_achived_till_date = $volume_achived_till_date;
                    $reportTargetAchievement->sales_achived_till_date = $sales_achived_till_date;
                    $reportTargetAchievement->volume_sales_revenue = $volume_sales_revenue;
                    $reportTargetAchievement->sales_sales_revenue = $sales_sales_revenue;
                    $reportTargetAchievement->order_pipeline = $order_pipeline;
                    $reportTargetAchievement->created_at = date('Y-m-d H:i:s');
                    $reportTargetAchievement->save();

                    $total_employee = Input::get('total_employee');
                    $fulltime_employee = Input::get('fulltime_employee');
                    $parttime_employee = Input::get('parttime_employee');
                    $founder_name = Input::get('founder_name');
                    $role_function = Input::get('role_function');
                    $reportTeamDetails = new NewReportTeamDetails;
                    $reportTeamDetails->startup_id = $startup_id;
                    $reportTeamDetails->master_id = $master_id;
                    $reportTeamDetails->total_employee = $total_employee;
                    $reportTeamDetails->fulltime_employee = $fulltime_employee;
                    $reportTeamDetails->parttime_employee = $parttime_employee;
                    $reportTeamDetails->founder_name = $founder_name;
                    $reportTeamDetails->role_function = $role_function;
                    $reportTeamDetails->created_at = date('Y-m-d H:i:s');
                    $reportTeamDetails->save();

                    return Redirect::route('incubate_list_report');
                    
                }else{
                    echo '2';
                }
            }else{
                echo '3';
            }
        }else{
           return Redirect::route('home')->with('message','Please Login');
       }
    }
    
    public function new_edit_report_startup($id){
        $data = array();
        if(\Auth::check()){
            $where = " 1=1 ";
            $startup_id = Auth::user()->id;
            $master_id = $data['master_id'] = $id;
            $startupDetails = User::
                    join('start_up_investor_rel','start_up_investor_rel.start_up_id','=','users.id')
                    ->join('users as user2','user2.id','=','start_up_investor_rel.investor_id')
                    ->select('users.*','user2.individual_name as investor_name')
                    ->where('users.id', '=', $startup_id)->where('users.status', '=', 'Active')->first();

            $startupInfoDetails = Entrepreneur::where('id', '=', $startup_id)->where('status', '=', 'Active')->first();
            $last_month_info = NewReportIncubateMaster::where('startup_id', '=', $startup_id)->where('id','=',$master_id)->whereRaw($where)->first();
            
            $prev_month = date('m',strtotime($last_month_info['submit_date']))-1;
            $prev_prev_month =  date('m',strtotime($last_month_info['submit_date']))-2;
            $prev_prev_prev_month =  date('m',strtotime($last_month_info['submit_date']))-3;
            $currnt_month =  date('m',strtotime($last_month_info['submit_date']));
            $next_month =  date('m',strtotime($last_month_info['submit_date']))+1;
            $next_next_month =  date('m',strtotime($last_month_info['submit_date']))+2;

            $where = " MONTH(new_report_incubate_master.submit_date)='".$prev_month."' ";
            
            $reportFundingStatusDetails = $reportTargetAchievementDetails = $reportTeamDetails = $prevInformationDetails = $prev_prevInformationDetails = $prev_prev_prev_InformationDetails = $currentInformationDetails = $next_monthInformationDetails = $next_next_monthInformationDetails = array();
            
            if(isset($last_month_info) && count($last_month_info)>0){
                $reportTargetAchievementDetails = NewReportTargetAchievement::where('master_id','=',$master_id)->first();
                $reportFundingStatusDetails = NewReportFundingStatus::where('master_id','=',$master_id)->first();
                $reportTeamDetails = NewReportTeamDetails::where('master_id','=',$master_id)->first();            

                $prevInformationDetails = NewReportFinancialInformation::
                        where('startup_id', '=', $startup_id)
                       // ->where('master_id','=',$master_id)
                        ->whereRaw(" MONTH(new_report_financial_information.submit_date)='".$prev_month."' ")
                        ->orderBy('new_report_financial_information.id', 'desc')
                        ->first();
                $prev_prevInformationDetails = NewReportFinancialInformation::
                        where('startup_id', '=', $startup_id)
                        //->where('master_id','=',$master_id)
                        ->whereRaw(" MONTH(new_report_financial_information.submit_date)='".$prev_prev_month."' ")
                        ->orderBy('new_report_financial_information.id', 'desc')
                        ->first();
                $prev_prev_prev_InformationDetails = NewReportFinancialInformation::
                        where('startup_id', '=', $startup_id)
                        //->where('master_id','=',$master_id)
                        ->whereRaw(" MONTH(new_report_financial_information.submit_date)='".$prev_prev_prev_month."' ")
                        ->orderBy('new_report_financial_information.id', 'desc')
                        ->first();

                $currentInformationDetails = NewReportFinancialInformationPlan::
                        where('startup_id', '=', $startup_id)
                        //->where('master_id','=',$master_id)
                        ->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".$currnt_month."' ")
                        ->orderBy('new_report_financial_information_plan.id', 'desc')
                        ->first();
                $next_monthInformationDetails = NewReportFinancialInformationPlan::
                        where('startup_id', '=', $startup_id)
                        //->where('master_id','=',$master_id)
                        ->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".$next_month."' ")
                        ->orderBy('new_report_financial_information_plan.id', 'desc')
                        ->first();
                $next_next_monthInformationDetails = NewReportFinancialInformationPlan::
                        where('startup_id', '=', $startup_id)
                        //->where('master_id','=',$master_id)
                        ->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".$next_next_month."' ")
                        ->orderBy('new_report_financial_information_plan.id', 'desc')
                        ->first();

            }
            
            $data['master_id'] = $id;
            $data['startupDetails'] = $startupDetails;
            $data['last_month_info'] = $last_month_info;
            $data['startupInfoDetails'] = $startupInfoDetails;
            $data['reportFundingStatusDetails'] = $reportFundingStatusDetails;
            $data['reportTargetAchievementDetails'] = $reportTargetAchievementDetails;
            $data['reportTeamDetails'] = $reportTeamDetails;

            $data['prevInformationDetails'] = $prevInformationDetails;
            $data['prev_prevInformationDetails'] = $prev_prevInformationDetails;
            $data['prev_prev_prev_InformationDetails'] = $prev_prev_prev_InformationDetails;

            $data['currentInformationDetails'] = $currentInformationDetails;
            $data['next_monthInformationDetails'] = $next_monthInformationDetails;
            $data['next_next_monthInformationDetails'] = $next_next_monthInformationDetails;
            //echo '<pre>'; print_r($data); die();
            return $returnHTML = View::make('new_report.edit_report')->with('data',$data);
        }else{
           return Redirect::route('home')->with('message','Please Login');
       }
        
    }
    
    public function new_update_report_startup(){
        
        $master_id = Input::get('master_id');        
        if(!empty($master_id)){
            $challenges = Input::get('challenges');
            $progress_key_activities = Input::get('progress_key_activities');
            $funding_conversation = Input::get('funding_conversation');
            $planning = Input::get('planning');
            $awards_won = Input::get('awards_won');
            $comments = Input::get('comments');
            $support = Input::get('support');
            $cash_in_hand = Input::get('cash_in_hand');
            $is_draft = Input::get('is_draft');
            $incubateMaster = NewReportIncubateMaster::find($master_id);
            $incubateMaster->progress_key_activities = $progress_key_activities;
            $incubateMaster->funding_conversation = $funding_conversation;
            $incubateMaster->planning = $planning;
            $incubateMaster->awards_won = $awards_won;
            $incubateMaster->challenges = $challenges;
            $incubateMaster->comments = $comments;
            $incubateMaster->support = $support;
            $incubateMaster->cash_in_hand = $cash_in_hand;
            $incubateMaster->updated_at = date('Y-m-d H:i:s');
            $incubateMaster->is_draft = $is_draft;
            if($incubateMaster->save()){
                $startup_id = $incubateMaster->startup_id;
                $id_revenue_3 = Input::get('id_revenue_3');
                $date_revenue_3 = Input::get('date_revenue_3');
                $revenue_3 = Input::get('revenue_3');
                $expense_3 = Input::get('expense_3');
                $burn_rate_3 = Input::get('burn_rate_3');
                if(!empty($revenue_3) && !empty($expense_3)){
                    if(!empty($id_revenue_3)){
                        $financial_report_3 =NewReportFinancialInformation::find($id_revenue_3);
                        $financial_report_3->revenue = $revenue_3;
                        $financial_report_3->expense = $expense_3;
                        $financial_report_3->burn_rate = $burn_rate_3;
                        $financial_report_3->is_draft = $is_draft;
                        $financial_report_3->updated_at = date('Y-m-d H:i:s');
                    }else{
                        $financial_report_3 = new NewReportFinancialInformation;
                        $financial_report_3->startup_id = $startup_id;
                        $financial_report_3->master_id = $master_id;
                        $financial_report_3->submit_date = $date_revenue_3;                        
                        $financial_report_3->month_year	 = date('Y-m-d');
                        $financial_report_3->created_at = date('Y-m-d H:i:s');
                    }
                    $financial_report_3->revenue = $revenue_3;
                    $financial_report_3->expense = $expense_3;
                    $financial_report_3->burn_rate = $burn_rate_3;
                    $financial_report_3->is_draft = $is_draft;
                    $financial_report_3->save();
                }
                $id_revenue_2 = Input::get('id_revenue_2');
                $date_revenue_2 = Input::get('date_revenue_2');
                $revenue_2 = Input::get('revenue_2');
                $expense_2 = Input::get('expense_2');
                $burn_rate_2 = Input::get('burn_rate_2');
                if(!empty($revenue_2) && !empty($expense_2)){
                    if(!empty($id_revenue_2)){
                        $financial_report_2 = NewReportFinancialInformation::find($id_revenue_2);
                        $financial_report_2->updated_at = date('Y-m-d H:i:s');
                    }else{
                        $financial_report_2 = new NewReportFinancialInformation;
                        $financial_report_2->startup_id = $startup_id;
                        $financial_report_2->master_id = $master_id;
                        $financial_report_2->submit_date = $date_revenue_2;                        
                        $financial_report_2->month_year	 = date('Y-m-d');
                        $financial_report_2->created_at = date('Y-m-d H:i:s');
                    }
                    $financial_report_2->revenue = $revenue_2;
                    $financial_report_2->expense = $expense_2;
                    $financial_report_2->burn_rate = $burn_rate_2;
                    $financial_report_2->is_draft = $is_draft;
                    $financial_report_2->save();
                }
                $id_revenue_1 = Input::get('id_revenue_1');
                $date_revenue_1 = Input::get('date_revenue_1');
                $revenue_1 = Input::get('revenue_1');
                $expense_1 = Input::get('expense_1');
                $burn_rate_1 = Input::get('burn_rate_1');
                if(!empty($revenue_1) && !empty($expense_1)){
                    if(!empty($id_revenue_1)){
                        $financial_report_1 = NewReportFinancialInformation::find($id_revenue_1);
                        $financial_report_1->updated_at = date('Y-m-d H:i:s');
                    }else{
                        $financial_report_1 = new NewReportFinancialInformation;
                        $financial_report_1->startup_id = $startup_id;
                        $financial_report_1->master_id = $master_id;
                        $financial_report_1->submit_date = $date_revenue_1;                        
                        $financial_report_1->month_year	 = date('Y-m-d');
                        $financial_report_1->created_at = date('Y-m-d H:i:s');
                    }
                    $financial_report_1->revenue = $revenue_1;
                    $financial_report_1->expense = $expense_1;
                    $financial_report_1->burn_rate = $burn_rate_1;
                    $financial_report_1->is_draft = $is_draft;
                    $financial_report_1->save();
                }
                //end
                //NewReportFinancialInformationPlan
                $id_plan_0 = Input::get('id_revenue_plan_0');
                $submit_plan_0 = Input::get('date_revenue_plan_0');
                $revenue_plan_0 = Input::get('revenue_plan_0');
                $expense_plan_0 = Input::get('expense_plan_0');
                $burn_rate_plan_0 = Input::get('burn_rate_plan_0');
                if(!empty($revenue_plan_0) && !empty($expense_plan_0)){
                    if(!empty($id_plan_0)){
                        $financial_report_plan = NewReportFinancialInformationPlan::find($id_plan_0);
                        $financial_report_plan->updated_at = date('Y-m-d H:i:s');
                    }else{
                        $financial_report_plan = new NewReportFinancialInformationPlan;
                        $financial_report_plan->startup_id = $startup_id;
                        $financial_report_plan->master_id = $master_id;
                        $financial_report_plan->submit_date = $submit_plan_0;
                        $financial_report_plan->month_year = date('Y-m-d');
                        $financial_report_plan->created_at = date('Y-m-d H:i:s');
                    }
                    $financial_report_plan->revenue = $revenue_plan_0;
                    $financial_report_plan->expense = $expense_plan_0;
                    $financial_report_plan->burn_rate = $burn_rate_plan_0;
                    $financial_report_plan->is_draft = $is_draft;
                    $financial_report_plan->save();
                }
                $id_plan_1 = Input::get('id_revenue_plan_1');
                $submit_plan_1 = Input::get('date_revenue_plan_1');
                $revenue_plan_1 = Input::get('revenue_plan_1');
                $expense_plan_1 = Input::get('expense_plan_1');
                $burn_rate_plan_1 = Input::get('burn_rate_plan_1');
                if(!empty($revenue_plan_1) && !empty($expense_plan_1)){
                    
                    if(!empty($id_plan_1)){
                        $financial_report_plan_1 = NewReportFinancialInformationPlan::find($id_plan_1);
                        $financial_report_plan_1->updated_at = date('Y-m-d H:i:s');
                    }else{
                        $financial_report_plan_1 = new NewReportFinancialInformationPlan;
                        $financial_report_plan_1->startup_id = $startup_id;
                        $financial_report_plan_1->master_id = $master_id;
                        $financial_report_plan_1->submit_date = $submit_plan_1;
                        $financial_report_plan_1->month_year = date('Y-m-d');
                        $financial_report_plan_1->created_at = date('Y-m-d H:i:s');
                    }
                    $financial_report_plan_1->revenue = $revenue_plan_1;
                    $financial_report_plan_1->expense = $expense_plan_1;
                    $financial_report_plan_1->burn_rate = $burn_rate_plan_1;
                    $financial_report_plan_1->is_draft = $is_draft;
                    $financial_report_plan_1->save();
                    
                }
                $id_plan_2 = Input::get('id_revenue_plan_2');
                $submit_plan_2 = Input::get('date_revenue_plan_2');
                $revenue_plan_2 = Input::get('revenue_plan_2');
                $expense_plan_2 = Input::get('expense_plan_2');
                $burn_rate_plan_2 = Input::get('burn_rate_plan_2');
                if(!empty($revenue_plan_2) && !empty($expense_plan_2)){
                    if(!empty($id_plan_2)){
                        $financial_report_plan_2 = NewReportFinancialInformationPlan::find($id_plan_2);
                        $financial_report_plan_2->updated_at = date('Y-m-d H:i:s');
                    }else{
                        $financial_report_plan_2 = new NewReportFinancialInformationPlan;
                        $financial_report_plan_2->startup_id = $startup_id;
                        $financial_report_plan_2->master_id = $master_id;
                        $financial_report_plan_2->submit_date = $submit_plan_2;
                        $financial_report_plan_2->month_year = date('Y-m-d');
                        $financial_report_plan_2->created_at = date('Y-m-d H:i:s');
                    }
                    $financial_report_plan_2->revenue = $revenue_plan_2;
                    $financial_report_plan_2->expense = $expense_plan_2;
                    $financial_report_plan_2->burn_rate = $burn_rate_plan_2;
                    $financial_report_plan_2->is_draft = $is_draft;
                    $financial_report_plan_2->save();
                }
                //ReportTeamDetails::where('master_id', $master_id)->firstOrFail();
                $remarks_fund_from_own_resources = Input::get('remarks_fund_from_own_resources');
                $remarks_fund_form_external = Input::get('remarks_fund_form_external');
                $remarks_iimcip_investment = Input::get('remarks_iimcip_investment');
                $reportFundingStatus = NewReportFundingStatus::where('master_id', $master_id)->firstOrFail();
                $reportFundingStatus->remarks_fund_from_own_resources = $remarks_fund_from_own_resources;
                $reportFundingStatus->remarks_fund_form_external = $remarks_fund_form_external;
                $reportFundingStatus->remarks_iimcip_investment = $remarks_iimcip_investment;
                $reportFundingStatus->updated_at = date('Y-m-d H:i:s');
                $reportFundingStatus->save();

                $volume_annual_target = Input::get('volume_annual_target');
                $sales_annual_target = Input::get('sales_annual_target');
                $volume_achived_till_date = Input::get('volume_achived_till_date');
                $sales_achived_till_date = Input::get('sales_achived_till_date');
                $volume_sales_revenue = Input::get('volume_sales_revenue');
                $sales_sales_revenue = Input::get('sales_sales_revenue');
                $order_pipeline = Input::get('order_pipeline');
                $reportTargetAchievement = NewReportTargetAchievement::where('master_id', $master_id)->firstOrFail();
                $reportTargetAchievement->volume_annual_target = $volume_annual_target;
                $reportTargetAchievement->sales_annual_target = $sales_annual_target;
                $reportTargetAchievement->volume_achived_till_date = $volume_achived_till_date;
                $reportTargetAchievement->sales_achived_till_date = $sales_achived_till_date;
                $reportTargetAchievement->volume_sales_revenue = $volume_sales_revenue;
                $reportTargetAchievement->sales_sales_revenue = $sales_sales_revenue;
                $reportTargetAchievement->order_pipeline = $order_pipeline;
                $reportTargetAchievement->updated_at = date('Y-m-d H:i:s');
                $reportTargetAchievement->save();

                $total_employee = Input::get('total_employee');
                $fulltime_employee = Input::get('fulltime_employee');
                $parttime_employee = Input::get('parttime_employee');
                $founder_name = Input::get('founder_name');
                $role_function = Input::get('role_function');
                $reportTeamDetails = NewReportTeamDetails::where('master_id', $master_id)->firstOrFail();
                $reportTeamDetails->total_employee = $total_employee;
                $reportTeamDetails->fulltime_employee = $fulltime_employee;
                $reportTeamDetails->parttime_employee = $parttime_employee;
                $reportTeamDetails->founder_name = $founder_name;
                $reportTeamDetails->role_function = $role_function;
                $reportTeamDetails->updated_at = date('Y-m-d H:i:s');
                $reportTeamDetails->save();
            }
            return Redirect::route('incubate_list_report');
            //return Redirect::URL('admin/list_report/'.$startup_id); //$startup_id
        }
    }
    
    public function new_download_report_startup($id){
        if(\Auth::check()){
            require_once Config::get('app.base_url')."mpdf/vendor/autoload.php";

            $mpdf = new mPDF();
            $mpdf->autoScriptToLang = true;
            $mpdf->autoLangToFont = true;
            //$pdf_name = "test.pdf";
            ob_start();

            $where = " 1=1 ";
            $startup_id = Auth::user()->id;
            $master_id = $data['master_id'] = $id;
            $startupDetails = User::
                    join('start_up_investor_rel','start_up_investor_rel.start_up_id','=','users.id')
                    ->join('users as user2','user2.id','=','start_up_investor_rel.investor_id')
                    ->select('users.*','user2.individual_name as investor_name')
                    ->where('users.id', '=', $startup_id)->where('users.status', '=', 'Active')->first();

            $startupInfoDetails = Entrepreneur::where('id', '=', $startup_id)->where('status', '=', 'Active')->first();
            $last_month_info = NewReportIncubateMaster::where('startup_id', '=', $startup_id)->where('id','=',$master_id)->whereRaw($where)->first();
            
            $prev_month = date('m',strtotime($last_month_info['submit_date']))-1;
            $prev_prev_month =  date('m',strtotime($last_month_info['submit_date']))-2;
            $prev_prev_prev_month =  date('m',strtotime($last_month_info['submit_date']))-3;
            $currnt_month =  date('m',strtotime($last_month_info['submit_date']));
            $next_month =  date('m',strtotime($last_month_info['submit_date']))+1;
            $next_next_month =  date('m',strtotime($last_month_info['submit_date']))+2;

            $where = " MONTH(new_report_incubate_master.submit_date)='".$prev_month."' ";
            
            $reportFundingStatusDetails = $reportTargetAchievementDetails = $reportTeamDetails = $prevInformationDetails = $prev_prevInformationDetails = $prev_prev_prev_InformationDetails = $currentInformationDetails = $next_monthInformationDetails = $next_next_monthInformationDetails = array();
            
            if(isset($last_month_info) && count($last_month_info)>0){
                $reportTargetAchievementDetails = NewReportTargetAchievement::where('master_id','=',$master_id)->first();
                $reportFundingStatusDetails = NewReportFundingStatus::where('master_id','=',$master_id)->first();
                $reportTeamDetails = NewReportTeamDetails::where('master_id','=',$master_id)->first();            

                $prevInformationDetails = NewReportFinancialInformation::
                        where('startup_id', '=', $startup_id)
                        ->where('master_id','=',$master_id)
                        ->whereRaw(" MONTH(new_report_financial_information.submit_date)='".$prev_month."' ")
                        ->first();
                $prev_prevInformationDetails = NewReportFinancialInformation::
                        where('startup_id', '=', $startup_id)
                        ->where('master_id','=',$master_id)
                        ->whereRaw(" MONTH(new_report_financial_information.submit_date)='".$prev_prev_month."' ")
                        ->first();
                $prev_prev_prev_InformationDetails = NewReportFinancialInformation::
                        where('startup_id', '=', $startup_id)
                        ->where('master_id','=',$master_id)
                        ->whereRaw(" MONTH(new_report_financial_information.submit_date)='".$prev_prev_prev_month."' ")
                        ->first();

                $currentInformationDetails = NewReportFinancialInformationPlan::
                        where('startup_id', '=', $startup_id)
                        ->where('master_id','=',$master_id)
                        ->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".$currnt_month."' ")
                        ->orderBy('new_report_financial_information_plan.id', 'desc')
                        ->first();
                $next_monthInformationDetails = NewReportFinancialInformationPlan::
                        where('startup_id', '=', $startup_id)
                        ->where('master_id','=',$master_id)
                        ->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".$next_month."' ")
                        ->orderBy('new_report_financial_information_plan.id', 'desc')
                        ->first();
                $next_next_monthInformationDetails = NewReportFinancialInformationPlan::
                        where('startup_id', '=', $startup_id)
                        ->where('master_id','=',$master_id)
                        ->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".$next_next_month."' ")
                        ->orderBy('new_report_financial_information_plan.id', 'desc')
                        ->first();

            }

            $pdf_name = $startupDetails['name'].'-'.date('M y',strtotime($last_month_info['submit_date'])).'.pdf';

            $data['startupDetails'] = $startupDetails;
            $data['last_month_info'] = $last_month_info;
            $data['startupInfoDetails'] = $startupInfoDetails;
            $data['reportFundingStatusDetails'] = $reportFundingStatusDetails;
            $data['reportTargetAchievementDetails'] = $reportTargetAchievementDetails;
            $data['reportTeamDetails'] = $reportTeamDetails;

            $data['prevInformationDetails'] = $prevInformationDetails;
            $data['prev_prevInformationDetails'] = $prev_prevInformationDetails;
            $data['prev_prev_prev_InformationDetails'] = $prev_prev_prev_InformationDetails;

            $data['currentInformationDetails'] = $currentInformationDetails;
            $data['next_monthInformationDetails'] = $next_monthInformationDetails;
            $data['next_next_monthInformationDetails'] = $next_next_monthInformationDetails;
            $data['pdf_name'] = $pdf_name;
            $html = View::make('new_report.generatePDF')->with('data',$data);

            //$html = 'test';
            ob_end_clean();
            
//            $mpdf->SetDisplayMode('fullpage');
//            $mpdf->list_indent_first_level = 0;
            $mpdf->WriteHTML($html);
            $mpdf->Output($pdf_name, 'D');
            echo $pdf_name;
        }else{
           return Redirect::route('home')->with('message','Please Login');
       }
    }
    
    public function detailsIncubateReportList(Request $request){
       //$searchYear =  Input::get('year'); 
       //$searchMonth = Input::get('month');
	   
	   /*$data = array();
        $data['GparentMenu'] = 'rpt_management';
        $data['parentMenu'] = 'rpt_Management';
        $data['childMenu'] = 'rpt_List';*/
	   
	   $searchYear = trim($request->input('year'));
	   $searchMonth = trim($request->input('month'));

       $where = "1=1";
            if(!empty ($searchMonth)){
                if($searchMonth=='last3'){
                    $currentMonthNo = date('m');
                    $prevMonthNo = $currentMonthNo-2;
                    $where.= " AND MONTH(new_report_incubate_master.submit_date) BETWEEN '".$prevMonthNo."' AND '".$currentMonthNo."'  ";
                }elseif($searchMonth=='last6'){
                    $currentMonthNo = date('m');
                    $prevMonthNo = $currentMonthNo-5;
                    $where.= " AND MONTH(new_report_incubate_master.submit_date) BETWEEN '".$prevMonthNo."' AND '".$currentMonthNo."'  ";
                }else{
                    $where.= " AND MONTH(new_report_incubate_master.submit_date)='".$searchMonth."' ";
                }
            }
            if(!empty ($searchYear)){
                $where.= " AND YEAR(new_report_incubate_master.submit_date)='".$searchYear."' ";
            }
            /*$myReports = NewReportIncubateMaster::
                    join('users as users','users.id','=','new_report_incubate_master.startup_id')
                    ->select('new_report_incubate_master.submit_date','new_report_incubate_master.id','users.name','new_report_incubate_master.is_draft','users.individual_name')
                    //->where('startup_id','=',$id)
                    ->whereRaw($where)
                    ->orderBy('submit_date')->get();*/
            
            $myReports = DB::select("SELECT submit_date AS submit_date FROM new_report_incubate_master WHERE ".$where."  GROUP BY MONTH(submit_date), YEAR(submit_date) ORDER BY submit_date desc");
//            dd(DB::getQueryLog()); 
             $data = [
                 'searchYear'=>$searchYear,
                 'searchMonth'=>$searchMonth,
                 'myReports'=>$myReports,
				 
				 'GparentMenu'=>'rpt_management',
				 'parentMenu'=>'rpt_Management',
				 'childMenu'=>'rpt_List',
             ];

        //return View::make('admin.report.details_report')->with($data);		
		return view('dashboard.pm.report.details_report')->with($data);
		
    }
    
    public function download_incubate_report_BKP($month, $year){
        //echo $month.$year;
        require_once Config::get('app.base_url')."common/helpers.php";
        $data = array();
        $reportFinancialInformationDetails = 
                DB::select("select distinct(users.id),users.name as company_name,users.individual_name as founder_name,users.user_email as email_id,users.phone as mobile_no,
                    entrepreneurs.incorporation_date as year_of_induction,entrepreneurs.website as website,entrepreneurs.summary_start_up,
                    entrepreneurs.qualification as qualification,entrepreneurs.share_holding_percentage as iimcip_share_holding_percentage,
                    if((entrepreneurs.dob!='NULL'), year(now()) - year(entrepreneurs.dob),'') as age, entrepreneurs.type as type,
                    
                    

                    '' as sector, '' as disbursement_date, '' as share_status_transfer, '' as transfered_share_for_incubation_support, '' as additional_shares, '' as iimcip_comments, user2.individual_name as mentor_name,
                    
                    (select sum(new_report_financial_information.revenue)  from new_report_financial_information join new_report_incubate_master on new_report_financial_information.master_id=new_report_incubate_master.id WHERE new_report_incubate_master.startup_id=users.id AND new_report_financial_information.master_id=new_report_incubate_master.id AND new_report_financial_information.submit_date BETWEEN '".$this->checkFinancialStart(date('Y')-1)."' AND '".$this->checkFinancialEnd(date('Y')-1)."') as total_revenue_2017,
                        
                    '' as total_projected_2018,'' as ytd,'' as avg_burn_pm, '' as technology,
                    
                    (select new_report_team_details.total_employee from new_report_team_details join new_report_incubate_master on new_report_team_details.master_id=new_report_incubate_master.id WHERE new_report_incubate_master.startup_id=users.id AND new_report_team_details.master_id=new_report_incubate_master.id AND new_report_incubate_master.submit_date BETWEEN '".$this->checkFinancialStart(date('Y')-1)."' AND '".$this->checkFinancialEnd(date('Y')-1)."' order by new_report_team_details.id desc limit 1) as employee_generated_2017,

                    (select new_report_team_details.total_employee from new_report_team_details join new_report_incubate_master on new_report_team_details.master_id=new_report_incubate_master.id WHERE new_report_incubate_master.startup_id=users.id AND new_report_team_details.master_id=new_report_incubate_master.id AND new_report_incubate_master.submit_date BETWEEN '".$this->checkFinancialStart(date('Y'))."' AND '".$this->checkFinancialEnd(date('Y'))."' order by new_report_team_details.id desc limit 1) as employee_generated_2018,
                    '' as external_fund,'' as seed_funding_from_iimcip
                    
                    
                    from `users` 
                        left join `new_report_incubate_master` on `new_report_incubate_master`.`startup_id` = `users`.`id`  
                        right join `entrepreneurs` on `entrepreneurs`.`id` = `users`.`id` 
                        left join `start_up_investor_rel` on `start_up_investor_rel`.`start_up_id` = `users`.`id` 
                        left join `users` as user2 on `start_up_investor_rel`.`investor_id` = `user2`.`id`
                    where 
                            `users`.`user_type` = 'e' 
                            and `users`.`status` = 'Active' 
                    ORDER BY users.id");
        //echo '<pre>';        
        //dd(DB::getQueryLog()); 
        //echo count($reportFinancialInformationDetails);
        //echo '<pre>';print_r($reportFinancialInformationDetails); die();
        $data = [
            'month'=>$month,
            'year'=>$year,
            'excelData'=>$reportFinancialInformationDetails
        ];
        return View::make('admin.report.incubate_details_report')->with($data);
    }

    public function incubateDetails(){
        require_once Config::get('app.base_url')."common/helpers.php";
        //echo '<pre>';
        //$calVal = $this->checkFinancialYear(date('Y'),date('m'));
        //$rangeVal = $this->checkFinancialStart(date('Y')); //checkFinancialEnd
        //print_r($calVal); 
        //print_r($rangeVal);
        //die();
        $data = array();
        /*$user_type = Session::get('user_type');
        echo $user_type;*/
        $where = "1=1 ";
        //$where .= " AND YEAR(report_financial_information.month_year) = YEAR(CURRENT_DATE()) ";
        $reportFinancialInformationDetails = 
                DB::select("select distinct(users.id),users.name as company_name,users.individual_name as founder_name,entrepreneurs.incorporation_date as year_of_induction,entrepreneurs.website as website,users.user_email as email_id,users.phone as mobile_no,entrepreneurs.summary_start_up,
                    if((entrepreneurs.dob!='NULL'), year(now()) - year(entrepreneurs.dob),'') as age,
                    
                    entrepreneurs.qualification as qualification, '' as sector, '' as ytd, '' as technology, '' as external_fund,'' as seed_funding_from_iimcip, '' as disbursement_date, entrepreneurs.share_holding_percentage as iimcip_share_holding_percentage, '' as share_status_transfer, '' as transfered_share_for_incubation_support, '' as additional_shares, '' as iimcip_comments,

(select sum(new_report_incubate_master.cash_in_hand)  from new_report_incubate_master where new_report_incubate_master.startup_id=users.id ) as total_cash_in_hand,

(select sum(new_report_financial_information.revenue)  from new_report_financial_information join new_report_incubate_master on new_report_financial_information.master_id=new_report_incubate_master.id WHERE new_report_incubate_master.startup_id=users.id AND new_report_financial_information.master_id=new_report_incubate_master.id AND new_report_financial_information.submit_date BETWEEN '".$this->checkFinancialStart(date('Y')-1)."' AND '".$this->checkFinancialEnd(date('Y')-1)."') as total_revenue_2017,

(select sum(new_report_financial_information.revenue)  from new_report_financial_information join new_report_incubate_master on new_report_financial_information.master_id=new_report_incubate_master.id WHERE new_report_incubate_master.startup_id=users.id AND new_report_financial_information.master_id=new_report_incubate_master.id AND new_report_financial_information.submit_date BETWEEN '".$this->checkFinancialStart(date('Y'))."' AND '".$this->checkFinancialEnd(date('Y'))."') as total_revenue_2018,

(select sum(new_report_financial_information_plan.revenue)  from new_report_financial_information_plan join new_report_incubate_master on new_report_financial_information_plan.master_id=new_report_incubate_master.id WHERE new_report_incubate_master.startup_id=users.id AND new_report_financial_information_plan.master_id=new_report_incubate_master.id AND new_report_financial_information_plan.submit_date BETWEEN '".$this->checkFinancialStart(date('Y'))."' AND '".$this->checkFinancialEnd(date('Y'))."') as total_projected_2018,

(select avg(new_report_financial_information.burn_rate)  from new_report_financial_information join new_report_incubate_master on new_report_financial_information.master_id=new_report_incubate_master.id WHERE new_report_incubate_master.startup_id=users.id AND new_report_financial_information.master_id=new_report_incubate_master.id ) as avg_burn_pm,

entrepreneurs.type as type,

(select new_report_team_details.total_employee from new_report_team_details join new_report_incubate_master on new_report_team_details.master_id=new_report_incubate_master.id WHERE new_report_incubate_master.startup_id=users.id AND new_report_team_details.master_id=new_report_incubate_master.id AND new_report_incubate_master.submit_date BETWEEN '".$this->checkFinancialStart(date('Y')-1)."' AND '".$this->checkFinancialEnd(date('Y')-1)."' order by new_report_team_details.id desc limit 1) as employee_generated_2017,

(select new_report_team_details.total_employee from new_report_team_details join new_report_incubate_master on new_report_team_details.master_id=new_report_incubate_master.id WHERE new_report_incubate_master.startup_id=users.id AND new_report_team_details.master_id=new_report_incubate_master.id AND new_report_incubate_master.submit_date BETWEEN '".$this->checkFinancialStart(date('Y'))."' AND '".$this->checkFinancialEnd(date('Y'))."' order by new_report_team_details.id desc limit 1) as employee_generated_2018,

user2.individual_name as mentor_name

from `users` 
	left join `new_report_incubate_master` on `new_report_incubate_master`.`startup_id` = `users`.`id`  
	join `entrepreneurs` on `entrepreneurs`.`id` = `users`.`id` 
    left join `start_up_investor_rel` on `start_up_investor_rel`.`start_up_id` = `users`.`id` 
    left join `users` as user2 on `start_up_investor_rel`.`investor_id` = `user2`.`id`
where 
	`users`.`user_type` = 'e' 
	and `users`.`status` = 'Active' 
ORDER BY users.id");
        //echo '<pre>';        
        //dd(DB::getQueryLog()); 
        //echo count($reportFinancialInformationDetails);
        //echo '<pre>';print_r($reportFinancialInformationDetails); die();
        $data = [
            'excelData'=>$reportFinancialInformationDetails
        ];
        return View::make('admin.report.incubate_details')->with($data);
    }
    
    public function checkFinancialYear($current_year,$current_month){
        $prev_year = $current_year - 1;
        $next_year = $current_year + 1;
        if($current_month=='1' || $current_month=='2' || $current_month=='3'){
            $financial_year = $prev_year.'-'.date('y',strtotime($current_year.'-01-01'));
            $month_duration_start = $prev_year.'-04-01';
            $month_duration_end = $current_year.'-03-31';
        }elseif($current_month=='4' || $current_month=='5' || $current_month=='6' || $current_month=='7' || $current_month=='8' || $current_month=='9' || $current_month=='10' || $current_month=='11' || $current_month=='12'){
            $financial_year = $current_year.'-'.date('y',strtotime($next_year.'-01-01'));
            $month_duration_start = $current_year.'-04-01';
            $month_duration_end = $next_year.'-03-31';
        }
        return $financial_year;
        /*return $data = [
            'financial_year'=>$financial_year,
            'month_duration_start'=>$month_duration_start,
            'month_duration_end'=>$month_duration_end
        ];*/
    }
    
    public function checkFinancialStart($year){
        $next_year = $year + 1;
        $start = $year.'-04-01';
        $end = $next_year.'-03-31';
        return $start;
    }
    
    public function checkFinancialEnd($year){
        $next_year = $year + 1;
        $start = $year.'-04-01';
        $end = $next_year.'-03-31';
        return $end;
    }
    
    public function download_incubate_report($month, $year){
		//echo 1;die;
        //require_once Config::get('app.base_url')."common/helpers.php";
		require_once $_SERVER['DOCUMENT_ROOT']."/iimcip-network/common/helpers.php";
		
        $data = array();
		
		
        $excelData = 
                DB::select("select distinct(users.id),users.member_company as company_name,users.contact_name as founder_name,users.email_id as email_id,users.mobile as mobile_no,
                    entrepreneurs.incorporation_date as year_of_induction,entrepreneurs.incubation_start_date as incubation_start_date,entrepreneurs.website as website,entrepreneurs.summary_start_up,
                    entrepreneurs.qualification as qualification,entrepreneurs.share_holding_percentage as iimcip_share_holding_percentage,
                    if((entrepreneurs.dob!='NULL' && entrepreneurs.dob!='0000-00-00'), year(now()) - year(entrepreneurs.dob),'') as age, entrepreneurs.type as type,
                    
                    

                    '' as sector, '' as disbursement_date, '' as share_status_transfer, '' as transfered_share_for_incubation_support, '' as additional_shares, '' as iimcip_comments, user2.contact_name as mentor_name,
                    
                    (select sum(new_report_financial_information.revenue)  from new_report_financial_information join new_report_incubate_master on new_report_financial_information.master_id=new_report_incubate_master.id WHERE new_report_financial_information.startup_id=users.id AND new_report_financial_information.master_id=new_report_incubate_master.id AND new_report_financial_information.submit_date BETWEEN '".checkFinancialStart(date('Y')-1)."' AND '".checkFinancialEnd(date('Y')-1)."') as total_revenue_2017,
                        
                    '' as total_projected_2018,
					(select sum(new_report_financial_information.revenue)  from new_report_financial_information join new_report_incubate_master on new_report_financial_information.master_id=new_report_incubate_master.id WHERE new_report_financial_information.startup_id=users.id AND new_report_financial_information.master_id=new_report_incubate_master.id AND new_report_financial_information.submit_date BETWEEN '".checkFinancialStart(date('Y'))."' AND '".checkFinancialEnd(date('Y'))."') as ytd,
					
					'' as avg_burn_pm, '' as technology,
                    
                    (select new_report_team_details.total_employee from new_report_team_details join new_report_incubate_master on new_report_team_details.master_id=new_report_incubate_master.id WHERE new_report_incubate_master.startup_id=users.id AND new_report_team_details.master_id=new_report_incubate_master.id AND new_report_incubate_master.submit_date BETWEEN '".$this->checkFinancialStart(date('Y')-1)."' AND '".$this->checkFinancialEnd(date('Y')-1)."' order by new_report_team_details.id desc limit 1) as employee_generated_2017,

                    (select new_report_team_details.total_employee from new_report_team_details join new_report_incubate_master on new_report_team_details.master_id=new_report_incubate_master.id WHERE new_report_incubate_master.startup_id=users.id AND new_report_team_details.master_id=new_report_incubate_master.id AND new_report_incubate_master.submit_date BETWEEN '".$this->checkFinancialStart(date('Y'))."' AND '".$this->checkFinancialEnd(date('Y'))."' order by new_report_team_details.id desc limit 1) as employee_generated_2018,
                    '' as external_fund,'' as seed_funding_from_iimcip
                    
                    
                    from `users` 
                        left join `new_report_incubate_master` on `new_report_incubate_master`.`startup_id` = `users`.`id`  
                        right join `entrepreneurs` on `entrepreneurs`.`id` = `users`.`id` 
                        left join `start_up_investor_rel` on `start_up_investor_rel`.`start_up_id` = `users`.`id` 
                        left join `users` as user2 on `start_up_investor_rel`.`investor_id` = `user2`.`id`
                    where 
                            `users`.`user_type` = 'e' 
                            and `users`.`status` = 'Active' 
                    ORDER BY users.id");
        
        
		
        
        $current_year = date('Y');
        $prev_year = $current_year - 1;
        $next_year = $current_year + 1;
            
        //require_once Config::get('app.base_url')."PHPExcel/Classes/PHPExcel.php";
		require_once $_SERVER['DOCUMENT_ROOT']."/iimcip-network/PHPExcel/Classes/PHPExcel.php";
		
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties();
        
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', "Sr No");
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', "Name of the incubatee company");
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', "Name of the main founder");
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', "Age");
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', "Qualification");
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', "Month and year of incubation in the TBI");
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', "Website");
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', "Email Id");
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', "Mobile No");
        $objPHPExcel->getActiveSheet()->SetCellValue('J1', "Industry Sector");
        $objPHPExcel->getActiveSheet()->SetCellValue('K1', "Company Profile");
        $objPHPExcel->getActiveSheet()->SetCellValue('L1', "Turnover ".financial_year(date('Y')-1)." (in Lacks)");
        $objPHPExcel->getActiveSheet()->SetCellValue('M1', "Projected ".financial_year(date('Y'))." (in Lacks)");
        $objPHPExcel->getActiveSheet()->SetCellValue('N1', "YTD (in Lacks)");
        $objPHPExcel->getActiveSheet()->SetCellValue('O1', "Avg Burn Rate pm(in lakhs)");
        $objPHPExcel->getActiveSheet()->SetCellValue('P1', "Type");
        $objPHPExcel->getActiveSheet()->SetCellValue('Q1', "Technology");
        //$objPHPExcel->getActiveSheet()->SetCellValue('R1', "Employee Generated previous Year");
        //$objPHPExcel->getActiveSheet()->SetCellValue('S1', "No of Employees");
        $objPHPExcel->getActiveSheet()->SetCellValue('R1', "External Funds Received(in lakhs)");
        $objPHPExcel->getActiveSheet()->SetCellValue('S1', "Seed Funding Received From IIMCIP(in lakhs)");
        $objPHPExcel->getActiveSheet()->SetCellValue('T1', "Date of disbursement");
        $objPHPExcel->getActiveSheet()->SetCellValue('U1', "IIMCIP Share holding percentage");
        $objPHPExcel->getActiveSheet()->SetCellValue('V1', "Share Transfer Status");
        $objPHPExcel->getActiveSheet()->SetCellValue('W1', "No of Shares transferred for incubation support");
        $objPHPExcel->getActiveSheet()->SetCellValue('X1', "Additional shares");
        $objPHPExcel->getActiveSheet()->SetCellValue('Y1', "Mentor Name");
        $objPHPExcel->getActiveSheet()->SetCellValue('Z1', "IIMCIP comments");
        $objPHPExcel->getActiveSheet()->SetCellValue('AA1', "status");
        $objPHPExcel->getActiveSheet()->SetCellValue('AB1', "Investible status");
        $objPHPExcel->getActiveSheet()->SetCellValue('AC1', "Fund type");
        $val=array(
                array('name'=>'Rohit', 'address'=>'Kolkata'),
                array('name'=>'Sumit', 'address'=>'Bombay')
        );

        $i=2;
        foreach($excelData as $row){
            $row = (array)$row;
            
            $allRelatedData = getMasterData($row['id'],$month,$year);
            $master_data  = (isset($allRelatedData['master_data']) && count($allRelatedData['master_data'])>0)? $allRelatedData['master_data'][0]:"";
            $type  = (isset($master_data->type) && !empty($master_data->type))? $master_data->type:"";
            $technology  = (isset($master_data->technology) && !empty($master_data->technology))? $master_data->technology:"";
            $dateof_disbursement  = (isset($master_data->dateof_disbursement) && !empty($master_data->dateof_disbursement))? date('Y-m-d',strtotime($master_data->dateof_disbursement)):"";
            $share_transfer_status  = (isset($master_data->share_transfer_status) && !empty($master_data->share_transfer_status))? $master_data->share_transfer_status:"";
            $share_transfer_count  = (isset($master_data->share_transfer_count) && !empty($master_data->share_transfer_count))? $master_data->share_transfer_count:"";
            $additional_share  = (isset($master_data->additional_share) && !empty($master_data->additional_share))? $master_data->additional_share:"";
            $incubate_status  = (isset($master_data->incubate_status) && !empty($master_data->incubate_status))? $master_data->incubate_status:"FFFFFF";
            
            $investible_status  = (isset($master_data->investible_status) && !empty($master_data->investible_status))? $master_data->investible_status:"FFFFFF";
            //die($investible_status);
            $fund_type  = (isset($master_data->fund_type) && !empty($master_data->fund_type))? $master_data->fund_type:"";



            $burnRate  = (isset($allRelatedData['burnRate']) && count($allRelatedData['burnRate'])>0)? $allRelatedData['burnRate'][0]->avg_burn_rate:"";
            $achievementDetails  = (isset($allRelatedData['achievement']) && count($allRelatedData['achievement'])>0)? $allRelatedData['achievement'][0]->sales_achived_till_date:"";
            $external_fundDetails  = (isset($allRelatedData['funding_status']) && count($allRelatedData['funding_status'])>0)? $allRelatedData['funding_status'][0]->remarks_fund_form_external:"";
            $seed_funding_from_iimcipDetails  = (isset($allRelatedData['funding_status']) && count($allRelatedData['funding_status'])>0)? $allRelatedData['funding_status'][0]->remarks_iimcip_investment:"";
            $projected  = (isset($allRelatedData['projected']) && count($allRelatedData['projected'])>0)? $allRelatedData['projected']->sales_annual_target:"";
            $total_revenue_2017=(isset($allRelatedData['turnover']) && count($allRelatedData['turnover'])>0)? $allRelatedData['turnover']->sales_sales_revenue:"";
            
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$i, $i-1);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, cleanData($row['company_name']));
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$i, cleanData($row['founder_name']));
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$i, cleanData($row['age']));
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$i, cleanData($row['qualification']));
			
			$incubation_start_date=($row['incubation_start_date']!='0000-00-00')?$row['incubation_start_date']:'';
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$i, cleanData($incubation_start_date));
			
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$i, cleanData($row['website']));
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$i, cleanData($row['email_id']));
            $objPHPExcel->getActiveSheet()->SetCellValue('I'.$i, cleanData($row['mobile_no']));
            $objPHPExcel->getActiveSheet()->SetCellValue('J'.$i, cleanData($row['sector']));
            $objPHPExcel->getActiveSheet()->SetCellValue('K'.$i, cleanData($row['summary_start_up']));
           // $objPHPExcel->getActiveSheet()->SetCellValue('L'.$i, cleanData($row['total_revenue_2017'])); // it was working correct but for requirement of iimc we have chnaged it
		   $objPHPExcel->getActiveSheet()->SetCellValue('L'.$i, cleanData($total_revenue_2017));
		   
            $objPHPExcel->getActiveSheet()->SetCellValue('M'.$i, cleanData($projected));
            //$objPHPExcel->getActiveSheet()->SetCellValue('N'.$i, cleanData($achievementDetails));
			$objPHPExcel->getActiveSheet()->SetCellValue('N'.$i, cleanData($row['ytd']));
            $objPHPExcel->getActiveSheet()->SetCellValue('O'.$i, cleanData($burnRate));
            $objPHPExcel->getActiveSheet()->SetCellValue('P'.$i, cleanData($type));
            $objPHPExcel->getActiveSheet()->SetCellValue('Q'.$i, cleanData($technology));
            //$objPHPExcel->getActiveSheet()->SetCellValue('R'.$i, cleanData($row['employee_generated_2017']));
            //$objPHPExcel->getActiveSheet()->SetCellValue('S'.$i, cleanData($row['employee_generated_2018']));
            $objPHPExcel->getActiveSheet()->SetCellValue('R'.$i, cleanData($external_fundDetails));
            $objPHPExcel->getActiveSheet()->SetCellValue('S'.$i, cleanData($seed_funding_from_iimcipDetails));
            $objPHPExcel->getActiveSheet()->SetCellValue('T'.$i, cleanData($dateof_disbursement));
            $objPHPExcel->getActiveSheet()->SetCellValue('U'.$i, cleanData($row['iimcip_share_holding_percentage']));
            $objPHPExcel->getActiveSheet()->SetCellValue('V'.$i, cleanData($share_transfer_status));
            $objPHPExcel->getActiveSheet()->SetCellValue('W'.$i, cleanData($share_transfer_count));
            $objPHPExcel->getActiveSheet()->SetCellValue('X'.$i, cleanData($additional_share));
            $objPHPExcel->getActiveSheet()->SetCellValue('Y'.$i, cleanData($row['mentor_name']));            
            $objPHPExcel->getActiveSheet()->SetCellValue('Z'.$i, cleanData($row['iimcip_comments']));            
            $objPHPExcel->getActiveSheet()
                    ->getStyle('AA'.$i)
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB($incubate_status);
            $objPHPExcel->getActiveSheet()
                    ->getStyle('AB'.$i)
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB($investible_status);
            $objPHPExcel->getActiveSheet()->SetCellValue('AC'.$i, cleanData($fund_type)); 

        $i++;
        }
        
            $filename = "IncubateDetailsReport" . date('Y-m-d') . ".xls";
            $writer = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
    }
    
    public function incubate_report_monthwise($searchMonth, $searchYear){
        //$searchYear =  Input::get('year'); 
       //$searchMonth = Input::get('month');
       $where = "1=1";
            if(!empty ($searchMonth)){
                if($searchMonth=='last3'){
                    $currentMonthNo = date('m');
                    $prevMonthNo = $currentMonthNo-2;
                    $where.= " AND MONTH(new_report_incubate_master.submit_date) BETWEEN '".$prevMonthNo."' AND '".$currentMonthNo."'  ";
                }elseif($searchMonth=='last6'){
                    $currentMonthNo = date('m');
                    $prevMonthNo = $currentMonthNo-5;
                    $where.= " AND MONTH(new_report_incubate_master.submit_date) BETWEEN '".$prevMonthNo."' AND '".$currentMonthNo."'  ";
                }else{
                    $where.= " AND MONTH(new_report_incubate_master.submit_date)='".$searchMonth."' ";
                }
            }
            if(!empty ($searchYear)){
                $where.= " AND YEAR(new_report_incubate_master.submit_date)='".$searchYear."' ";
            }
            $myReports = NewReportIncubateMaster::
                    join('users as users','users.id','=','new_report_incubate_master.startup_id')
                    ->select('new_report_incubate_master.submit_date','new_report_incubate_master.id','users.member_company','new_report_incubate_master.is_draft','users.contact_name')
                    //->where('startup_id','=',$id)
                    ->whereRaw($where)->orderBy('submit_date')->paginate(10);
            $current_month_info = NewReportIncubateMaster::
                    //where('new_report_incubate_master.startup_id', '=', $id)
                    whereRaw(" MONTH(new_report_incubate_master.submit_date)='".date('m')."' ")->first();
            
             $data = [
                 'heading'=>'Report for '.date("M'y",strtotime($searchYear.'-'.$searchMonth.'-01')),
                 'searchYear'=>$searchYear,
                 'searchMonth'=>$searchMonth,
                 'current_month_info'=>$current_month_info,
                 'myReports'=>$myReports,
				 
				 'GparentMenu'=>'rpt_management',
				 'parentMenu'=>'rpt_Management',
				 'childMenu'=>'rpt_List',
             ];

        //return View::make('admin.report.monthwise_report')->with($data);
		return view('dashboard.pm.report.monthwise_report')->with($data);
		
		
		
    }

    //new incubate report  ac
    public function incubate_report_list(Request $request) {
        if(\Auth::check()){
            //$searchYear =  Input::get('year');
            //$searchMonth = Input::get('month'); 
			
			$searchYear = trim($request->input('year'));
			$searchMonth = trim($request->input('month'));
			
            $id = Auth::user()->id;
            $where = "1=1";
            if(!empty ($searchMonth)){
                if($searchMonth=='last3'){
                    $currentMonthNo = date('m');
                    $prevMonthNo = $currentMonthNo-2;
                    $where.= " AND MONTH(new_report_incubate_master.submit_date) BETWEEN '".$prevMonthNo."' AND '".$currentMonthNo."'  ";
                }elseif($searchMonth=='last6'){
                    $currentMonthNo = date('m');
                    $prevMonthNo = $currentMonthNo-5;
                    $where.= " AND MONTH(new_report_incubate_master.submit_date) BETWEEN '".$prevMonthNo."' AND '".$currentMonthNo."'  ";
                }else{
                    $where.= " AND MONTH(new_report_incubate_master.submit_date)='".$searchMonth."' ";
                }
            }
            if(!empty ($searchYear)){
                $where.= " AND YEAR(new_report_incubate_master.submit_date)='".$searchYear."' ";
            }
            $myReports = NewReportIncubateMaster::
            join('users as users','users.id','=','new_report_incubate_master.startup_id')
                ->select('new_report_incubate_master.submit_date','new_report_incubate_master.id','users.member_company','new_report_incubate_master.is_draft','users.contact_name')
                ->where('startup_id','=',$id)->whereRaw($where)->orderBy('submit_date')->paginate(10);
            $current_month_info = NewReportIncubateMaster::where('new_report_incubate_master.startup_id', '=', $id)->whereRaw(" MONTH(new_report_incubate_master.submit_date)='".date('m')."' and YEAR(new_report_incubate_master.submit_date)='".date('Y')."' ")->get();  //->first();
			$data = [
                'start_up_id'=>$id,
                'searchYear'=>$searchYear,
                'searchMonth'=>$searchMonth,
                'current_month_info'=>$current_month_info,
                'myReports'=>$myReports
            ];

            //return View::make('new_report.demo.list_report')->with($data);			
			return view('frontend.new_report.demo.list_report')->with($data);
        }else{
            return Redirect::route('home')->with('message','Please Login');
        }
    }
    
	public function incubate_report_add(){
        //require_once Config::get('app.base_url')."common/helpers.php";
		require_once $_SERVER['DOCUMENT_ROOT']."/iimcip-network/common/helpers.php";
		
        if(\Auth::check()){
            $id = Auth::user()->id;
            $data = array();
			
            /*$startupDetails = Users::
            leftJoin('start_up_investor_rel','start_up_investor_rel.start_up_id','=','users.id')
                ->leftJoin('users as user2','user2.id','=','start_up_investor_rel.investor_id')*/
			$startupDetails = Users::	
			//leftJoin('member_mentor_rel','member_mentor_rel.member_id','=','users.id')
                //->leftJoin('users as user2','user2.id','=','member_mentor_rel.mentor_id')					
                //->select('users.*','user2.individual_name as investor_name')//ac
				//->select('users.*','user2.contact_name as investor_name')
				select('users.*')
                ->where('users.id', '=', $id)->where('users.status', '=', '1')->first();

            $startupInfoDetails = Entrepreneur::where('id', '=', $id)->where('status', '=', 'Active')->first();
			//dd($startupDetails);
			/*if(is_null($startupDetails)){
				$join_date = '';
			}else{*/
				$join_date = $startupDetails->created_at;
			//}
            
            
            $prev_month = date("Y-m-2", strtotime(date('Y-m-2')." -1 Month"));
            $prev_prev_month = date("Y-m-2", strtotime(date('Y-m-2')." -2 Month"));
            $prev_prev_prev_month = date("Y-m-2", strtotime(date('Y-m-2')." -3 Month"));
            $currnt_month = date("Y-m-2", strtotime(date('Y-m-2')." +0 Month"));
            $next_month = date("Y-m-2", strtotime(date('Y-m-2')." +1 Month"));
            $next_next_month = date("Y-m-2", strtotime(date('Y-m-2')." +2 Month"));
            //echo $next_month; echo '<br>';
            //echo $next_next_month; die();

            $where = " MONTH(new_report_incubate_master.submit_date)='".date('m',strtotime($prev_month))."' and YEAR(new_report_incubate_master.submit_date)='".date('Y',strtotime($prev_month))."'";
            $last_month_info = NewReportIncubateMaster::where('startup_id', '=', $id)->whereRaw($where)->first();
            $reportFundingStatusDetails = $reportTargetAchievementDetails = $reportTeamDetails = array();
            if(isset($last_month_info) && count($last_month_info)>0){
                $master_id = $last_month_info['id'];
                $reportTargetAchievementDetails = NewReportTargetAchievement::where('master_id','=',$master_id)->first();
                $reportFundingStatusDetails = NewReportFundingStatus::where('master_id','=',$master_id)->first();
                $reportTeamDetails = NewReportTeamDetails::where('master_id','=',$master_id)->first();
            }

            $prevInformationDetails = NewReportFinancialInformation::where('startup_id', '=', $id)
                ->whereRaw(" new_report_financial_information.submit_date='".$prev_month."' ")->first();
            $prev_prevInformationDetails = NewReportFinancialInformation::where('startup_id', '=', $id)
                ->whereRaw(" new_report_financial_information.submit_date='".$prev_prev_month."' ")->first();
            $prev_prev_prev_InformationDetails = NewReportFinancialInformation::where('startup_id', '=', $id)
                ->whereRaw(" new_report_financial_information.submit_date='".$prev_prev_prev_month."' ")->first();


            $currentInformationDetails = NewReportFinancialInformationPlan::where('startup_id', '=', $id)
                ->whereRaw(" new_report_financial_information_plan.submit_date='".$currnt_month."' ")
                //->whereRaw(" YEAR(new_report_financial_information_plan.submit_date)='".checkFinancialStart(date('Y'))."' ")
                ->first();
            $next_monthInformationDetails = NewReportFinancialInformationPlan::where('startup_id', '=', $id)
                ->whereRaw(" new_report_financial_information_plan.submit_date='".$next_month."' ")
                //->whereRaw(" YEAR(new_report_financial_information_plan.submit_date)='".checkFinancialStart(date('Y'))."' ")
                ->first();
            $next_next_monthInformationDetails = NewReportFinancialInformationPlan::where('startup_id', '=', $id)
                ->whereRaw(" new_report_financial_information_plan.submit_date='".$next_next_month."' ")
                //->whereRaw(" YEAR(new_report_financial_information_plan.submit_date)='".checkFinancialStart(date('Y'))."' ")
                ->first();



            $data['join_date'] = $join_date;
            $data['startupDetails'] = $startupDetails;
            $data['last_month_info'] = $last_month_info;
            $data['startupInfoDetails'] = $startupInfoDetails;
            $data['reportFundingStatusDetails'] = $reportFundingStatusDetails;
            $data['reportTargetAchievementDetails'] = $reportTargetAchievementDetails;
            $data['reportTeamDetails'] = $reportTeamDetails;

            $data['prevInformationDetails'] = $prevInformationDetails;
            $data['prev_prevInformationDetails'] = $prev_prevInformationDetails;
            $data['prev_prev_prev_InformationDetails'] = $prev_prev_prev_InformationDetails;

            $data['currentInformationDetails'] = $currentInformationDetails;
            $data['next_monthInformationDetails'] = $next_monthInformationDetails;
            $data['next_next_monthInformationDetails'] = $next_next_monthInformationDetails;

            //return $returnHTML = View::make('new_report.demo.add_report')->with('data',$data);
			//dd($data);
			return view('frontend.new_report.demo.add_report')->with('data',$data);
        }else{
            return Redirect::route('home')->with('message','Please Login');
        }
    }
    
	public function incubate_report_save(Request $request){
        if(\Auth::check()){
            $startup_id = Auth::user()->id;
			//echo 'AC';die;

            if(!empty($startup_id)){		
								
                /*$challenges = Input::get('challenges');
                $progress_key_activities = Input::get('progress_key_activities');
                $funding_conversation = Input::get('funding_conversation');
                $planning = Input::get('planning');
                $awards_won = Input::get('awards_won');
                $comments = Input::get('comments');
                $support = Input::get('support');
                $cash_in_hand = Input::get('cash_in_hand');
                $is_draft = Input::get('is_draft');*/
				
				$challenges = trim($request->input('challenges'));				
				$progress_key_activities = trim($request->input('progress_key_activities'));
				$funding_conversation = trim($request->input('funding_conversation'));
				$planning = trim($request->input('planning'));
				$awards_won = trim($request->input('awards_won'));
				$comments = trim($request->input('comments'));
				$support = trim($request->input('support'));
				$cash_in_hand = trim($request->input('cash_in_hand'));
				$is_draft = trim($request->input('is_draft'));

                //check already this month report submitted or not
                $current_month_info = NewReportIncubateMaster::where('startup_id', '=', $startup_id)->whereRaw(" MONTH(new_report_incubate_master.submit_date)='".date('m')."' and YEAR(new_report_incubate_master.submit_date)='".date('y')."'")->first();
                //print_r($current_month_info);
				//exit;
				//end
                if(isset($current_month_info) && count($current_month_info)>0){
                    $incubateMaster = NewReportIncubateMaster::find($current_month_info['id']);
                    $incubateMaster->challenges = $challenges;
                    $incubateMaster->progress_key_activities = $progress_key_activities;
                    $incubateMaster->funding_conversation = $funding_conversation;
                    $incubateMaster->planning = $planning;
                    $incubateMaster->awards_won = $awards_won;
                    $incubateMaster->comments = $comments;
                    $incubateMaster->support = $support;
                    $incubateMaster->cash_in_hand = $cash_in_hand;
                    $incubateMaster->submit_date = date('Y-m-d');
                    $incubateMaster->is_draft = $is_draft;
                }else{
                    $incubateMaster = new NewReportIncubateMaster;
                    $incubateMaster->startup_id = $startup_id;
                    $incubateMaster->challenges = $challenges;
                    $incubateMaster->progress_key_activities = $progress_key_activities;
                    $incubateMaster->funding_conversation = $funding_conversation;
                    $incubateMaster->planning = $planning;
                    $incubateMaster->awards_won = $awards_won;
                    $incubateMaster->comments = $comments;
                    $incubateMaster->support = $support;
                    $incubateMaster->cash_in_hand = $cash_in_hand;
                    $incubateMaster->submit_date = date('Y-m-d');
                    $incubateMaster->is_draft = $is_draft;
                    $incubateMaster->created_at = date('Y-m-d H:i:s');
                }
                if($incubateMaster->save()){
                    $master_id = $incubateMaster->id;

                    //NewReportFinancialInformation
                    /*$date_revenue_3 = Input::get('date_revenue_3');
                    $revenue_3 = Input::get('revenue_3');
                    $expense_3 = Input::get('expense_3');
                    $burn_rate_3 = Input::get('burn_rate_3');*/
					
					$date_revenue_3 = trim($request->input('date_revenue_3'));		
					$revenue_3 = trim($request->input('revenue_3'));		
					$expense_3 = trim($request->input('expense_3'));		
					$burn_rate_3 = trim($request->input('burn_rate_3'));		
					
                    if(!empty($revenue_3) && !empty($expense_3)){
                        $financial_report_3 = new NewReportFinancialInformation;
                        $financial_report_3->startup_id = $startup_id;
                        $financial_report_3->master_id = $master_id;
                        $financial_report_3->submit_date = $date_revenue_3;
                        $financial_report_3->revenue = $revenue_3;
                        $financial_report_3->expense = $expense_3;
                        $financial_report_3->burn_rate = $burn_rate_3;
                        $financial_report_3->is_draft = $is_draft;
                        $financial_report_3->month_year	 = date('Y-m-d');
                        $financial_report_3->save();
                    }
                    /*$date_revenue_2 = Input::get('date_revenue_2');
                    $revenue_2 = Input::get('revenue_2');
                    $expense_2 = Input::get('expense_2');
                    $burn_rate_2 = Input::get('burn_rate_2');*/
					
					$date_revenue_2 = trim($request->input('date_revenue_2'));
					$revenue_2 = trim($request->input('revenue_2'));
					$expense_2 = trim($request->input('expense_2'));
					$burn_rate_2 = trim($request->input('burn_rate_2'));
					
                    if(!empty($revenue_2) && !empty($expense_2)){
                        $financial_report_2 = new NewReportFinancialInformation;
                        $financial_report_2->startup_id = $startup_id;
                        $financial_report_2->master_id = $master_id;
                        $financial_report_2->submit_date = $date_revenue_2;//date('Y-m-d');
                        $financial_report_2->revenue = $revenue_2;
                        $financial_report_2->expense = $expense_2;
                        $financial_report_2->burn_rate = $burn_rate_2;
                        $financial_report_2->is_draft = $is_draft;
                        $financial_report_2->month_year	 = date('Y-m-d');
                        $financial_report_2->save();
                    }
                    /*$date_revenue_1 = Input::get('date_revenue_1');
                    $revenue_1 = Input::get('revenue_1');
                    $expense_1 = Input::get('expense_1');
                    $burn_rate_1 = Input::get('burn_rate_1');*/
					
					$date_revenue_1 = trim($request->input('date_revenue_1'));
					$revenue_1 = trim($request->input('revenue_1'));
					$expense_1 = trim($request->input('expense_1'));
					$burn_rate_1 = trim($request->input('burn_rate_1'));
					
                    if(!empty($revenue_1) && !empty($expense_1)){
                        $financial_report_1 = new NewReportFinancialInformation;
                        $financial_report_1->startup_id = $startup_id;
                        $financial_report_1->master_id = $master_id;
                        $financial_report_1->submit_date = $date_revenue_1;//date('Y-m-d');
                        $financial_report_1->revenue = $revenue_1;
                        $financial_report_1->expense = $expense_1;
                        $financial_report_1->burn_rate = $burn_rate_1;
                        $financial_report_1->is_draft = $is_draft;
                        $financial_report_1->month_year	 = date('Y-m-d');
                        $financial_report_1->save();
                    }
                    //end
                    //NewReportFinancialInformationPlan
                    /*$submit_plan_0 = Input::get('date_revenue_plan_0');
                    $revenue_plan_0 = Input::get('revenue_plan_0');
                    $expense_plan_0 = Input::get('expense_plan_0');
                    $burn_rate_plan_0 = Input::get('burn_rate_plan_0');*/
					
					$submit_plan_0 = trim($request->input('date_revenue_plan_0'));
					$revenue_plan_0 = trim($request->input('revenue_plan_0'));
					$expense_plan_0 = trim($request->input('expense_plan_0'));
					$burn_rate_plan_0 = trim($request->input('burn_rate_plan_0'));
					
                    if(!empty($revenue_plan_0) && !empty($expense_plan_0)){
                        $financial_report_plan = new NewReportFinancialInformationPlan;
                        $financial_report_plan->startup_id = $startup_id;
                        $financial_report_plan->master_id = $master_id;
                        $financial_report_plan->submit_date = $submit_plan_0;//date('Y-m-d');
                        $financial_report_plan->revenue = $revenue_plan_0;
                        $financial_report_plan->expense = $expense_plan_0;
                        $financial_report_plan->burn_rate = $burn_rate_plan_0;
                        $financial_report_plan->is_draft = $is_draft;
                        $financial_report_plan->month_year = date('Y-m-d');
                        $financial_report_plan->save();
                    }
                    /*$submit_plan_1 = Input::get('date_revenue_plan_1');
                    $revenue_plan_1 = Input::get('revenue_plan_1');
                    $expense_plan_1 = Input::get('expense_plan_1');
                    $burn_rate_plan_1 = Input::get('burn_rate_plan_1');*/
					
					$submit_plan_1 = trim($request->input('date_revenue_plan_1'));
					$revenue_plan_1 = trim($request->input('revenue_plan_1'));
					$expense_plan_1 = trim($request->input('expense_plan_1'));
					$burn_rate_plan_1 = trim($request->input('burn_rate_plan_1'));
					
                    if(!empty($revenue_plan_1) && !empty($expense_plan_1)){
                        $financial_report_plan_1 = new NewReportFinancialInformationPlan;
                        $financial_report_plan_1->startup_id = $startup_id;
                        $financial_report_plan_1->master_id = $master_id;
                        $financial_report_plan_1->submit_date = $submit_plan_1;
                        $financial_report_plan_1->revenue = $revenue_plan_1;
                        $financial_report_plan_1->expense = $expense_plan_1;
                        $financial_report_plan_1->burn_rate = $burn_rate_plan_1;
                        $financial_report_plan_1->is_draft = $is_draft;
                        $financial_report_plan_1->month_year = date('Y-m-d');
                        $financial_report_plan_1->save();
                    }
                    /*$submit_plan_2 = Input::get('date_revenue_plan_2');
                    $revenue_plan_2 = Input::get('revenue_plan_2');
                    $expense_plan_2 = Input::get('expense_plan_2');
                    $burn_rate_plan_2 = Input::get('burn_rate_plan_2');*/
					
					$submit_plan_2 = trim($request->input('date_revenue_plan_2'));
					$revenue_plan_2 = trim($request->input('revenue_plan_2'));
					$expense_plan_2 = trim($request->input('expense_plan_2'));
					$burn_rate_plan_2 = trim($request->input('burn_rate_plan_2'));
					
                    if(!empty($revenue_plan_2) && !empty($expense_plan_2)){
                        $financial_report_plan_2 = new NewReportFinancialInformationPlan;
                        $financial_report_plan_2->startup_id = $startup_id;
                        $financial_report_plan_2->master_id = $master_id;
                        $financial_report_plan_2->submit_date = $submit_plan_2;
                        $financial_report_plan_2->revenue = $revenue_plan_2;
                        $financial_report_plan_2->expense = $expense_plan_2;
                        $financial_report_plan_2->burn_rate = $burn_rate_plan_2;
                        $financial_report_plan_2->is_draft = $is_draft;
                        $financial_report_plan_2->month_year = date('Y-m-d');
                        $financial_report_plan_2->save();
                    }
                    //end

                    /*$remarks_fund_from_own_resources = Input::get('remarks_fund_from_own_resources');
                    $remarks_fund_form_external = Input::get('remarks_fund_form_external');
                    $remarks_iimcip_investment = Input::get('remarks_iimcip_investment');*/
					
					$remarks_fund_from_own_resources = trim($request->input('remarks_fund_from_own_resources'));
					$remarks_fund_form_external = trim($request->input('remarks_fund_form_external'));
					$remarks_iimcip_investment = trim($request->input('remarks_iimcip_investment'));
					
                    $reportFundingStatus = new NewReportFundingStatus;
                    $reportFundingStatus->startup_id = $startup_id;
                    $reportFundingStatus->master_id = $master_id;
                    $reportFundingStatus->remarks_fund_from_own_resources = $remarks_fund_from_own_resources;
                    $reportFundingStatus->remarks_fund_form_external = $remarks_fund_form_external;
                    $reportFundingStatus->remarks_iimcip_investment = $remarks_iimcip_investment;
                    $reportFundingStatus->created_at = date('Y-m-d H:i:s');
                    $reportFundingStatus->save();

                    /*$volume_annual_target = Input::get('volume_annual_target');
                    $sales_annual_target = Input::get('sales_annual_target');
                    $volume_achived_till_date = Input::get('volume_achived_till_date');
                    $sales_achived_till_date = Input::get('sales_achived_till_date');
                    $volume_sales_revenue = Input::get('volume_sales_revenue');
                    $sales_sales_revenue = Input::get('sales_sales_revenue');
                    $order_pipeline = Input::get('order_pipeline');*/
					
					$volume_annual_target = trim($request->input('volume_annual_target'));
					$sales_annual_target = trim($request->input('sales_annual_target'));
					$volume_achived_till_date = trim($request->input('volume_achived_till_date'));
					$sales_achived_till_date = trim($request->input('sales_achived_till_date'));
					$volume_sales_revenue = trim($request->input('volume_sales_revenue'));
					$sales_sales_revenue = trim($request->input('sales_sales_revenue'));
					$order_pipeline = trim($request->input('order_pipeline'));
					
					
                    $reportTargetAchievement = new NewReportTargetAchievement;
                    $reportTargetAchievement->startup_id = $startup_id;
                    $reportTargetAchievement->master_id = $master_id;
                    $reportTargetAchievement->volume_annual_target = $volume_annual_target;
                    $reportTargetAchievement->sales_annual_target = $sales_annual_target;
                    $reportTargetAchievement->volume_achived_till_date = $volume_achived_till_date;
                    $reportTargetAchievement->sales_achived_till_date = $sales_achived_till_date;
                    $reportTargetAchievement->volume_sales_revenue = $volume_sales_revenue;
                    $reportTargetAchievement->sales_sales_revenue = $sales_sales_revenue;
                    $reportTargetAchievement->order_pipeline = $order_pipeline;
                    $reportTargetAchievement->created_at = date('Y-m-d H:i:s');
                    $reportTargetAchievement->save();

                    /*$total_employee = Input::get('total_employee');
                    $fulltime_employee = Input::get('fulltime_employee');
                    $parttime_employee = Input::get('parttime_employee');
                    $founder_name = Input::get('founder_name');
                    $role_function = Input::get('role_function');*/
					
					$total_employee = trim($request->input('total_employee'));
					$fulltime_employee = trim($request->input('fulltime_employee'));
					$parttime_employee = trim($request->input('parttime_employee'));
					$founder_name = trim($request->input('founder_name'));
					$role_function = trim($request->input('role_function'));
					
                    $reportTeamDetails = new NewReportTeamDetails;
                    $reportTeamDetails->startup_id = $startup_id;
                    $reportTeamDetails->master_id = $master_id;
                    $reportTeamDetails->total_employee = $total_employee;
                    $reportTeamDetails->fulltime_employee = $fulltime_employee;
                    $reportTeamDetails->parttime_employee = $parttime_employee;
                    $reportTeamDetails->founder_name = $founder_name;
                    $reportTeamDetails->role_function = $role_function;
                    $reportTeamDetails->created_at = date('Y-m-d H:i:s');
                    $reportTeamDetails->save();

                    //return Redirect::route('incubate_report_list');
					//return back()->with('msg', 'Risk not added.')->with('msg_class', 'alert alert-danger');
					//return back()->with('msg', 'Report added.')->with('msg_class', 'alert alert-success');
					return redirect()->route('incubate_report_list')->with('msg', 'Report added successfully.')->with('msg_class', 'alert alert-success');

                }else{
                    echo '2';
                }
            }else{
                echo '3';
            }
        }else{
            return Redirect::route('home')->with('message','Please Login');
        }
    }
    
	public function incubate_report_edit($id){
        $data = array();
        if(\Auth::check()){
            $where = " 1=1 ";
            $startup_id = Auth::user()->id;
            $master_id = $data['master_id'] = $id;
            $startupDetails = Users::
            //join('start_up_investor_rel','start_up_investor_rel.start_up_id','=','users.id')
               // ->join('users as user2','user2.id','=','start_up_investor_rel.investor_id')
                select('users.*') //,'user2.contact_name as contact_name'
                ->where('users.id', '=', $startup_id)->where('users.status', '=', '1')->first();

            $startupInfoDetails = Entrepreneur::where('id', '=', $startup_id)->where('status', '=', 'Active')->first();
            $last_month_info = NewReportIncubateMaster::where('startup_id', '=', $startup_id)->where('id','=',$master_id)->whereRaw($where)->first();

            $prev_month = date("Y-m-2", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." -1 Month"));
            $prev_prev_month =  date("Y-m-2", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." -2 Month"));
            $prev_prev_prev_month =  date("Y-m-2", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." -3 Month"));
            $currnt_month =  date("Y-m-2", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." +0 Month"));
            $next_month =  date("Y-m-2", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." +1 Month"));
            $next_next_month =  date("Y-m-2", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." +2 Month"));
			
			
            
            
            //$where = " MONTH(new_report_incubate_master.submit_date)='".$prev_month."' ";
			
			$where = " MONTH(new_report_incubate_master.submit_date)='".date('m',strtotime($prev_month))."' and YEAR(new_report_incubate_master.submit_date)='".date('Y',strtotime($prev_month))."'";

            $reportFundingStatusDetails = $reportTargetAchievementDetails = $reportTeamDetails = $prevInformationDetails = $prev_prevInformationDetails = $prev_prev_prev_InformationDetails = $currentInformationDetails = $next_monthInformationDetails = $next_next_monthInformationDetails = array();

            if(isset($last_month_info)){ // && count($last_month_info)>0
                $reportTargetAchievementDetails = NewReportTargetAchievement::where('master_id','=',$master_id)->first();
                $reportFundingStatusDetails = NewReportFundingStatus::where('master_id','=',$master_id)->first();
                $reportTeamDetails = NewReportTeamDetails::where('master_id','=',$master_id)->first();

                $prevInformationDetails = NewReportFinancialInformation::
                where('startup_id', '=', $startup_id)
                    // ->where('master_id','=',$master_id)
                    ->whereRaw(" new_report_financial_information.submit_date='".$prev_month."' ")
                    ->orderBy('new_report_financial_information.id', 'desc')
                    ->first();
                $prev_prevInformationDetails = NewReportFinancialInformation::
                where('startup_id', '=', $startup_id)
                    //->where('master_id','=',$master_id)
                    ->whereRaw(" new_report_financial_information.submit_date='".$prev_prev_month."' ")
                    ->orderBy('new_report_financial_information.id', 'desc')
                    ->first();
                $prev_prev_prev_InformationDetails = NewReportFinancialInformation::
                where('startup_id', '=', $startup_id)
                    //->where('master_id','=',$master_id)
                    ->whereRaw(" new_report_financial_information.submit_date='".$prev_prev_prev_month."' ")
                    ->orderBy('new_report_financial_information.id', 'desc')
                    ->first();

                $currentInformationDetails = NewReportFinancialInformationPlan::
                where('startup_id', '=', $startup_id)
                    //->where('master_id','=',$master_id)
                    ->whereRaw(" new_report_financial_information_plan.submit_date='".$currnt_month."' ")
                    ->orderBy('new_report_financial_information_plan.id', 'desc')
                    ->first();
                $next_monthInformationDetails = NewReportFinancialInformationPlan::
                where('startup_id', '=', $startup_id)
                    //->where('master_id','=',$master_id)
                    ->whereRaw(" new_report_financial_information_plan.submit_date='".$next_month."' ")
                    ->orderBy('new_report_financial_information_plan.id', 'desc')
                    ->first();
                $next_next_monthInformationDetails = NewReportFinancialInformationPlan::
                where('startup_id', '=', $startup_id)
                    //->where('master_id','=',$master_id)
                    ->whereRaw(" new_report_financial_information_plan.submit_date='".$next_next_month."' ")
                    ->orderBy('new_report_financial_information_plan.id', 'desc')
                    ->first();

            }

            $data['master_id'] = $id;
            $data['startupDetails'] = $startupDetails;
            $data['last_month_info'] = $last_month_info;
            $data['startupInfoDetails'] = $startupInfoDetails;
            $data['reportFundingStatusDetails'] = $reportFundingStatusDetails;
            $data['reportTargetAchievementDetails'] = $reportTargetAchievementDetails;
            $data['reportTeamDetails'] = $reportTeamDetails;

            $data['prevInformationDetails'] = $prevInformationDetails;
            $data['prev_prevInformationDetails'] = $prev_prevInformationDetails;
            $data['prev_prev_prev_InformationDetails'] = $prev_prev_prev_InformationDetails;

            $data['currentInformationDetails'] = $currentInformationDetails;
            $data['next_monthInformationDetails'] = $next_monthInformationDetails;
            $data['next_next_monthInformationDetails'] = $next_next_monthInformationDetails;
            //echo '<pre>'; print_r($data); die();
            
            
            //return  $returnHTML = View::make('new_report.demo.edit_report2')->with('data',$data);
            ////return Response::view('new_report.demo.edit_report')->with('data',$data);
			
			return view('frontend.new_report.demo.edit_report2')->with('data',$data);
        }else{
            return Redirect::route('home')->with('message','Please Login');
        }

    }
    
	public function incubate_report_update(Request $request){

        //$master_id = Input::get('master_id');
		$master_id = trim($request->input('master_id'));
		
        if(!empty($master_id)){
            /*$challenges = Input::get('challenges');
            $progress_key_activities = Input::get('progress_key_activities');
            $funding_conversation = Input::get('funding_conversation');
            $planning = Input::get('planning');
            $awards_won = Input::get('awards_won');
            $comments = Input::get('comments');
            $support = Input::get('support');
            $cash_in_hand = Input::get('cash_in_hand');
            $is_draft = Input::get('is_draft');*/
			
			$challenges = trim($request->input('challenges'));	
			$progress_key_activities = trim($request->input('progress_key_activities'));	
			$funding_conversation = trim($request->input('funding_conversation'));	
			$planning = trim($request->input('planning'));	
			$awards_won = trim($request->input('awards_won'));	
			$comments = trim($request->input('comments'));	
			$support = trim($request->input('support'));	
			$cash_in_hand = trim($request->input('cash_in_hand'));	
			$is_draft = trim($request->input('is_draft'));				
			
            $incubateMaster = NewReportIncubateMaster::find($master_id);
            $incubateMaster->progress_key_activities = $progress_key_activities;
            $incubateMaster->funding_conversation = $funding_conversation;
            $incubateMaster->planning = $planning;
            $incubateMaster->awards_won = $awards_won;
            $incubateMaster->challenges = $challenges;
            $incubateMaster->comments = $comments;
            $incubateMaster->support = $support;
            $incubateMaster->cash_in_hand = $cash_in_hand;
            $incubateMaster->updated_at = date('Y-m-d H:i:s');
            $incubateMaster->is_draft = $is_draft;
            if($incubateMaster->save()){
                $startup_id = $incubateMaster->startup_id;
				
                /*$id_revenue_3 = Input::get('id_revenue_3');
                $date_revenue_3 = Input::get('date_revenue_3');
                $revenue_3 = Input::get('revenue_3');
                $expense_3 = Input::get('expense_3');
                $burn_rate_3 = Input::get('burn_rate_3');*/
				
				$id_revenue_3 = trim($request->input('id_revenue_3'));
				$date_revenue_3 = trim($request->input('date_revenue_3'));
				$revenue_3 = trim($request->input('revenue_3'));
				$expense_3 = trim($request->input('expense_3'));
				$burn_rate_3 = trim($request->input('burn_rate_3'));
				
				
                if(!empty($revenue_3) && !empty($expense_3)){
                    if(!empty($id_revenue_3)){
                        $financial_report_3 =NewReportFinancialInformation::find($id_revenue_3);
                        $financial_report_3->revenue = $revenue_3;
                        $financial_report_3->expense = $expense_3;
                        $financial_report_3->burn_rate = $burn_rate_3;
                        $financial_report_3->is_draft = $is_draft;
                        $financial_report_3->updated_at = date('Y-m-d H:i:s');
                    }else{
                        $financial_report_3 = new NewReportFinancialInformation;
                        $financial_report_3->startup_id = $startup_id;
                        $financial_report_3->master_id = $master_id;
                        $financial_report_3->submit_date = $date_revenue_3;
                        $financial_report_3->month_year	 = date('Y-m-d');
                        $financial_report_3->created_at = date('Y-m-d H:i:s');
                    }
                    $financial_report_3->revenue = $revenue_3;
                    $financial_report_3->expense = $expense_3;
                    $financial_report_3->burn_rate = $burn_rate_3;
                    $financial_report_3->is_draft = $is_draft;
                    $financial_report_3->save();
                }
                /*$id_revenue_2 = Input::get('id_revenue_2');
                $date_revenue_2 = Input::get('date_revenue_2');
                $revenue_2 = Input::get('revenue_2');
                $expense_2 = Input::get('expense_2');
                $burn_rate_2 = Input::get('burn_rate_2');*/
				
				$id_revenue_2 = trim($request->input('id_revenue_2'));
				$date_revenue_2 = trim($request->input('date_revenue_2'));
				$revenue_2 = trim($request->input('revenue_2'));
				$expense_2 = trim($request->input('expense_2'));
				$burn_rate_2 = trim($request->input('burn_rate_2'));
				
                if(!empty($revenue_2) && !empty($expense_2)){
                    if(!empty($id_revenue_2)){
                        $financial_report_2 = NewReportFinancialInformation::find($id_revenue_2);
                        $financial_report_2->updated_at = date('Y-m-d H:i:s');
                    }else{
                        $financial_report_2 = new NewReportFinancialInformation;
                        $financial_report_2->startup_id = $startup_id;
                        $financial_report_2->master_id = $master_id;
                        $financial_report_2->submit_date = $date_revenue_2;
                        $financial_report_2->month_year	 = date('Y-m-d');
                        $financial_report_2->created_at = date('Y-m-d H:i:s');
                    }
                    $financial_report_2->revenue = $revenue_2;
                    $financial_report_2->expense = $expense_2;
                    $financial_report_2->burn_rate = $burn_rate_2;
                    $financial_report_2->is_draft = $is_draft;
                    $financial_report_2->save();
                }
                /*$id_revenue_1 = Input::get('id_revenue_1');
                $date_revenue_1 = Input::get('date_revenue_1');
                $revenue_1 = Input::get('revenue_1');
                $expense_1 = Input::get('expense_1');
                $burn_rate_1 = Input::get('burn_rate_1');*/
				
				$id_revenue_1 = trim($request->input('id_revenue_1'));
				$date_revenue_1 = trim($request->input('date_revenue_1'));
				$revenue_1 = trim($request->input('revenue_1'));
				$expense_1 = trim($request->input('expense_1'));
				$burn_rate_1 = trim($request->input('burn_rate_1'));
				
                if(!empty($revenue_1) && !empty($expense_1)){
                    if(!empty($id_revenue_1)){
                        $financial_report_1 = NewReportFinancialInformation::find($id_revenue_1);
                        $financial_report_1->updated_at = date('Y-m-d H:i:s');
                    }else{
                        $financial_report_1 = new NewReportFinancialInformation;
                        $financial_report_1->startup_id = $startup_id;
                        $financial_report_1->master_id = $master_id;
                        $financial_report_1->submit_date = $date_revenue_1;
                        $financial_report_1->month_year	 = date('Y-m-d');
                        $financial_report_1->created_at = date('Y-m-d H:i:s');
                    }
                    $financial_report_1->revenue = $revenue_1;
                    $financial_report_1->expense = $expense_1;
                    $financial_report_1->burn_rate = $burn_rate_1;
                    $financial_report_1->is_draft = $is_draft;
                    $financial_report_1->save();
                }
                //end
                //NewReportFinancialInformationPlan
                /*$id_plan_0 = Input::get('id_revenue_plan_0');
                $submit_plan_0 = Input::get('date_revenue_plan_0');
                $revenue_plan_0 = Input::get('revenue_plan_0');
                $expense_plan_0 = Input::get('expense_plan_0');
                $burn_rate_plan_0 = Input::get('burn_rate_plan_0');*/
				
				$id_plan_0 = trim($request->input('id_revenue_plan_0'));
				$submit_plan_0 = trim($request->input('date_revenue_plan_0'));
				$revenue_plan_0 = trim($request->input('revenue_plan_0'));
				$expense_plan_0 = trim($request->input('expense_plan_0'));
				$burn_rate_plan_0 = trim($request->input('burn_rate_plan_0'));
				
				
                if(!empty($revenue_plan_0) && !empty($expense_plan_0)){
                    if(!empty($id_plan_0)){
                        $financial_report_plan = NewReportFinancialInformationPlan::find($id_plan_0);
                        $financial_report_plan->updated_at = date('Y-m-d H:i:s');
                    }else{
                        $financial_report_plan = new NewReportFinancialInformationPlan;
                        $financial_report_plan->startup_id = $startup_id;
                        $financial_report_plan->master_id = $master_id;
                        $financial_report_plan->submit_date = $submit_plan_0;
                        $financial_report_plan->month_year = date('Y-m-d');
                        $financial_report_plan->created_at = date('Y-m-d H:i:s');
                    }
                    $financial_report_plan->revenue = $revenue_plan_0;
                    $financial_report_plan->expense = $expense_plan_0;
                    $financial_report_plan->burn_rate = $burn_rate_plan_0;
                    $financial_report_plan->is_draft = $is_draft;
                    $financial_report_plan->save();
                }
                /*$id_plan_1 = Input::get('id_revenue_plan_1');
                $submit_plan_1 = Input::get('date_revenue_plan_1');
                $revenue_plan_1 = Input::get('revenue_plan_1');
                $expense_plan_1 = Input::get('expense_plan_1');
                $burn_rate_plan_1 = Input::get('burn_rate_plan_1');*/
				
				$id_plan_1 = trim($request->input('id_revenue_plan_1'));
				$submit_plan_1 = trim($request->input('date_revenue_plan_1'));
				$revenue_plan_1 = trim($request->input('revenue_plan_1'));
				$expense_plan_1 = trim($request->input('expense_plan_1'));
				$burn_rate_plan_1 = trim($request->input('burn_rate_plan_1'));
				
				
                if(!empty($revenue_plan_1) && !empty($expense_plan_1)){

                    if(!empty($id_plan_1)){
                        $financial_report_plan_1 = NewReportFinancialInformationPlan::find($id_plan_1);
                        $financial_report_plan_1->updated_at = date('Y-m-d H:i:s');
                    }else{
                        $financial_report_plan_1 = new NewReportFinancialInformationPlan;
                        $financial_report_plan_1->startup_id = $startup_id;
                        $financial_report_plan_1->master_id = $master_id;
                        $financial_report_plan_1->submit_date = $submit_plan_1;
                        $financial_report_plan_1->month_year = date('Y-m-d');
                        $financial_report_plan_1->created_at = date('Y-m-d H:i:s');
                    }
                    $financial_report_plan_1->revenue = $revenue_plan_1;
                    $financial_report_plan_1->expense = $expense_plan_1;
                    $financial_report_plan_1->burn_rate = $burn_rate_plan_1;
                    $financial_report_plan_1->is_draft = $is_draft;
                    $financial_report_plan_1->save();

                }
                /*$id_plan_2 = Input::get('id_revenue_plan_2');
                $submit_plan_2 = Input::get('date_revenue_plan_2');
                $revenue_plan_2 = Input::get('revenue_plan_2');
                $expense_plan_2 = Input::get('expense_plan_2');
                $burn_rate_plan_2 = Input::get('burn_rate_plan_2');*/
				
				$id_plan_2 = trim($request->input('id_revenue_plan_2'));
				$submit_plan_2 = trim($request->input('date_revenue_plan_2'));
				$revenue_plan_2 = trim($request->input('revenue_plan_2'));
				$expense_plan_2 = trim($request->input('expense_plan_2'));
				$burn_rate_plan_2 = trim($request->input('burn_rate_plan_2'));
				
                if(!empty($revenue_plan_2) && !empty($expense_plan_2)){
                    if(!empty($id_plan_2)){
                        $financial_report_plan_2 = NewReportFinancialInformationPlan::find($id_plan_2);
                        $financial_report_plan_2->updated_at = date('Y-m-d H:i:s');
                    }else{
                        $financial_report_plan_2 = new NewReportFinancialInformationPlan;
                        $financial_report_plan_2->startup_id = $startup_id;
                        $financial_report_plan_2->master_id = $master_id;
                        $financial_report_plan_2->submit_date = $submit_plan_2;
                        $financial_report_plan_2->month_year = date('Y-m-d');
                        $financial_report_plan_2->created_at = date('Y-m-d H:i:s');
                    }
                    $financial_report_plan_2->revenue = $revenue_plan_2;
                    $financial_report_plan_2->expense = $expense_plan_2;
                    $financial_report_plan_2->burn_rate = $burn_rate_plan_2;
                    $financial_report_plan_2->is_draft = $is_draft;
                    $financial_report_plan_2->save();
                }
                //ReportTeamDetails::where('master_id', $master_id)->firstOrFail();
                /*$remarks_fund_from_own_resources = Input::get('remarks_fund_from_own_resources');
                $remarks_fund_form_external = Input::get('remarks_fund_form_external');
                $remarks_iimcip_investment = Input::get('remarks_iimcip_investment');*/
				
				$remarks_fund_from_own_resources = trim($request->input('remarks_fund_from_own_resources'));
				$remarks_fund_form_external = trim($request->input('remarks_fund_form_external'));
				$remarks_iimcip_investment = trim($request->input('remarks_iimcip_investment'));
				
                $reportFundingStatus = NewReportFundingStatus::where('master_id', $master_id)->firstOrFail();
                $reportFundingStatus->remarks_fund_from_own_resources = $remarks_fund_from_own_resources;
                $reportFundingStatus->remarks_fund_form_external = $remarks_fund_form_external;
                $reportFundingStatus->remarks_iimcip_investment = $remarks_iimcip_investment;
                $reportFundingStatus->updated_at = date('Y-m-d H:i:s');
                $reportFundingStatus->save();

                /*$volume_annual_target = Input::get('volume_annual_target');
                $sales_annual_target = Input::get('sales_annual_target');
                $volume_achived_till_date = Input::get('volume_achived_till_date');
                $sales_achived_till_date = Input::get('sales_achived_till_date');
                $volume_sales_revenue = Input::get('volume_sales_revenue');
                $sales_sales_revenue = Input::get('sales_sales_revenue');
                $order_pipeline = Input::get('order_pipeline');*/
				
				$volume_annual_target = trim($request->input('volume_annual_target'));
				$sales_annual_target = trim($request->input('sales_annual_target'));
				$volume_achived_till_date = trim($request->input('volume_achived_till_date'));				
				$sales_achived_till_date = trim($request->input('sales_achived_till_date'));
				$volume_sales_revenue = trim($request->input('volume_sales_revenue'));
				$sales_sales_revenue = trim($request->input('sales_sales_revenue'));
				$order_pipeline = trim($request->input('order_pipeline'));
								
				
				
                $reportTargetAchievement = NewReportTargetAchievement::where('master_id', $master_id)->firstOrFail();
                $reportTargetAchievement->volume_annual_target = $volume_annual_target;
                $reportTargetAchievement->sales_annual_target = $sales_annual_target;
                $reportTargetAchievement->volume_achived_till_date = $volume_achived_till_date;
                $reportTargetAchievement->sales_achived_till_date = $sales_achived_till_date;
                $reportTargetAchievement->volume_sales_revenue = $volume_sales_revenue;
                $reportTargetAchievement->sales_sales_revenue = $sales_sales_revenue;
                $reportTargetAchievement->order_pipeline = $order_pipeline;
                $reportTargetAchievement->updated_at = date('Y-m-d H:i:s');
                $reportTargetAchievement->save();

                /*$total_employee = Input::get('total_employee');
                $fulltime_employee = Input::get('fulltime_employee');
                $parttime_employee = Input::get('parttime_employee');
                $founder_name = Input::get('founder_name');
                $role_function = Input::get('role_function');*/
				
				$total_employee = trim($request->input('total_employee'));
				$fulltime_employee = trim($request->input('fulltime_employee'));
				$parttime_employee = trim($request->input('parttime_employee'));
				$founder_name = trim($request->input('founder_name'));
				$role_function = trim($request->input('role_function'));
				
				
				
                $reportTeamDetails = NewReportTeamDetails::where('master_id', $master_id)->firstOrFail();
                $reportTeamDetails->total_employee = $total_employee;
                $reportTeamDetails->fulltime_employee = $fulltime_employee;
                $reportTeamDetails->parttime_employee = $parttime_employee;
                $reportTeamDetails->founder_name = $founder_name;
                $reportTeamDetails->role_function = $role_function;
                $reportTeamDetails->updated_at = date('Y-m-d H:i:s');
                $reportTeamDetails->save();
            }
			
            //return Redirect::route('incubate_report_list');
            ////return Redirect::URL('admin/list_report/'.$startup_id); //$startup_id
			
			//return back()->with('msg', 'Report updated.')->with('msg_class', 'alert alert-success');
			return redirect()->route('incubate_report_list')->with('msg', 'Report updated successfully.')->with('msg_class', 'alert alert-success');
			
        }
    }
    
	public function incubate_report_download($id){
		//require_once Config::get('app.base_url')."common/helpers.php";
		require_once $_SERVER['DOCUMENT_ROOT']."/iimcip-network/common/helpers.php";
		
        if(\Auth::check()){
            //require_once Config::get('app.base_url')."mpdf/vendor/autoload.php";
			//require_once('iimcip-network/mpdf/vendor/autoload.php');
			
			//echo $_SERVER['DOCUMENT_ROOT'];die;
			require_once $_SERVER['DOCUMENT_ROOT']."/iimcip-network/mpdf/vendor/autoload.php";
			
			//config("constants.site_name")

            $mpdf = new \mPDF();
            $mpdf->autoScriptToLang = true;
            $mpdf->autoLangToFont = true;
            //$pdf_name = "test.pdf";
            ob_start();
            $current = date('Y');
            $comming = date('y')+1;
            $financial_year = $current.'-'.$comming;

            $where = " 1=1 ";
            $startup_id = Auth::user()->id;
            $master_id = $data['master_id'] = $id;
            $startupDetails = Users::
            //join('start_up_investor_rel','start_up_investor_rel.start_up_id','=','users.id')
                //->join('users as user2','user2.id','=','start_up_investor_rel.investor_id')
                //->leftJoin('countries as cnt1','users.country_id','=','cnt1.sortname')
                //->leftJoin('cities as cty1','users.city_id','=','cty1.id')
                //->leftJoin('countries as cnt2','users.country_id2','=','cnt2.sortname')
                //->leftJoin('cities as cty2','users.city_id2','=','cty2.id')
                select('users.*') //,'user2.contact_name as contact_name','cnt1.name as country1','cnt2.name as country2','cty1.city_name as city1','cty2.city_name as city2'
                ->where('users.id', '=', $startup_id)->where('users.status', '=', '1')->first();
            #echo '<pre>';print_r($startupDetails);
            /*$startupInfoDetails = Entrepreneur::
                leftJoin("structure_of_companies as soc","soc.id","=","entrepreneurs.structure_of_company")
                ->select('entrepreneurs.*','soc.name as soc_name')
            ->where('entrepreneurs.id', '=', $startup_id)->where('entrepreneurs.status', '=', 'Active')->first();*/
			
			
            $last_month_info = NewReportIncubateMaster::where('startup_id', '=', $startup_id)->where('id','=',$master_id)->whereRaw($where)->first();
			
			/*
            $prev_month = date("m", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." -1 Month"));
            $prev_prev_month =  date("m", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." -2 Month"));
            $prev_prev_prev_month =  date("m", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." -3 Month"));
            $currnt_month =  date("m", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." +0 Month"));
            $next_month =  date("m", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." +1 Month"));
            $next_next_month =  date("m", strtotime(date('Y-m-2',strtotime($last_month_info['submit_date']))." +2 Month"));
            */
			
		$prev_month = date('Y-m-d',strtotime('-1 months', strtotime($last_month_info['submit_date']))); //date('m',strtotime($last_month_info['submit_date']))-1;
        $prev_prev_month =  date('Y-m-d',strtotime('-2 months', strtotime($last_month_info['submit_date'])));
        $prev_prev_prev_month =  date('Y-m-d',strtotime('-3 months', strtotime($last_month_info['submit_date'])));
		
		
		
		$currnt_month =  $last_month_info['submit_date'];
        $next_month =  date('Y-m-d',strtotime('+1 months', strtotime($last_month_info['submit_date'])));
        $next_next_month = date('Y-m-d',strtotime('+2 months', strtotime($last_month_info['submit_date'])));
			
            
            $where = " MONTH(new_report_incubate_master.submit_date)='".$prev_month."' ";

            $reportFundingStatusDetails = $reportTargetAchievementDetails = $reportTeamDetails = $prevInformationDetails = $prev_prevInformationDetails = $prev_prev_prev_InformationDetails = $currentInformationDetails = $next_monthInformationDetails = $next_next_monthInformationDetails = array();

            if(isset($last_month_info)){ // && count($last_month_info)>0
                $reportTargetAchievementDetails = NewReportTargetAchievement::where('master_id','=',$master_id)->first();
                $reportFundingStatusDetails = NewReportFundingStatus::where('master_id','=',$master_id)->first();
                $reportTeamDetails = NewReportTeamDetails::where('master_id','=',$master_id)->first();

                $prevInformationDetails = NewReportFinancialInformation::
                where('startup_id', '=', $startup_id)
                    ->where('master_id','=',$master_id)
                    //->whereRaw(" MONTH(new_report_financial_information.submit_date)='".$prev_month."' ")
					->whereRaw(" MONTH(new_report_financial_information.submit_date)='".date("m",strtotime($prev_month))."' and YEAR(new_report_financial_information.submit_date)='".date("Y",strtotime($prev_month))."' ")
                    ->first();
                $prev_prevInformationDetails = NewReportFinancialInformation::
                where('startup_id', '=', $startup_id)
                    //->where('master_id','=',$master_id)
                    //->whereRaw(" MONTH(new_report_financial_information.submit_date)='".$prev_prev_month."' ")
					->whereRaw(" MONTH(new_report_financial_information.submit_date)='".date("m",strtotime($prev_prev_month))."' and YEAR(new_report_financial_information.submit_date)='".date("Y",strtotime($prev_prev_month))."' ")
                    ->first();
                $prev_prev_prev_InformationDetails = NewReportFinancialInformation::
                where('startup_id', '=', $startup_id)
                    //->where('master_id','=',$master_id)
                    //->whereRaw(" MONTH(new_report_financial_information.submit_date)='".$prev_prev_prev_month."' ")
					->whereRaw(" MONTH(new_report_financial_information.submit_date)='".date("m",strtotime($prev_prev_prev_month))."' and YEAR(new_report_financial_information.submit_date)='".date("Y",strtotime($prev_prev_prev_month))."' ")
                    ->first();

                $currentInformationDetails = NewReportFinancialInformationPlan::
                where('startup_id', '=', $startup_id)
                    ->where('master_id','=',$master_id)
                    //->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".$currnt_month."' ")
					->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".date("m",strtotime($currnt_month))."' and YEAR(new_report_financial_information_plan.submit_date)='".date("Y",strtotime($currnt_month))."' ")
                    ->orderBy('new_report_financial_information_plan.id', 'desc')
                    ->first();
                $next_monthInformationDetails = NewReportFinancialInformationPlan::
                where('startup_id', '=', $startup_id)
                    ->where('master_id','=',$master_id)
                    //->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".$next_month."' ")
					->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".date("m",strtotime($next_month))."' and YEAR(new_report_financial_information_plan.submit_date)='".date("Y",strtotime($next_month))."' ")
                    ->orderBy('new_report_financial_information_plan.id', 'desc')
                    ->first();
                $next_next_monthInformationDetails = NewReportFinancialInformationPlan::
                where('startup_id', '=', $startup_id)
                    ->where('master_id','=',$master_id)
                   // ->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".$next_next_month."' ")
				   ->whereRaw(" MONTH(new_report_financial_information_plan.submit_date)='".date("m",strtotime($next_next_month))."' and YEAR(new_report_financial_information_plan.submit_date)='".date("Y",strtotime($next_next_month))."' ")
                    ->orderBy('new_report_financial_information_plan.id', 'desc')
                    ->first();

            }
            /*$promoterDetails = Promoter::where('user_id', '=', $startup_id)
                ->orderBy('id', 'asc')
                ->first();*/
            $pdf_name = $startupDetails['member_company'].'-'.date('M y',strtotime($last_month_info['submit_date'])).'.pdf';

            $where = " startup_target_achievement.created_at BETWEEN '" . checkFinancialStart(date('Y')) . "' AND '" . checkFinancialEnd(date('Y')) . "'  ";
            $current_target_achievement = StartupTargetAchievement::where('startup_id', $startup_id)->whereRaw($where)->first();

            $where1 = " startup_target_achievement.created_at BETWEEN '" . checkFinancialStart(date('Y') - 1) . "' AND '" . checkFinancialEnd(date('Y') - 1) . "'  ";
            $current_target_achievement1 = StartupTargetAchievement::where('startup_id', $startup_id)->whereRaw($where1)->first();

            $data['current_target_achievement'] = $current_target_achievement;
            $data['current_target_achievement1'] = $current_target_achievement1;
            $data['startupDetails'] = $startupDetails;
            $data['last_month_info'] = $last_month_info;
            $data['startupInfoDetails'] = $startupInfoDetails;
            $data['reportFundingStatusDetails'] = $reportFundingStatusDetails;
            $data['reportTargetAchievementDetails'] = $reportTargetAchievementDetails;
            $data['reportTeamDetails'] = $reportTeamDetails;

            $data['prevInformationDetails'] = $prevInformationDetails;
            $data['prev_prevInformationDetails'] = $prev_prevInformationDetails;
            $data['prev_prev_prev_InformationDetails'] = $prev_prev_prev_InformationDetails;

            //$data['promoterDetails'] = $promoterDetails;
            $data['financial_year'] = date('Y');//$financial_year;
            $data['currentInformationDetails'] = $currentInformationDetails;
            $data['next_monthInformationDetails'] = $next_monthInformationDetails;
            $data['next_next_monthInformationDetails'] = $next_next_monthInformationDetails;
            $data['pdf_name'] = $pdf_name;
//            echo $data['financial_year'] ; die();

            //$html = View::make('new_report.demo.generatePDF')->with('data',$data);
			
			$html = view('frontend.new_report.demo.generatePDF')->with('data',$data);

//            $html = 'test';
            ob_end_clean();

//            $mpdf->SetDisplayMode('fullpage');
//            $mpdf->list_indent_first_level = 0;
            $mpdf->WriteHTML($html);
            $mpdf->Output($pdf_name, 'D');
            echo $pdf_name;
        }else{
            return Redirect::route('home')->with('message','Please Login');
        }
    }
}
