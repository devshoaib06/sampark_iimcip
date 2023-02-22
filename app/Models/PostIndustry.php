<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostIndustry extends Model
{
    protected $table = 'post_industry';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function industryInfo() {
    	return $this->belongsTo('App\Models\IndustryCategories', 'industry_category_id', 'id');
    }
}
