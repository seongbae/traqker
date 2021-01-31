<?php

use App\DeviceToken;
use Illuminate\Database\Seeder;

class DeviceTokenSeeder extends Seeder
{
    public function run()
    {
        factory(DeviceToken::class, 25)->create();
    }
}
