<?php

use App\Console\Commands\Currencies;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\UpdateSettings;
use App\Console\Commands\UpdateLeaves;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Artisan::command('settings:update', function () {
    Artisan::call(UpdateSettings::class);
})->describe('Update application settings from the configuration file');

Artisan::command('currencies:update', function () {
    Artisan::call(Currencies::class);
})->describe('Update application settings from the configuration file');

Artisan::command('leaves:update', function () {
    Artisan::call(UpdateLeaves::class);
})->describe('Update application leaves from the configuration file');






