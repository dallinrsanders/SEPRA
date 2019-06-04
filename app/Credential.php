<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{
	public function host(){
		return $this->belongsTo(Host::class);
	}
    //
}
