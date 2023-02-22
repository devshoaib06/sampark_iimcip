<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Spatie\Permission\Traits\HasRoles;
use Cohensive\Embed\Facades\Embed;
class IndustryExpertise extends Authenticatable
{
	//use HasRoles;

    protected $table = 'industry_expertise';
    protected $primaryKey = "id";

    protected $guard_name = 'web';
 
}
