<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyLocation extends Model
{
    protected $fillable = ['company_name', 'company_latitude', 'company_longitude'];
}
