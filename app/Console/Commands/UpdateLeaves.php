<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateLeaves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-leaves';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $settings = config('app_leave_types');

        foreach ($settings as $setting) {
            DB::table('leave_types')->updateOrInsert(
                ['slug' => $setting['slug']],
                [
                    'name' => $setting['name'],
                    'description' => $setting['description'],
                    'created_at' => $setting['created_at'],
                    'updated_at' => now(),
                ]
            );
        }

        $this->info('Leave types have been updated successfully.');
    }
}
