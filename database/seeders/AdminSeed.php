<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "name"=> "Admin",
            "email"=> "admin@gmail.com",
            "password"=> Hash::make("12345"),
            "role"=> "1",
        ]);
        User::create([
            "name"=> "shankar",
            "email"=> "shankar@gmail.com",
            "password"=> Hash::make("123456"),
            "role"=> "0",
        ]);
    }
}
