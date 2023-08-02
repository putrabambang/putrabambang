<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Barang extends Model
{
    use HasFactory;
    protected $table = 'barang';
    protected $primaryKey ='id_barang';
    protected $guarded = [];
    public function kategori()
    {
        return $this->hasOne(kategori::class, 'id_kategori', 'id_kategori');
    }
}