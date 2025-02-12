<?php

namespace App\Providers;

use App\Models\AdminSettings;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot()
	{
		try {
			\DB::connection()->getPdo();
		} catch (\Exception $e) {
			return false;
		}

		view()->share('settings', AdminSettings::first());
	}
}
