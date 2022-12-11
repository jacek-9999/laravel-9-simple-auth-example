<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Company;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'active' => true,
            'verified' => true,
            'email_verified_at' => now(),
            'created_at' => now()
        ]);
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'active' => true,
            'verified' => true,
            'email_verified_at' => now(),
            'created_at' => now()
        ]);
        $user = User::where('email', 'user@example.com')->first();
        $admin = User::where('email', 'admin@example.com')->first();
        $company = Company::where('name', 'PuertaLogic')->first();
        $user->company_id = $company->id;
        $admin->company_id = $company->id;
        $user->save();
        $admin->save();
    }
}
