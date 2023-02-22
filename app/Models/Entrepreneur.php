<?php
namespace App\Models;

/*use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;*/

use Illuminate\Database\Eloquent\Model;

class Entrepreneur extends Model {

	protected $table = 'entrepreneurs';


	public function user()
		{
			    return $this->belongsTo('User', 'id');
		}

  public function categoryuser()
    {
        return $this->hasMany('CategoryUser','user_id');
    }

      public function promoters()
    {
        return $this->hasMany('Promoter','user_id');
    }

      public function products()
    {
        return $this->hasMany('Product','user_id');
    }
	
	public function startupstage()
	{
		return $this->belongsTo('StartupStage', 'start_up_stage');
	}
	
	public function structureofcompnay()
	{
		return $this->belongsTo('StructureofCompany', 'structure_of_company');
	}

}
