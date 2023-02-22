<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    protected $guarded = [];

    /**
     * Get all of the comments for the Parameter
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getAllQuestions()
    {
        return $this->hasMany(Question::class, 'parameter_id', 'id');
    }

    /**
     * Get the getResponseBrief that owns the Parameter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function getResponseBrief()
    {
        return $this->hasMany(ResponseBrief::class, 'parameter_id', 'id');
    }


    public function getResponseBriefData()
    {
        return $this->hasOne(ResponseBrief::class, 'parameter_id', 'id');
    }
}
