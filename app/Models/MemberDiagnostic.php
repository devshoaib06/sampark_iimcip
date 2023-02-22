<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberDiagnostic extends Model
{
    protected $table = 'member_diagnostics';
    protected $guarded = [];

    public function getMentor()
    {
        return $this->belongsTo(Users::class,  'mentor_id', 'id');
    }
    public function getIncubatee()
    {
        return $this->belongsTo(Users::class,  'incubatee_id', 'id');
    }
}
