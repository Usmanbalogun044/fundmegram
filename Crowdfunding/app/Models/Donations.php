<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donations extends Model
{

	protected $guarded = [];
	
	const CREATED_AT = 'date';
	const UPDATED_AT = null;

	public function campaigns()
	{
		return $this->belongsTo(Campaigns::class)->first();
	}

	public function rewards()
	{
		return $this->belongsTo(Rewards::class, 'rewards_id')->first();
	}
}
