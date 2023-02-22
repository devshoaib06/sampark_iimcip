<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberBusiness extends Model
{
    protected $table = 'member_business';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function industryInfo() {
        return $this->belongsTo('App\Models\IndustryCategories', 'industry_category_id', 'id');
    }

}
