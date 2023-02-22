<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Programme extends Authenticatable
{
    use SoftDeletes;

    public function programmsponsor(){
        return $this->hasOne(ProgrammeSponsor::class,'programme_id');
    }
}
