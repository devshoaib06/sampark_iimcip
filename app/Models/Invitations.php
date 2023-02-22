<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Spatie\Permission\Traits\HasRoles;
use Cohensive\Embed\Facades\Embed;
class Invitations extends Authenticatable
{
	//use HasRoles;

    protected $table = 'invitations';
    protected $primaryKey = "id";

    protected $guard_name = 'web';

    public function allIndustryIds() {
        return $this->hasMany('App\Models\MemberBusiness', 'member_id', 'id')
        ->orderBy('id', 'desc');
    }

    public function getVideoHtmlAttribute()
    { 
        $embed = Embed::make($this->speech)->parseUrl();

        if (!$embed)
            return '';

        $embed->setAttribute(['width' => 140,'height' => 70]);
        return $embed->getHtml();
    }


}
