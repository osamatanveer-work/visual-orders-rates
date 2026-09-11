<?php

namespace Database\Seeders;

use App\Models\Carrier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarriersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carriers = [
            [
                'name' => 'Fedex',
                'slug' => 'fedex',
                'description' => 'Fedex'
            ],
            [
                'name' => 'UPS',
                'slug' => 'ups',
                'description' => 'UPS System'
            ],
            [
                'name' => 'USPS',
                'slug' => 'usps',
                'description' => 'USPS System'
            ]
        ];

        foreach ($carriers as $carrier) {
            Carrier::create($carrier);
        }
    }
}
