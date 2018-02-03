<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FutureSchool extends Model
{
	protected $table = 'FUTURE_SCHOOL'; // Real Name Table
	protected $primaryKey = 'FUTURE_SCHOOL_CODE';
	public $timestamps = false;
}
