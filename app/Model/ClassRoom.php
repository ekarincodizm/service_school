<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
	protected $table = 'CLASS_ROOM'; // Real Name Table
	protected $primaryKey = 'CR_ID';
	public $timestamps = false;
}
