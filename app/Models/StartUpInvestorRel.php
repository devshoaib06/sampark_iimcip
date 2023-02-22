<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class StartUpInvestorRel extends Eloquent  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
        public $timestamps = false;
        public $fillable = ['menber_id','mentor_id'];
	protected $table = 'member_mentor_rel';
			
			
}
