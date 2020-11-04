<?php

use App\Payout;
use Illuminate\Database\Seeder;

class PayoutSeeder extends Seeder
{
    public function run()
    {
        factory(Payout::class, 25)->create();
    }
}
