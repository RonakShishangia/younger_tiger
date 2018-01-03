<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model {

	use SoftDeletes;

	public function user () {
		return $this->belongsTo('App\User');
	}
	public function leave_days () {
		return $this->hasMany('App\Leave_sub');
	}
	public function employee () {
		return $this->hasMany('App\Employee');
	}
	public function department()
    {
        return $this->belongsTo('App\Department');
    }
	/**
	  * The attributes that are mass assignable.
	  * @var array
	  */
	// protected $fillable = [
	// 	'user_id','date','half_day', 'from_date', 'to_date'
	// ];
	/**
	 * The attributes that should be use for soft delete.
	 * @var array
	 */
	protected $dates = ['deleted_at'];
}