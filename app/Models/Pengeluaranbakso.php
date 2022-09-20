<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaranbakso extends Model
{
    use HasFactory;

    protected $table = 'pengeluaranbakso';
    protected $primaryKey = 'id_pengeluaran';
    protected $guarded = [];
}
