<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BillDetail extends Model
{
	protected $table = 'BILL_DETAIL'; // Real Name Table
	protected $primaryKey = 'BD_ID';
	public $timestamps = false;
}
