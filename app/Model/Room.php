<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
	protected $table = 'ROOM'; // Real Name Table
	protected $primaryKey = 'ROOM_ID';
	public $timestamps = false;
}
