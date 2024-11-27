<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Currencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:currencies';

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
        $currencies = config('app_currencies');

        foreach ($currencies as $currency) {
            DB::table('currencies')->updateOrInsert(
                ['code' => $currency['code']],
                [
                    'symbol' => $currency['symbol'],
                    'name' => $currency['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->info('Currencies have been updated successfully.');
    }
}
