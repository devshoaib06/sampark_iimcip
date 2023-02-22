<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Spatie\Permission\Traits\HasRoles;
use Cohensive\Embed\Facades\Embed;
class Message extends Authenticatable
{
	//use HasRoles;

    protected $table = 'message';
    protected $primaryKey = "id";

    protected $guard_name = 'web';
  
}
