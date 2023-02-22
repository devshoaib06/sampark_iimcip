<?php

use Illuminate\Database\Eloquent\Model;

class TaskCategory extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'task_categories';

	public function parent()
	{
		return $this->belongsTo('Category', 'parent_id');
	}

	public function children()
	{
		return $this->hasMany('Category', 'parent_id', 'id');
	}
}
