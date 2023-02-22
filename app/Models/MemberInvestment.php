<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MemberInvestment extends Model
{
    protected $table = 'member_investments';
    protected $primaryKey = "id";
    public $timestamps = false;

	 public function user()
    {
        return $this->belongsTo(User::class);
    } 

}