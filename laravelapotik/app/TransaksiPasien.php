<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransaksiPasien extends Model
{
    protected $table = 'transaksi_pasiens';

    protected $guarded = ['id'];
}
