<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
	protected $table = 'ROOM_TYPE'; // Real Name Table
	protected $primaryKey = 'RT_ID';
	public $timestamps = false;
}
