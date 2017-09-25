<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\StudentAccount;
use App\Model\BillDetail;

class Bill extends Model
{
	protected $table = 'BILL'; // Real Name Table
	protected $primaryKey = 'BILL_ID';
	public $timestamps = false;

	const BILL_STATUS_PAID = "P";
	const BILL_STATUS_WAIT_TO_PAID = "W";
	const BILL_STATUS_CANCLE = "C";

	public function studentAccount() {
		return $this->belongsTo(StudentAccount::class,'SA_ID');
	}

	public function billDetail() {
		return $this->hasMany(BillDetail::class,'BILL_ID');
	}

}
