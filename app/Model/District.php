<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
	protected $table = 'DISTRICT'; // Real Name Table
	protected $primaryKey = 'DISTRICT_ID';
	public $timestamps = false;
}
