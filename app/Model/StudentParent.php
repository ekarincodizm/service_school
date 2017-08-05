<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StudentParent extends Model
{
	protected $table = 'STUDENT_PARENT'; // Real Name Table
	protected $primaryKey = 'SP_ID';
	public $timestamps = false;
}
