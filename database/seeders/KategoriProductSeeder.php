<?php

namespace Database\Seeders;

use App\Models\KategoriProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Makanan Berat',
            'Makanan Ringan',
            'Minuman'
        ];

        foreach ($data as $value) {
            KategoriProduct::create(['name' => $value]);
        }
    }
}
