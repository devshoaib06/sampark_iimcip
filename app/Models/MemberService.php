<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cohensive\Embed\Facades\Embed;

class MemberService extends Model
{
    protected $table = 'member_services';
    protected $primaryKey = "id";
    public $timestamps = false;

    public function getVideoHtmlAttribute()
    {
        
    	
        $embed = Embed::make($this->buisness_video)->parseUrl();

        if (!$embed)
            return '';

        $embed->setAttribute(['width' => 140,'height' => 70]);
        return $embed->getHtml();
    }

    // public function getFinance() {
    //     return $this->belongsTo('App\Models\StartupMonthlySale', 'product_id', 'id');
    // }

   

}
