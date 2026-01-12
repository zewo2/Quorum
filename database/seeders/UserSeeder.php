<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@quorum.edu',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '910000001',
            'address' => 'Rua Administração, 1, 1000-001 Lisboa',
            'date_of_birth' => '1985-03-15',
            'nif' => '123456789',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'John Smith',
            'email' => 'john.smith@quorum.edu',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'phone' => '920000001',
            'address' => 'Rua dos Professores, 10, 1100-100 Lisboa',
            'date_of_birth' => '1980-06-22',
            'nif' => '234567890',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah.johnson@quorum.edu',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'phone' => '930000001',
            'address' => 'Avenida Central, 45, 1200-200 Lisboa',
            'date_of_birth' => '1982-09-10',
            'nif' => '345678901',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Alice Williams',
            'email' => 'alice.williams@student.quorum.edu',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '960000001',
            'address' => 'Travessa Estudantes, 5, 1300-300 Lisboa',
            'date_of_birth' => '2002-01-15',
            'nif' => '456789012',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Bob Martinez',
            'email' => 'bob.martinez@student.quorum.edu',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '961000001',
            'address' => 'Rua do Campus, 12, 1400-400 Lisboa',
            'date_of_birth' => '2003-05-20',
            'nif' => '567890123',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Emma Davis',
            'email' => 'emma.davis@student.quorum.edu',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '962000001',
            'address' => 'Praça Universitária, 8, 1500-500 Lisboa',
            'date_of_birth' => '2001-11-08',
            'nif' => '678901234',
            'email_verified_at' => now(),
        ]);

        User::factory(20)->create([
            'role' => 'student',
        ]);

        User::factory(5)->create([
            'role' => 'teacher',
        ]);
    }
}
