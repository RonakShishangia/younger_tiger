<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model {

	use SoftDeletes;

	public function employee () {
		return $this->belongsTo('App\Employee');
	}
	/**
	  * The attributes that are mass assignable.
	  * @var array
	  */
	protected $fillable = [
		'employee_id','date','half_day'
	];
	/**
	 * The attributes that should be use for soft delete.
	 * @var array
	 */
	protected $dates = ['deleted_at'];
}