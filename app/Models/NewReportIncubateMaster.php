<?php
namespace App\Models;


/*use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;*/

use Illuminate\Database\Eloquent\Model;

class NewReportIncubateMaster extends Model  
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'new_report_incubate_master';
        
        public function entrepreneur(){
            return $this->belongsTo('Entrepreneur', 'id');
        }
                
        public function getAllData($id,$month,$year){
            $allData = $master_data = $burnRate = $achievement = $projected = $turnover = $funding_status = array();
            $master_data = DB::select("select * from new_report_incubate_master where startup_id=".$id." AND MONTH(submit_date)=".$month." AND YEAR(submit_date)=".$year);
           //echo "select * from new_report_incubate_master where startup_id=".$id." AND MONTH(submit_date)=".$month." AND YEAR(submit_date)=".$year;
		   //print_r($master_data); die();
            if(!empty($master_data) && count($master_data)>0){
                $prev_prev_prev_month =  date('m',strtotime($master_data[0]->submit_date))-3;
                $prev_month =  date('m',strtotime($master_data[0]->submit_date))-1;
                $current =  date('m',strtotime($master_data[0]->submit_date))-0;
                $next_next_month =  date('m',strtotime($master_data[0]->submit_date))+2;
                $burnRate = DB::select("select avg(burn_rate) as avg_burn_rate from new_report_financial_information where startup_id=".$id." AND master_id=".$master_data[0]->id." AND submit_date between '".$year."-".$prev_prev_prev_month."-01' AND '".$year."-".$prev_month."-30' "); 
                $achievement = DB::select("select sales_achived_till_date from new_report_target_achievement where startup_id=".$id." AND master_id=".$master_data[0]->id);
                
				//$projected = DB::select("select sum(revenue) as revenue from new_report_financial_information_plan where startup_id=".$id." AND master_id=".$master_data[0]->id." AND submit_date between '".$year."-".$current."-01' AND '".$year."-".$next_next_month."-30' ");
                
				// prev finacial year
				$turnover_where=" startup_target_achievement.created_at BETWEEN '" . checkFinancialStart(date('Y')-1) . "' AND '" . checkFinancialEnd(date('Y')-1) . "'  ";
				$turnover = StartupTargetAchievement::where('startup_id', $id)->whereRaw($turnover_where)->first();
				
				
				$projected_where=" startup_target_achievement.created_at BETWEEN '" . checkFinancialStart(date('Y')) . "' AND '" . checkFinancialEnd(date('Y')) . "'  ";
				$projected = StartupTargetAchievement::where('startup_id', $id)->whereRaw($projected_where)->first();
				
				
				$funding_status = DB::select("select remarks_fund_form_external,remarks_iimcip_investment from new_report_funding_status where startup_id=".$id." AND master_id=".$master_data[0]->id);
               
            }
			
            $allData = [
                'master_data'=>$master_data,
                'burnRate'=>$burnRate,
                'achievement'=>$achievement,
				'turnover'=>$turnover,
                'projected'=>$projected,
                'funding_status'=>$funding_status
        ];
            //print_r($achievement);
            return $allData;
        }
        
        public function getReportStatus($id){
            $myReports = NewReportIncubateMaster::where('startup_id','=',$id)
                    ->whereRaw(" MONTH(new_report_incubate_master.submit_date)='".date('m')."' ")
                    ->first();
            $flag = 0;
            if(isset($myReports) && count($myReports)>0){
                $flag = 1;
            }
            return $flag;
        }
}

