<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hostfile extends Model
{
	public function host(){
		return $this->belongsTo(Host::class);
	}
	public function upload(){
		return $this->belongsTo(Upload::class);
	}
    //
}
