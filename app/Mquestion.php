<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mquestion extends Model
{
	
	public function msection(){
		return $this->belongsTo(Msection::class);
	}
	public function manswer(){
		return $this->hasMany(Manswer::class);
	}
    //
}
