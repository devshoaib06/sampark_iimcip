<?php

namespace App\Models;

 
use Illuminate\Foundation\Auth\User as Authenticatable;
 
class CompanyType extends Authenticatable
{
 
    protected $table = 'company_type';
    protected $primaryKey = "id";

    protected $guard_name = 'web';

   

}
