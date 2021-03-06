<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public function department()
    {
        return $this->belongsTo('App\Department');
    }
    // public function user()
    // {
    //     return $this->belongsTo('App\User');
    // }
    
    protected $fillable = ['user_id', 'name', 'dob', 'avatar', 'department_id', 'email', 'password'];
}
