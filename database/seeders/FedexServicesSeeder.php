<?php

namespace Database\Seeders;

use App\Models\CarrierService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FedexServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fedexServices = [
            // Domestic Services
            ["carrier_name" => "Fedex", "service_type" => "Domestic", "service_name" => "FedEx First Overnight®", "code" => "FIRST_OVERNIGHT", "description" => ""],
            ["carrier_name" => "Fedex", "service_type" => "Domestic", "service_name" => "FedEx Priority Overnight®", "code" => "PRIORITY_OVERNIGHT", "description" => ""],
            ["carrier_name" => "Fedex", "service_type" => "Domestic", "service_name" => "FedEx Standard Overnight®", "code" => "STANDARD_OVERNIGHT", "description" => ""],
            ["carrier_name" => "Fedex", "service_type" => "Domestic", "service_name" => "FedEx Ground®", "code" => "FEDEX_GROUND", "description" => ""],
            ["carrier_name" => "Fedex", "service_type" => "Domestic", "service_name" => "FedEx Home Delivery®", "code" => "GROUND_HOME_DELIVERY", "description" => ""],
            ["carrier_name" => "Fedex", "service_type" => "Domestic", "service_name" => "FedEx 2Day® AM", "code" => "FEDEX_2_DAY_AM", "description" => ""],
            ["carrier_name" => "Fedex", "service_type" => "Domestic", "service_name" => "FedEx 2Day®", "code" => "FEDEX_2_DAY", "description" => ""],
            ["carrier_name" => "Fedex", "service_type" => "Domestic", "service_name" => "FedEx Express Saver®", "code" => "FEDEX_EXPRESS_SAVER", "description" => ""],

            // International Services
            ["carrier_name" => "Fedex", "service_type" => "International", "service_name" => "FedEx International First®", "code" => "INTERNATIONAL_FIRST", "description" => ""],
            ["carrier_name" => "Fedex", "service_type" => "International", "service_name" => "FedEx International Priority® Express", "code" => "FEDEX_INTERNATIONAL_PRIORITY_EXPRESS", "description" => ""],
            ["carrier_name" => "Fedex", "service_type" => "International", "service_name" => "FedEx International Priority®", "code" => "FEDEX_INTERNATIONAL_PRIORITY", "description" => ""],
            ["carrier_name" => "Fedex", "service_type" => "International", "service_name" => "FedEx International Economy®", "code" => "INTERNATIONAL_ECONOMY", "description" => ""],
            ["carrier_name" => "Fedex", "service_type" => "International", "service_name" => "FedEx International Priority Freight®", "code" => "INTERNATIONAL_PRIORITY_FREIGHT", "description" => ""],
            ["carrier_name" => "Fedex", "service_type" => "International", "service_name" => "FedEx International Economy Freight®", "code" => "INTERNATIONAL_ECONOMY_FREIGHT", "description" => ""],
        ];

        foreach ($fedexServices as $service) {
            CarrierService::create($service);
        }
    }
}
