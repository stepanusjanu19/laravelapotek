<?php

use Illuminate\Database\Seeder;

class SpesialisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$spesialis = array('gigi', 'mata', 'umum', 'anak');

    	for ($i=0; $i < count($spesialis); $i++) {
        	factory(App\Speasialis::class)->create(['spesialis' => $spesialis[$i]]);
    	}
    }
}
