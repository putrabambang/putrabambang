<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class penggilingan extends Model
{
    use HasFactory;

    protected $table = 'penggilingan';
    protected $primaryKey = 'id_penggilingan';
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }
}
