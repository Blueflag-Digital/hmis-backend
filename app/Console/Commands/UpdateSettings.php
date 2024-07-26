<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-settings';

    /**
     * The console command description.
     *
     * @var string
     */
     protected $description = 'Update application settings from the configuration file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $settings = config('app_settings');

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['slug' => $setting['slug']],
                [
                    'name' => $setting['name'],
                    'value' => $setting['value'],
                    'description' => $setting['description'],
                    'created_at' => $setting['created_at'],
                    'updated_at' => now(),
                ]
            );
        }

        $this->info('Settings have been updated successfully.');
    }
}
