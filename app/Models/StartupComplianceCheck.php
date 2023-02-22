<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StartupComplianceCheck extends Model
{
	protected $guarded = [];
   
	protected $table = 'startup_compliance_checks';

    public function getFinancialYear(){
		 return $this->hasOne('App\Models\FinancialYear','id','financial_year');
	} 
}
