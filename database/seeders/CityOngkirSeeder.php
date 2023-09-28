<?php

namespace Database\Seeders;

use App\Models\CityOngkir;
use App\Models\ProvinceOngkir;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CityOngkirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $provinces = ProvinceOngkir::all();

        foreach ($provinces as $province) {
            $cities = Http::withOptions(['verify' => false,])->withHeaders([
                'key' => env('RAJAONGKIR_API_KEY')
            ])->get('https://api.rajaongkir.com/starter/city?province=' . $province->id)->json()['rajaongkir']['results'];

            $insert_city = [];

            foreach ($cities as $city) {

                $data = [
                    'province_id'   => $province->id,
                    'type'          => $city['type'],
                    'name'          => $city['type'] . ' ' . $city['city_name'],
                    'postal_code'   => $city['postal_code'],
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];

                $insert_city[] = $data;
            }

            $insert_city = collect($insert_city);

            $city_chunks = $insert_city->chunk(100);

            foreach ($city_chunks as $chunk) {
                CityOngkir::insert($chunk->toArray());
            }
        }
    }
}
