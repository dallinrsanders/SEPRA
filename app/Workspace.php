<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
	public function host(){
		return $this->hasMany(Host::class);
	}
	public function workfile(){
		return $this->hasMany(Workfile::class);
	}
	public function workspacemethodology(){
		return $this->hasMany(Workspacemethodology::class);
	}
    //
}
