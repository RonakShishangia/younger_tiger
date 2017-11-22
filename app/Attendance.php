<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public function employee(){
        return $this->belongsTo('App\Employee');
    }
    protected $fillable = ['start_time', 'end_time', 'employee_id', 'date'];
}
