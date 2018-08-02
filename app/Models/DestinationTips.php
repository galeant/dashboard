<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DestinationTips extends Model
{
	use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $table = 'destination_tips';
    protected $hidden = 'id';
    protected $fillable = [
        'destination_id',
        'question_id',
        'answer'
    ];
}
