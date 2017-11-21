<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function todolists()
    {
        return $this->belongsToMany('App\Todolist');
    }
}
