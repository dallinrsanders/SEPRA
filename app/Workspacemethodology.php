<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workspacemethodology extends Model
{
	public function workspace(){
		return $this->belongsTo(Workspace::class);
	}
	public function methodology(){
		return $this->belongsTo(Methodology::class);
	}
	public function manswer(){
		return $this->hasMany(Manswer::class);
	}
	protected $table = "workspacemethodologies";
    //
}
