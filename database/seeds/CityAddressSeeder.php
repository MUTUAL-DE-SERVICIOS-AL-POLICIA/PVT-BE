<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\City;

class CityAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contacts = [
            [
                'city' => 'LA PAZ',
                'address' => 'Zona Sopocachi - Av. 6 de Agosto N° 2354',
                'coordinates' => [-16.5086, -68.12653],
                'prefix' => 2,
                'phones' => [2442270, 2445101, 2443506],
                'cellphones' => []
            ], [
                'city' => 'COCHABAMBA',
                'address' => 'Av. Melchor Urquidi - Zona Queru Queru Nº 1985',
                'coordinates' => [-17.369605, -66.149686],
                'prefix' => 4,
                'phones' => [4458935, 4242210],
                'cellphones' => [71782067]
            ], [
                'city' => 'SANTA CRUZ',
                'address' => 'Calle Ballivian Zona Este Nº 1229',
                'coordinates' => [-16.508697, -68.126546],
                'prefix' => 3,
                'phones' => [3337570],
                'cellphones' => [72134627]
            ], [
                'city' => 'CHUQUISACA',
                'address' => 'Calle Loa - Zona San Roque Nº 1070',
                'coordinates' => [-19.050645, -65.26576],
                'prefix' => 4,
                'phones' => [6452587],
                'cellphones' => [72875480]
            ], [
                'city' => 'ORURO',
                'address' => 'Av. 6 de Octubre entre oblitas y Belzu Zona Central Nº 4836',
                'coordinates' => [-17.959307, -67.109766],
                'prefix' => 5,
                'phones' => [246509],
                'cellphones' => [67200819]
            ], [
                'city' => 'POTOSI',
                'address' => 'Calle 1º de Abril - Zona Central Nº 615',
                'coordinates' => [-19.582756, -65.752783],
                'prefix' => 2,
                'phones' => [6226428],
                'cellphones' => [72405059]
            ], [
                'city' => 'BENI',
                'address' => 'Av. Cipriano Barece - Zona San Jose',
                'coordinates' => [-15.671311, -66.5176],
                'prefix' => 3,
                'phones' => [4622030],
                'cellphones' => [71133639]
            ], [
                'city' => 'TARIJA',
                'address' => 'Av Los Ceibos Esq. Cosio',
                'coordinates' => [-21.539301, -64.745244],
                'prefix' => 4,
                'phones' => [6644229],
                'cellphones' => [71862987]
            ], [
                'city' => 'PANDO',
                'address' => 'Av. 9 de Febrero ex SEGIP, 2do. Piso',
                'coordinates' => [-11.028398, -68.761157],
                'prefix' => 4,
                'phones' => [],
                'cellphones' => [67669749]
            ]
        ];

        foreach($contacts as $contact) {
            $city = City::whereName($contact['city'])->first();
            if ($city) {
                $city->update([
                    'company_address' => $contact['address'],
                    'latitude' => $contact['coordinates'][0],
                    'longitude' => $contact['coordinates'][1],
                    'phone_prefix' => $contact['prefix'],
                    'company_phones' => json_encode($contact['phones']),
                    'company_cellphones' => json_encode($contact['cellphones'])
                ], [
                    'timestamps' => false
                ]);
            }
        }
    }
}
