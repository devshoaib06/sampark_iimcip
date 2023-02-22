<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Spatie\Permission\Traits\HasRoles;
use Cohensive\Embed\Facades\Embed;
class LegalStatus extends Authenticatable
{
	//use HasRoles;

    protected $table = 'legal_status';
    protected $primaryKey = "id";

    protected $guard_name = 'web';
 

}
