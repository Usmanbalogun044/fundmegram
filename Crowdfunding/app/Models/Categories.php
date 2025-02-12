<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{

	protected $guarded = [];
	public $timestamps = false;

	public function campaigns()
	{
		return $this->hasMany(Campaigns::class)->where('status', 'active')->where('finalized', '0');
	}
}
