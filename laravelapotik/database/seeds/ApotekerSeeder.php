<?php

use Illuminate\Database\Seeder;

class ApotekerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Apoteker::class)->create();
    }
}
