<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Subject;

class BillDetail extends Model
{
	protected $table = 'BILL_DETAIL'; // Real Name Table
	protected $primaryKey = 'BD_ID';
	public $timestamps = false;

	public function getSubjectAttribute() {
		return Subject::find($this->attributes['SUBJECT_ID']);

		//return $this->belongsTo(Subject::class,'SUBJECT_ID');
	}
}
