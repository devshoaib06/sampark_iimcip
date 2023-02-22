<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Cohensive\Embed\Facades\Embed;


class Posts extends Model
{
    protected $table = 'post_master';
    protected $primaryKey = "id";
	

    public function memberInfo() {
    	return $this->belongsTo('App\Models\Users', 'member_id', 'id');
    }

    public function postIndistries() {
    	return $this->hasMany('App\Models\PostIndustry', 'post_id', 'id');
    }
    public function postCategories() {
        return $this->hasMany('App\Models\PostCategory', 'post_id', 'id');
    }

    public function postReplies() {
        return $this->hasMany('App\Models\PostReply', 'post_id', 'id')
        ->where('replied_on', 0)->orderBy('id', 'desc');
    }

    public function totalPostComment() {
        return $this->hasMany('App\Models\PostReply', 'post_id', 'id');
    }

    public function getVideoHtmlAttribute()
    {
        

        $embed = Embed::make($this->video_link)->parseUrl();

        if (!$embed)
            return '';

        $embed->setAttribute(['width' => 140,'height' => 70]);
        return $embed->getHtml();
    }
}
