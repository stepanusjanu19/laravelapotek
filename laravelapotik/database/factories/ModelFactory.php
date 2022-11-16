<?php
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

// factories admin
$factory->define(App\Admin::class, function(Faker\Generator $faker) {
	return [
		'username' => 'admin',
		'level' => 'admin',
		'password' => bcrypt('admin')
	];
});

// factories resepsionist
$factory->define(App\Resepsionist::class, function(Faker\Generator $faker) {
	return [
		'id' => createId('Resepsionist', 'resepsionist'),
    	'username' => 'resepsionist',
    	'password' => bcrypt('resepsionist'),
    	'nama' => $faker->name,
    	'alamat' => $faker->address,
    	'tgl_lahir' => $faker->date($format = 'Y-m-d', $max = 'now'),
    	'level' => 'resepsionist',
        'photo' => '',
	];
});

// factories dokter
$factory->define(App\Dokter::class, function(Faker\Generator $faker) {
	$spesialis = App\Speasialis::pluck('id')->all();
	return [
		'id' => createId('Dokter', 'dokter'),
		'username' => 'dokter',
    	'password' => bcrypt('dokter'),
    	'nama' => $faker->name,
    	'alamat' => $faker->address,
    	'tgl_lahir' => $faker->date($format = 'Y-m-d', $max = 'now'),
    	'level' => 'dokter',
    	'spesialis_id' => $faker->randomElement($spesialis),
        'photo' => '',
	];
});

// factories spsialis
$factory->define(App\Speasialis::class, function(Faker\Generator $faker, $spesialis)
{
	return [
		'spesialis' => $spesialis['spesialis']
	];
});

function CreateId($model, $type) {
	$model = 'App\\'.$model;
	$id = $model::select('id')->get()->last();
        if ($id == null) {
            $id = 1;
        }
    $id  = substr($id['id'], 4);
    $id = (int) $id;
    $id += 1;

    switch ($type) {
    	case 'resepsionist':
    		$id  = "RS" . str_pad($id, 3, "0", STR_PAD_LEFT);
    		break;
    	case 'dokter':
    		$id  = "DK" . str_pad($id, 3, "0", STR_PAD_LEFT);
    		break;
    	case 'apoteker':
    		$id  = "AP" . str_pad($id, 3, "0", STR_PAD_LEFT);
    		break;
    	default:
    		return dd('error');
    		break;
    }

    return $id;
}

