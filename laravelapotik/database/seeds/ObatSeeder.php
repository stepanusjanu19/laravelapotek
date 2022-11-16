<?php

use Illuminate\Database\Seeder;

class ObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$obat = array('mixagrip', 'bodrexin', 'oskadon', 'panadol');

    	foreach ($obat as $key => $value) {
	        factory();
    	}
    }
}
