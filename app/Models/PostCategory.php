<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $table = 'post_categories';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function categoryInfo() {
    	return $this->belongsTo('App\Models\Categories', 'category_id', 'id');
    }
}
