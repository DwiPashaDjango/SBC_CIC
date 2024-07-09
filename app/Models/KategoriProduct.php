<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriProduct extends Model
{
    use HasFactory;
    protected $tabel = 'kategori_products';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
