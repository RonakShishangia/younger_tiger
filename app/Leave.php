<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model {

	use SoftDeletes;

	public function user () {
		return $this->belongsTo('App\User');
	}
	/**
	  * The attributes that are mass assignable.
	  * @var array
	  */
	protected $fillable = [
		'user_id','date','half_day', 'from_date', 'to_date'
	];
	/**
	 * The attributes that should be use for soft delete.
	 * @var array
	 */
	protected $dates = ['deleted_at'];
}