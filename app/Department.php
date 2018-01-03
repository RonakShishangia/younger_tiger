<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public function employee () {
		return $this->hasOne('App\Employee');
	}
    protected $fillable = ['name', 'description'];
}
