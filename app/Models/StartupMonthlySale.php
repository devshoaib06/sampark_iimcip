<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StartupMonthlySale extends Model
{
	protected $guarded = [];
   
	protected $table = 'startup_monthly_sales';


	public function getProducts() {
        return $this->belongsTo('App\Models\MemberService', 'product_id', 'id');
    }

	public function getFinancialYear(){
		 return $this->hasOne('App\Models\FinancialYear','id','financial_year');
	} 

	public function getFinancialMonth(){
		 return $this->hasOne('App\Models\FinancialMonth','id','month');
	} 
    
}