<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Subject;
use App\Model\RoomType;

class ClassRoom extends Model
{
	protected $table = 'CLASS_ROOM'; // Real Name Table
	protected $primaryKey = 'CR_ID';
	public $timestamps = false;

	public function subject() {
		return $this->belongsTo(Subject::class,'SUBJECT_ID');
	}

	public function roomType() {
		return $this->belongsTo(RoomType::class,'RT_ID');
	}
	
}
