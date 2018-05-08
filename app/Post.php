<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function tag(){
        return $this->belongsToMany('App\Tag');
    }
    protected $fillable = ['name'];
}
