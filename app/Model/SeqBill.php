<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SeqBill extends Model
{
	protected $table = 'SEQ_BILL'; // Real Name Table
	protected $primaryKey = 'ID';
	public $timestamps = false;
}
