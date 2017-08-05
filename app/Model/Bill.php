<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
	protected $table = 'BILL'; // Real Name Table
	protected $primaryKey = 'BILL_ID';
	public $timestamps = false;
}
