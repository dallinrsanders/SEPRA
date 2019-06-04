<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
	public function host(){
		return $this->belongsTo(Host::class);
	}
	public function vulnerability(){
		return $this->hasMany(Vulnerability::class);
	}
    //
}
