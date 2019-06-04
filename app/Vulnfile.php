<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vulnfile extends Model
{
	public function vulnerability(){
		return $this->belongsTo(Vulnerability::class);
	}
	public function upload(){
		return $this->belongsTo(Upload::class);
	}
    //
}
