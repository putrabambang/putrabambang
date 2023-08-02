<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class penggilingan_detail extends Model
{
    use HasFactory;
    protected $table = 'penggilingan_detail';
    protected $primaryKey = 'id_penggilingan_detail';
    protected $guarded = [];
    public function item()
    {
        return $this->hasOne(Item::class, 'id_item', 'id_item');
    }
}