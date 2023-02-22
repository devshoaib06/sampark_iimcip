<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Auth;
use DB;

class AppointmentAvailability extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'appointment_availability';
			
	
	public function getAppointmentAjax($start,$end){
            $user_id = Auth::user()->id;
            $where = '1=1';
            if(Auth::user()->user_type=='e'){
                $where = 'appo.start_up_id ='.$user_id;
            }elseif(Auth::user()->user_type=='i'){
                $where = 'appo.investor_id ='.$user_id;
            }elseif(Auth::user()->user_type=='pm'){
                $where = 'appo.created_by ='.$user_id;
            }

            $query = DB::table("appointment as appo")
                        ->join('users as u', 'u.id', '=', 'appo.investor_id')
                        ->join('users as u1', 'u1.id', '=', 'appo.start_up_id')
                        ->select("appo.id","appo.start_up_id","appo.investor_id",
                            DB::raw("concat(COALESCE(appo.apoointment_date,''),' ',COALESCE(appo.apoointment_time,'')) as start"),
                            DB::raw("concat(COALESCE(appo.subject,''),' from ',COALESCE(appo.created_by,''),' with ',COALESCE(u.individual_name,''),' and ',COALESCE(u1.individual_name,'')) as title"),
                           "appo.created_by",
                           "appo.apoointment_date","appo.apoointment_time","appo.background as backgroundColor")
                       ->whereRaw($where)
                       ->whereBetween('appo.apoointment_date', array($start, $end))
                       ->get();
            return $query;
	}
			
}
