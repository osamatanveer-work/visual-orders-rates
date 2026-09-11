<?php

namespace Database\Seeders;

use App\Models\CarrierService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class USPSServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $uspsServices = [
            // Domestic Services
            ["carrier_name" => "USPS", "service_type" => "Domestic", "service_name" => "USPS Priority Mail", "code" => "usps_priority_mail", "description" => ""],
            ["carrier_name" => "USPS", "service_type" => "Domestic", "service_name" => "USPS Priority Mail Express", "code" => "usps_priority_mail_express", "description" => ""],
            ["carrier_name" => "USPS", "service_type" => "Domestic", "service_name" => "USPS Parcel Select Ground", "code" => "usps_parcel_select", "description" => ""],
            ["carrier_name" => "USPS", "service_type" => "Domestic", "service_name" => "USPS First Class Mail", "code" => "usps_first_class_mail", "description" => ""],
            ["carrier_name" => "USPS", "service_type" => "Domestic", "service_name" => "USPS Media Mail", "code" => "usps_media_mail", "description" => ""],
            ["carrier_name" => "USPS", "service_type" => "Domestic", "service_name" => "USPS Ground Advantage", "code" => "usps_ground_advantage", "description" => ""],
            ["carrier_name" => "USPS", "service_type" => "Domestic", "service_name" => "USPS First Class Mail - Letter", "code" => "usps_first_class_mail", "description" => ""],
            ["carrier_name" => "USPS", "service_type" => "Domestic", "service_name" => "USPS First Class Mail - Package", "code" => "usps_first_class_mail", "description" => ""],
            ["carrier_name" => "USPS", "service_type" => "Domestic", "service_name" => "USPS Priority Mail - Package", "code" => "usps_priority_mail", "description" => ""],
            ["carrier_name" => "USPS", "service_type" => "Domestic", "service_name" => "USPS Priority Mail - Medium Flat Rate Box", "code" => "usps_priority_mail", "description" => ""],
            ["carrier_name" => "USPS", "service_type" => "Domestic", "service_name" => "USPS Priority Mail - Small Flat Rate Box", "code" => "usps_priority_mail", "description" => ""],
            ["carrier_name" => "USPS", "service_type" => "Domestic", "service_name" => "USPS Priority Mail - Large Flat Rate Box", "code" => "usps_priority_mail", "description" => ""],
            ["carrier_name" => "USPS", "service_type" => "Domestic", "service_name" => "USPS Parcel Select Ground - Package", "code" => "usps_parcel_select", "description" => ""],

            // International Services
            ["carrier_name" => "USPS", "service_type" => "International", "service_name" => "USPS Priority Mail Intl", "code" => "usps_priority_mail_international", "description" => ""],
            ["carrier_name" => "USPS", "service_type" => "International", "service_name" => "USPS Priority Mail Express Intl", "code" => "usps_priority_mail_express_international", "description" => ""],
            ["carrier_name" => "USPS", "service_type" => "International", "service_name" => "USPS First Class Mail Intl", "code" => "usps_first_class_mail_international", "description" => ""],
            ["carrier_name" => "USPS", "service_type" => "International", "service_name" => "GlobalPost Economy Intl", "code" => "globalpost_economy", "description" => ""],
            ["carrier_name" => "USPS", "service_type" => "International", "service_name" => "GlobalPost Standard Intl", "code" => "globalpost_priority", "description" => ""],
            ["carrier_name" => "USPS", "service_type" => "International", "service_name" => "GlobalPost Plus", "code" => "gp_plus", "description" => ""],
            ["carrier_name" => "USPS", "service_type" => "International", "service_name" => "GlobalPost Parcel Select SmartSaver", "code" => "globalpost_parcel_select_smart_saver", "description" => ""],
        ];

        foreach ($uspsServices as $service) {
            CarrierService::create($service);
        }
    }
}
