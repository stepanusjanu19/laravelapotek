<?php

use App\Admin;
use App\Apoteker;
use App\Dokter;
use App\Resepsionist;
use App\Speasialis;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Admin::class)->create();
    }
}
