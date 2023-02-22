<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cohensive\Embed\Facades\Embed;

class PostReply extends Model
{
    protected $table = 'post_reply';
    protected $primaryKey = "id";

    public function memberInfo() {
    	return $this->belongsTo('App\Models\Users', 'replied_by', 'id');
    }

    public function childReplies() {
        return $this->hasMany('App\Models\PostReply', 'replied_on', 'id')
        ->orderBy('id', 'desc');
    }

    public function postImages() {
        return $this->hasMany('App\Models\PostMedia', 'post_reply_id', 'id')->where('media_type', 'I')->orderBy('id', 'desc');
    }

    public function postVideos() {
        return $this->hasMany('App\Models\PostMedia', 'post_reply_id', 'id')->where('media_type', 'V')->orderBy('id', 'desc');
    }

    public function getVideoHtmlAttribute()
    {
        

        $embed = Embed::make($this->video_url)->parseUrl();

        if (!$embed)
            return '';

        $embed->setAttribute(['width' => 140,'height' => 70]);
        return $embed->getHtml();
    }
}
