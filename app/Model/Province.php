<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
	protected $table = 'PROVINCE'; // Real Name Table
	protected $primaryKey = 'PROVINCE_ID';
	public $timestamps = false;
}
