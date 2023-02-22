<?php

use Illuminate\Database\Eloquent\Model;


class StartUpPmRel extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	public $timestamps = false;
	public $fillable = ['member_id', 'pm_id'];
	protected $table = 'member_pm_rel';
}
