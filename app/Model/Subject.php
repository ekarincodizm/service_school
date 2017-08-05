<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
	protected $table = 'SUBJECT'; // Real Name Table
	protected $primaryKey = 'SUBJECT_ID';
	public $timestamps = false;
}
