<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $table = 'transfer';
    protected $primaryKey = 'id_transfer';
    protected $guarded = [];

    // Relasi one-to-many dengan TransferDetail
    public function details()
    {
        return $this->hasMany(TransferDetail::class, 'id_transfer', 'id_transfer');
    }
}
