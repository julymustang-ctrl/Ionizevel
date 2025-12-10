<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin kullanıcısı oluştur
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'id_role' => 1, // Super Admin
                'username' => 'admin',
                'screen_name' => 'Administrator',
                'firstname' => 'Super',
                'lastname' => 'Admin',
                'email' => 'admin@ionizevel.local',
                'password' => Hash::make('admin123'),
                'join_date' => now(),
            ]
        );

        // Normal Admin kullanıcısı oluştur
        User::updateOrCreate(
            ['username' => 'editor'],
            [
                'id_role' => 3, // Editor
                'username' => 'editor',
                'screen_name' => 'Editor',
                'firstname' => 'Content',
                'lastname' => 'Editor',
                'email' => 'editor@ionizevel.local',
                'password' => Hash::make('editor123'),
                'join_date' => now(),
            ]
        );
    }
}
