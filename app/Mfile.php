<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mfile extends Model
{
	public function manswer(){
		return $this->belongsTo(Manswer::class);
	}
	public function upload(){
		return $this->belongsTo(Upload::class);
	}
    //
}
