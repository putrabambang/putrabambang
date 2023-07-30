<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferDetail extends Model
{
    use HasFactory;
    
    protected $table = 'transfer_detail';
    protected $primaryKey = 'id_transferdetail';
    protected $guarded = [];

    public function Barang()
    {
        return $this->hasOne(Barang::class, 'id_barang', 'id_barang');
    }
}
