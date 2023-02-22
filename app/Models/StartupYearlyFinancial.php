<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StartupYearlyFinancial extends Model
{
	protected $guarded = [];
   
	protected $table = 'startup_yearly_financials';

	public function getFinancialYear(){
		 return $this->hasOne('App\Models\FinancialYear','id','financial_year');
	} 

    
}