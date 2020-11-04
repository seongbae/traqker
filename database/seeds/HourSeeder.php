<?php

use App\Hour;
use Illuminate\Database\Seeder;

class HourSeeder extends Seeder
{
    public function run()
    {
        factory(Hour::class, 25)->create();
    }
}
