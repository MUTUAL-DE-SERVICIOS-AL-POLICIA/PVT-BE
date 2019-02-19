<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\PaymentType;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payment_types = [
            ['name' => 'Efectivo'],
            ['name' => 'Deposito Bancario']
        ];
        foreach ($payment_types as $payment_type) {
            PaymentType::create($payment_type);
        }
    }
}