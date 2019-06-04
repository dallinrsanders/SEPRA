<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workfile extends Model
{
	public function workspace(){
		return $this->belongsTo(Workspace::class);
	}
	public function upload(){
		return $this->belongsTo(Upload::class);
	}
    //
}
