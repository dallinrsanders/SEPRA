<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Host extends Model
{
	public function workspace(){
		return $this->belongsTo(Workspace::class);
	}
	public function service(){
		return $this->hasMany(Service::class);
	}
	public function credential(){
		return $this->hasMany(Credential::class);
	}
	public function hostfile(){
		return $this->hasMany(Hostfile::class);
	}
    //
}
