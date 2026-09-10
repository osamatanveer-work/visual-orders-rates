<?php

namespace Database\Seeders;

use App\Models\CarrierService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpsServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $upsServices = [
            // Domestic Services
            ["carrier_name" => "UPS", "service_type" => "Domestic", "service_name" => "UPS Next Day Air Early", "code" => "14", "description" => ""],
            ["carrier_name" => "UPS", "service_type" => "Domestic", "service_name" => "UPS Next Day Air", "code" => "01", "description" => ""],
            ["carrier_name" => "UPS", "service_type" => "Domestic", "service_name" => "UPS Next Day Air Saver", "code" => "13", "description" => ""],
            ["carrier_name" => "UPS", "service_type" => "Domestic", "service_name" => "UPS 2nd Day Air A.M.", "code" => "59", "description" => ""],
            ["carrier_name" => "UPS", "service_type" => "Domestic", "service_name" => "UPS 2nd Day Air", "code" => "02", "description" => ""],
            ["carrier_name" => "UPS", "service_type" => "Domestic", "service_name" => "UPS 3 Day Select", "code" => "12", "description" => ""],
            ["carrier_name" => "UPS", "service_type" => "Domestic", "service_name" => "UPS Ground", "code" => "03", "description" => ""],
            ["carrier_name" => "UPS", "service_type" => "Domestic", "service_name" => "UPS Standard", "code" => "11", "description" => ""],
            ["carrier_name" => "UPS", "service_type" => "Domestic", "service_name" => "UPS SurePost Less than 1 lb", "code" => "92", "description" => ""],
            ["carrier_name" => "UPS", "service_type" => "Domestic", "service_name" => "UPS SurePost 1 lb or greater", "code" => "93", "description" => "UPS Quick Ship Economy"],
            ["carrier_name" => "UPS", "service_type" => "Domestic", "service_name" => "UPS SurePost BPM", "code" => "94", "description" => ""],
            ["carrier_name" => "UPS", "service_type" => "Domestic", "service_name" => "UPS SurePost Media", "code" => "95", "description" => ""],

            // International Services
            ["carrier_name" => "UPS", "service_type" => "International", "service_name" => "UPS Worldwide Express", "code" => "07", "description" => ""],
            ["carrier_name" => "UPS", "service_type" => "International", "service_name" => "UPS Worldwide Expedited", "code" => "08", "description" => ""],
            ["carrier_name" => "UPS", "service_type" => "International", "service_name" => "UPS Standard (International)", "code" => "11", "description" => ""],
            ["carrier_name" => "UPS", "service_type" => "International", "service_name" => "UPS Worldwide Express Plus", "code" => "54", "description" => ""],
            ["carrier_name" => "UPS", "service_type" => "International", "service_name" => "UPS Worldwide Saver", "code" => "65", "description" => ""],

            // Additional Services
            ["carrier_name" => "UPS", "service_type" => "Additional", "service_name" => "UPS Worldwide Express Freight", "code" => "96", "description" => ""],
            ["carrier_name" => "UPS", "service_type" => "Additional", "service_name" => "UPS Worldwide Express Freight Midday", "code" => "71", "description" => ""],
        ];
        foreach ($upsServices as $service) {
            CarrierService::create($service);
        }
    }
}
