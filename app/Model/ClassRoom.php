<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Subject;

class ClassRoom extends Model
{
	protected $table = 'CLASS_ROOM'; // Real Name Table
	protected $primaryKey = 'CR_ID';
	public $timestamps = false;

	public function subject() {
		return $this->belongsTo(Subject::class,'SUBJECT_ID');
	}
	
}
