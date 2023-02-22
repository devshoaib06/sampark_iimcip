<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StartupImpact extends Model
{
	protected $guarded = [];
   
	protected $table = 'startup_impacts';

	public function getFinancialYear(){
		return $this->hasOne('App\Models\FinancialYear','id','financial_year');
   } 

   public function getFinancialMonth(){
		return $this->hasOne('App\Models\FinancialMonth','id','month');
   } 

    
}
