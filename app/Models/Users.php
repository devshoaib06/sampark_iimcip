<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Spatie\Permission\Traits\HasRoles;
use Cohensive\Embed\Facades\Embed;

class Users extends Authenticatable
{
    //use HasRoles;

    protected $table = 'users';
    protected $primaryKey = "id";

    protected $guard_name = 'web';

    public function allIndustryIds()
    {
        return $this->hasMany('App\Models\MemberBusiness', 'member_id', 'id')
            ->orderBy('id', 'desc');
    }

    public function startUpCount()
    {
        return $this->hasMany('App\Models\MemberPmRel', 'member_id', 'id');
    }
    public function startUpList()
    {
        return $this->hasMany('App\Models\MemberPmRel', 'member_id', 'id');
    }


    /**
     * The roles that belong to the Users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function getServiceLocation()
    {
        return $this->belongsToMany(Location::class, 'member_locations', 'member_id', 'location_id');
    }
    public function getProgramme()
    {
        return $this->belongsToMany(Programme::class, 'startup_programmes', 'startup_id', 'programme_id');
    }

    public function getMentor()
    {
        return $this->belongsToMany(Users::class, 'member_mentor_rel', 'member_id', 'mentor_id');
    }

    public function getMemberService()
    {
        return $this->belongsTo(MemberService::class,  'member_id', 'id');
    }


    public function getCompanyType()
    {
        return $this->belongsTo(CompanyType::class,  'company_type', 'id');
        // return $this->hasOne('App\Models\CompanyType', 'company_id', 'id');
    }

    public function getProgramme1()
    {
        return $this->belongsTo('App\Models\Programme', 'programme_id', 'id');
    }



    public function allInvestmentIds()
    {
        return $this->hasOne(MemberInvestment::class, 'member_id', 'id')
            ->orderBy('id', 'desc');
    }


    public function getVideoHtmlAttribute()
    {


        $embed = Embed::make($this->speech)->parseUrl();

        if (!$embed)
            return '';

        $embed->setAttribute(['width' => 140, 'height' => 70]);
        return $embed->getHtml();
    }

    public function startupprogramme()
    {
        return $this->hasOne(StartupProgramme::class, 'startup_id');
    }

    public function industryInfo()
    {
        return $this->belongsTo('App\Models\MemberPmRel', 'member_id', 'pm_id');
    }
}
