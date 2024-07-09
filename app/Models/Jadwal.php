<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;
    protected $table = 'jadwals';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function stand()
    {
        return $this->belongsTo(Stand::class, 'stands_id', 'id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriProduct::class, 'kategori_products_id', 'id');
    }
}
