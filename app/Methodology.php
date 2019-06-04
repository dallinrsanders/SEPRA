<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Methodology extends Model
{
	public function msection(){
		return $this->hasMany(Msection::class);
	}
	protected $table = "methodologies";
    //
}
