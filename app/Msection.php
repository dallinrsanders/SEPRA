<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Msection extends Model
{
	
	public function methodology(){
		return $this->belongsTo(Methodology::class);
	}
	public function mquestion(){
		return $this->hasMany(Mquestion::class);
	}
    //
}
