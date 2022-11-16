<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(AdminSeeder::class);
        $this->call(ResepsionistSeeder::class);
        $this->call(SpesialisSeeder::class);
        $this->call(DokterSeeder::class);
        $this->call(ApotekerSeeder::class);

        Model::reguard();
    }
}
