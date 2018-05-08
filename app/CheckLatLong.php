<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheckLatLong extends Model
{
    protected $fillable = ['name', 'latitude', 'longitude'];
}
