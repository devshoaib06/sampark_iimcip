<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Auth;
use DB;
class Appointment extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'appointment';
			
	public function getAppointment(){
            /*echo '<pre>';
            print_r(Auth::user()->user_type);*/
            $user_id = Auth::user()->id; 
            //die('soumya');*/
            $where = '1=1';
            if(Auth::user()->user_type=='2'){ //start_up_id
                    $where = 'appo.start_up_id ='.$user_id;
            }elseif(Auth::user()->user_type=='6'){
                    $where = 'appo.investor_id ='.$user_id;
            }elseif(Auth::user()->user_type=='4'){
                    $where = 'appo.created_by ='.$user_id;
            }
		
            $query = DB::table("appointment as appo")
                ->join('users as u', 'u.id', '=', 'appo.investor_id')
                ->join('users as u1', 'u1.id', '=', 'appo.start_up_id')
                ->join('users as u3', 'u3.id', '=', 'appo.created_by')
                ->select("appo.id","appo.start_up_id","appo.investor_id","appo.subject as subject",
                    DB::raw("concat(COALESCE(appo.apoointment_date,''),' ',COALESCE(appo.apoointment_time,'')) as start"),
                    DB::raw("concat(COALESCE(appo.subject,''),' from ',COALESCE(u3.member_company,''),' with ',COALESCE(u.member_company,''),' and ',COALESCE(u1.member_company,'')) as title"),
                   "appo.created_by",
                   "appo.apoointment_date","appo.apoointment_time")
                ->whereRaw($where)
                //->where('appo.investor_approval', '1')
                ->get();
        //dd(DB::getQueryLog());
		return $query;
	}
	public function getAppointmentAjax($start,$end){
            $user_id = Auth::user()->id;
            $td = date('Y-m-d ');
            $where = '1=1';
            if(Auth::user()->user_type=='2'){
                    $where = 'appo.start_up_id ='.$user_id;
            }elseif(Auth::user()->user_type=='6'){
                    $where = 'appo.investor_id ='.$user_id;
            }elseif(Auth::user()->user_type=='4'){
                    $where = 'appo.created_by ='.$user_id;
            }

            $query = DB::table("appointment as appo")
                        ->join('users as u', 'u.id', '=', 'appo.investor_id')
                        ->join('users as u1', 'u1.id', '=', 'appo.start_up_id')
                        ->join('users as u3', 'u3.id', '=', 'appo.created_by')
                        ->select("appo.id","appo.start_up_id","appo.subject as subject",
                                "appo.investor_id","u1.member_company as startup_name","u.first_name as investor_name",
                            DB::raw("concat(COALESCE(appo.apoointment_date,''),' ',COALESCE(appo.apoointment_time,'')) as start"),
                            //DB::raw("concat(COALESCE(appo.subject,''),' from ',COALESCE(u3.individual_name,''),' with ',COALESCE(u.individual_name,''),' and ',COALESCE(u1.name,'')) as title"),
                            DB::raw("if(('".Auth::user()->user_type."'='e'),u.member_company, if(('".Auth::user()->user_type."'='i'),u1.first_name, u3.member_company)) as title"),
                            DB::raw("if((appo.apoointment_date>='".$td."'), appo.background,'#e4230d') as backgroundColor"),
                           "appo.created_by","appo.type as type",
                           "appo.apoointment_date","appo.apoointment_time")
                       ->whereRaw($where)
                       ->whereBetween('appo.apoointment_date', array($start, $end))
                       ->get();
            return $query;
	}        
	public function getAvailabilityAjax($start,$end){
            $user_id = Auth::user()->id;
            $where = '1=1';
            if(Auth::user()->user_type=='6'){
                $query = $this->getEnterpreneursData($start,$end);
            }else{
                $query = $this->getMentorData($start,$end);
            }
            #dd(DB::getQueryLog());
            return $query;
	}
        public function getEnterpreneursData($start,$end){
            $user_id = Auth::user()->id;
            $td = date('Y-m-d ');
            $investor = StartUpInvestorRel::where('start_up_id','=',$user_id)->select('investor_id')->first();
            //echo $investor_id->investor_id;
            $investor_id = (isset($investor) && !empty($investor->investor_id))?$investor->investor_id:0;
            $where = '1=1';
            $where = 'av.created_by ='.$investor_id;
            $query = DB::table("appointment_availability as av")
                        ->join('users as u', 'u.id', '=', 'av.created_by')
                        //->leftJoin('appointment_availability_relation as aar', 'aar.app_avail_id', '=', 'av.id')
                        //->join('users as u1', 'u1.id', '=', 'av.start_up_id')
                        ->select("av.id","av.start_up_id",
                            DB::raw("concat(COALESCE(av.availability_date,'')) as start"),
//                            DB::raw("concat(COALESCE(av.availability_date,''),' ',COALESCE(av.avalibility_time_start,'')) as start"),
                            DB::raw("concat(COALESCE(DATE_FORMAT(av.avalibility_time_start,'%H:%i'),''),'-',COALESCE(DATE_FORMAT(av.avalibility_time_end,'%H:%i'),'')) as title"),
                            //DB::raw("concat(COALESCE(u.individual_name,''),' -',COALESCE(DATE_FORMAT(av.avalibility_time_start,'%H:%i'),''),'-',COALESCE(DATE_FORMAT(av.avalibility_time_end,'%H:%i'),'')) as title"),
                           "av.created_by","av.type as type",
                           DB::raw("if(av.availablity_status = '0', '0','1') as availablity_status"),
                           DB::raw("if((av.availability_date>='".$td."'), av.background,'#e4230d') as backgroundColor"),
                           "av.availability_date as apoointment_date","av.avalibility_time_start as apoointment_time")
                       ->where('av.availablity_status','=','1')
                       ->whereRaw($where)
                       ->whereBetween('av.availability_date', array($start, $end))
                       ->get();
            return $query;
        }
        public function getMentorData($start,$end){
            $user_id = Auth::user()->id;
            $td = date('Y-m-d');
            $where = '1=1';
            $where = 'av.created_by ='.$user_id;
            $query = DB::table("appointment_availability as av")
                        ->join('users as u', 'u.id', '=', 'av.created_by')
                        ->select("av.id","av.start_up_id",
                            DB::raw("concat(COALESCE(av.availability_date,'')) as start"),
                            //DB::raw("concat(COALESCE(av.availability_date,''),' ',COALESCE(av.avalibility_time_start,'')) as start"),
                            DB::raw("concat(COALESCE(if(av.availablity_status = '0', '(Booked)',''),''),COALESCE(DATE_FORMAT(av.avalibility_time_start,'%H:%i'),''),'-',COALESCE(DATE_FORMAT(av.avalibility_time_end,'%H:%i'),'')) as title"),
                            //DB::raw("concat(COALESCE(if(av.availablity_status = '0', '(Booked)',''),''),COALESCE(u.individual_name,''),' -',COALESCE(DATE_FORMAT(av.avalibility_time_start,'%H:%i'),''),'-',COALESCE(DATE_FORMAT(av.avalibility_time_end,'%H:%i'),'')) as title"),
                           "av.created_by","av.type as type",
                            DB::raw("if(av.availablity_status = '0', '0','1') as availablity_status"),
                            DB::raw("if((av.availability_date>='".$td."'), av.background,'#e4230d') as backgroundColor"),
                           "av.availability_date as apoointment_date","av.avalibility_time_start as apoointment_time")
                       ->whereRaw($where)
                       ->whereBetween('av.availability_date', array($start, $end))
                       ->get();
            return $query;
        }
        
        
        
        public function getAppointmentAjaxPM($start,$end){
            $user_id = Session::get('userid');
            $td = date('Y-m-d ');
            $where = '1=1';
            if(Session::get('user_type')=='e'){
                    $where = 'appo.start_up_id ='.$user_id;
            }elseif(Session::get('user_type')=='i'){
                    $where = 'appo.investor_id ='.$user_id;
            }elseif(Session::get('user_type')=='pm'){
                    $where = 'appo.created_by ='.$user_id;
            }

            $query = DB::table("appointment as appo")
                        ->join('users as u', 'u.id', '=', 'appo.investor_id')
                        ->join('users as u1', 'u1.id', '=', 'appo.start_up_id')
                        ->join('users as u3', 'u3.id', '=', 'appo.created_by')
                        ->select("appo.id","appo.start_up_id","appo.subject as subject",
                                "appo.investor_id","u1.name as startup_name","u.individual_name as investor_name",
                            DB::raw("concat(COALESCE(appo.apoointment_date,''),' ',COALESCE(appo.apoointment_time,'')) as start"),
                            DB::raw("concat(COALESCE(appo.subject,''),' from ',COALESCE(u3.individual_name,''),' with ',COALESCE(u.individual_name,''),' and ',COALESCE(u1.name,'')) as title"),
                            DB::raw("if((appo.apoointment_date>='".$td."'), appo.background,'#e4230d') as backgroundColor"),
                           "appo.created_by","appo.type as type",
                           "appo.apoointment_date","appo.apoointment_time")
                       ->whereRaw($where)
                       ->whereBetween('appo.apoointment_date', array($start, $end))
                       ->get();
            return $query;
	}        
	public function getAvailabilityAjaxPM($start,$end,$startup_id){
            $user_id = Session::get('userid');
            $where = '1=1';
            /*if(Session::get('user_type')=='e'){
                $query = $this->getEnterpreneursDataPM($start,$end);
            }else{*/
                $query = $this->getMentorDataPM($start,$end,$startup_id);
            //}

            
            #dd(DB::getQueryLog());
            return $query;
	}
        public function getEnterpreneursDataPM($start,$end){
            $user_id = Session::get('userid');
            $td = date('Y-m-d ');
            $investor = StartUpInvestorRel::where('start_up_id','=',$user_id)->select('investor_id')->first();
            //echo $investor_id->investor_id;
            $investor_id = (isset($investor) && !empty($investor->investor_id))?$investor->investor_id:0;
            $where = '1=1';
            $where = 'av.created_by ='.$investor_id;
            $query = DB::table("appointment_availability as av")
                        ->join('users as u', 'u.id', '=', 'av.created_by')
                        ->leftJoin('appointment_availability_relation as aar', 'aar.app_avail_id', '=', 'av.id')
                        //->join('users as u1', 'u1.id', '=', 'av.start_up_id')
                        ->select("av.id","av.start_up_id",
                            DB::raw("concat(COALESCE(av.availability_date,'')) as start"),
                            //DB::raw("concat(COALESCE(av.availability_date,''),' ',COALESCE(av.avalibility_time_start,'')) as start"),
                            DB::raw("concat(COALESCE(DATE_FORMAT(av.avalibility_time_start,'%H:%i'),''),'-',COALESCE(DATE_FORMAT(av.avalibility_time_end,'%H:%i'),'')) as title"),
                            //DB::raw("concat(COALESCE(u.individual_name,''),' -',COALESCE(DATE_FORMAT(av.avalibility_time_start,'%H:%i'),''),'-',COALESCE(DATE_FORMAT(av.avalibility_time_end,'%H:%i'),'')) as title"),
                           "av.created_by","av.type as type",
                           DB::raw("if(av.availablity_status = '0', '0','1') as availablity_status"),
                           DB::raw("if((av.availability_date>='".$td."'), av.background,'#e4230d') as backgroundColor"),
                           "av.availability_date as apoointment_date","av.avalibility_time_start as apoointment_time")
                       ->where('av.availablity_status','=','1')
                       ->whereRaw($where)
                       ->whereBetween('av.availability_date', array($start, $end))
                       ->get();
            return $query;
        }
        public function getMentorDataPM($start,$end,$startup_id){
            $user_id = Session::get('userid');
            $td = date('Y-m-d ');
            $where = '1=1';
            //$where = 'av.created_by ='.$user_id;
            $query = DB::table("appointment_availability as av")
                        ->join('users as u', 'u.id', '=', 'av.created_by')
                        ->join('appointment_availability_relation as aar', 'aar.app_avail_id', '=', 'av.id')
                        ->select("av.id","av.start_up_id",
                            DB::raw("concat(COALESCE(av.availability_date,'')) as start"),
                            //DB::raw("concat(COALESCE(av.availability_date,''),' ',COALESCE(av.avalibility_time_start,'')) as start"),
                            DB::raw("concat(COALESCE(if(av.availablity_status = '0', '(Booked)',''),''),COALESCE(DATE_FORMAT(av.avalibility_time_start,'%H:%i'),''),'-',COALESCE(DATE_FORMAT(av.avalibility_time_end,'%H:%i'),'')) as title"),
                            //DB::raw("concat(COALESCE(if(av.availablity_status = '0', '(Booked)',''),''),COALESCE(u.individual_name,''),' -',COALESCE(DATE_FORMAT(av.avalibility_time_start,'%H:%i'),''),'-',COALESCE(DATE_FORMAT(av.avalibility_time_end,'%H:%i'),'')) as title"),
                           "av.created_by","av.type as type",
                            DB::raw("if(av.availablity_status = '0', '0','1') as availablity_status"),
                            //DB::raw("if((concat(COALESCE(av.availability_date,''),' ',COALESCE(av.avalibility_time_start,''))>'2018-05-25 01:40:41')), av.background,'#e4230d') as backgroundColor"),
                            DB::raw("if((av.availability_date>='".$td."'), av.background,'#e4230d') as backgroundColor"),
                           "av.availability_date as apoointment_date","av.avalibility_time_start as apoointment_time")
                       ->whereRaw($where)//#e4230d
                       ->where('av.availablity_status','=','1')
                       ->where('aar.startup_id','=',$startup_id)
                       ->whereBetween('av.availability_date', array($start, $end))
                       ->get();
            #dd(DB::getQueryLog());
            return $query;
        }
}
