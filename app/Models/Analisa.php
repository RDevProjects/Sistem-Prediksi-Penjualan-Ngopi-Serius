<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analisa extends Model
{
    protected $table = 'analisa';
    protected $fillable = ['bulan', 'tahun', 'jumlah'];
}
