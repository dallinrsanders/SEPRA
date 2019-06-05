<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manswer extends Model
{
	public function mquestion(){
		return $this->belongsTo(Mquestion::class);
	}
	public function workspacemethodology(){
		return $this->belongsTo(Workspacemethodology::class);
	}
	public function mfile(){
		return $this->hasMany(Mfile::class);
	}
    //
}
