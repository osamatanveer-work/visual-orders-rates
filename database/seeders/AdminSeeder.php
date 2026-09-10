<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',  // Admin email
            'email_verified_at' => Carbon::now(), // Marking email as verified
            'password' => Hash::make('12345678'),  // Use a secure password
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'bio' => 'Admin of the application',  // Optional bio
            'phone_no' => '1234567890',  // Example phone number
            'location' => 'Admin Location',  // Example location
        ]);
    }
}
