<?php

namespace Database\Seeders;

use App\Models\ProvinceOngkir;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProvinceOngkirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $provinces = Http::withOptions(['verify' => false,])->withHeaders([
            'key' => env('RAJAONGKIR_API_KEY')
        ])->get('https://api.rajaongkir.com/starter/province')->json()['rajaongkir']['results'];

        foreach ($provinces as $province) {
            ProvinceOngkir::create([
                'name'        => $province['province'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
