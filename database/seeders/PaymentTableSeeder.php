<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $payments = [
            ['receipt_no' => 'HospV001', 'hospital_id' => '1', 'amount' => '30000'],
            ['receipt_no' => 'HospV002', 'hospital_id' => '2', 'amount' => '90000'],
            ['receipt_no' => 'HospV003', 'hospital_id' => '3', 'amount' => '20000'],
            ['receipt_no' => 'HospV004', 'hospital_id' => '2', 'amount' => '25000'],
            ['receipt_no' => 'HospV005', 'hospital_id' => '1', 'amount' => '70000'],
        ];
        foreach ($payments as $key => $payment) {
             Payment::create([
                'amount' => $payment['amount'],
                'receipt_no' => $payment['receipt_no'],
                'hospital_id' => $payment['hospital_id'],
             ]);
        }

    }
}
