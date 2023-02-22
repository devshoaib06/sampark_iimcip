<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cohensive\Embed\Facades\Embed;

class MemberVideo extends Model
{
    protected $table = 'member_videos';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function getVideoHtmlAttribute()
    {
        
    	
        $embed = Embed::make($this->company_video)->parseUrl();

        if (!$embed)
            return '';

        $embed->setAttribute(['width' => 140,'height' => 70]);
        return $embed->getHtml();
    }

   

}
