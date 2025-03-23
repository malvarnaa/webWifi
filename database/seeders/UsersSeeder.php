<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('12345678'),
                'role' => 'admin',
            ],
            [
                'name' => 'Pelanggan',
                'email' => 'pelanggan@gmail.com',
                'password' => bcrypt('12345678'),
                'role' => 'pelanggan',
            ],
        ];

        foreach($userData as $key => $val) {
            User::create($val);
        }
    }
}
