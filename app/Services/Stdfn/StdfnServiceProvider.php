<?php
namespace App\Services\Stdfn;

use Illuminate\Support\ServiceProvider;

class StdfnServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->singleton('Stdfn', function($app) {
			return new Stdfn();
		});
	}
	
}