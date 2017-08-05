<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StudentAccount extends Model
{
	protected $table = 'STUDENT_ACCOUNT'; // Real Name Table
	protected $primaryKey = 'SA_ID';
	public $timestamps = false;
}
