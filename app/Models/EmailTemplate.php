<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Spatie\Permission\Traits\HasRoles;

class EmailTemplate extends Authenticatable
{
	//use HasRoles;

    protected $table = 'email_templates';
    protected $primaryKey = "id";

    protected $guard_name = 'web';
}
