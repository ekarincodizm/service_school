<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\ClassRoom;

class BillDetail extends Model
{
	protected $table = 'BILL_DETAIL'; // Real Name Table
	protected $primaryKey = 'BD_ID';
	public $timestamps = false;

	public function classRoom() {
		return $this->belongsTo(ClassRoom::class,'CR_ID');
	}
}
