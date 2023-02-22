<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Parameter;

class Question extends Model
{
	protected $guarded = [];
   
	protected $table = 'question_masters';

    public function parameters(){
        return $this->belongsTo(Parameter::class);
    }
}
