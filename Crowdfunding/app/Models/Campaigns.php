<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaigns extends Model
{

	protected $guarded = [];
	
	const CREATED_AT = 'date';
	const UPDATED_AT = null;

	public function user()
	{
		return $this->belongsTo(User::class)->first();
	}

	public function withdrawals()
	{
		return $this->hasMany(Withdrawals::class)->first();
	}

	public function likes()
	{
		return $this->hasMany(Like::class)->where('status', '1');
	}

	public function donations()
	{
		return $this->hasMany(Donations::class)->where('approved', '1');
	}

	public function updates()
	{
		return $this->hasMany(Updates::class);
	}

	public function category()
	{
		return $this->belongsTo(Categories::class, 'categories_id');
	}

	public function rewards()
	{
		return $this->hasMany(Rewards::class)->orderBy('amount');
	}
}
