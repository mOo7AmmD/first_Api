<?php

namespace Database\Seeders;

use App\Models\admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                "name" => " admin  ",
                "email" => "admin@admin.com",
                "password" => Hash::make("123456"),
                "age" => 30,
                "gender" => "male"
            ],
            // يمكنك إضافة المزيد هنا
        ];

        foreach ($admins as $adminData) {
            admin::create($adminData);
        }
    }
}
