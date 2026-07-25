<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabangToko extends Model
{
    use HasFactory;

    protected $table = 'cabang_toko';

    protected $fillable = [
        'nama_toko',
        'latitude',
        'longitude',
    ];
}
