<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberPmRel extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	public $timestamps = false;
	public $fillable = ['member_id', 'pm_id'];
	protected $table = 'member_pm_rel';

	public function allUsers()
	{
		return $this->hasMany('App\Models\Users', 'member_company', 'id')
			->orderBy('id', 'desc');
	}
}
