<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile; 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\DB; // <-- BARIS BARU WAJIB

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // (Role: Superadmin)
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@agenda.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), 
                'email_verified_at' => now(),
            ]
        );
        
        // 2. BUAT PROFILE MENGGUNAKAN DB FACADE (BYPASS ELOQUENT MODEL)
        DB::table('profiles')->updateOrInsert(
            ['user_id' => $superAdminUser->id], 
            [
                'full_name' => 'Pimpinan Utama',
                'category' => 'Pimpinan',
                'role' => 'Superadmin',  
                'photo_path' => null,
                'created_at' => now(), 
                'updated_at' => now(), 
            ]
        );

        // (Role: Admin)
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@agenda.com'],
            [
                'name' => 'Admin Agenda',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 4. BUAT PROFILE MENGGUNAKAN DB FACADE (BYPASS ELOQUENT MODEL)
        DB::table('profiles')->updateOrInsert(
            ['user_id' => $adminUser->id],
            [
                'full_name' => 'Staff Agenda',
                'category' => 'Staff', 
                'role' => 'Admin',    
                'photo_path' => null,
                'created_at' => now(), 
                'updated_at' => now(), 
            ]
        );
    }
}