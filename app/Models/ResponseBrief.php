<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ResponseBrief extends Model
{
	protected $guarded = [];

	protected $table = 'response_briefs';


	public function getMentorName()
	{
		return $this->belongsTo('App\Models\Users', 'mentor_id', 'id');
	}

	public function getIncubatee()
	{
		return $this->belongsTo('App\Models\Users', 'incubatee_id', 'id');
	}
	public function getDiagnostic()
	{
		return $this->belongsTo('App\Models\MemberDiagnostic', 'diagnostic_id', 'id');
	}

	public function getParameter()
	{
		return $this->belongsTo('App\Models\Parameter', 'parameter_id', 'id');
	}

	public function getCompanyType()
	{
		return $this->belongsTo('App\Models\CompanyType', 'company_id', 'id');
	}

	public function getProgramme()
	{
		return $this->belongsTo('App\Models\Programme', 'programme_id', 'id');
	}

	public function getResponseDetails()
	{
		return $this->hasMany(ResponseDetail::class, 'response_id', 'id');
	}
}
