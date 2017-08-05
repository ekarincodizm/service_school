<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	protected $table = 'USER'; // Real Name Table
	protected $primaryKey = 'USER_ID';
	public $timestamps = false;
}
