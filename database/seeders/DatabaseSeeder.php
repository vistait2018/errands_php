<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Role::factory()->createMany([
            ['role_name' => 'user'],
            ['role_name' => 'admin'],
        ]);


        $admin_role = Role::where('role_name', 'admin')->first();
        $user= User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' =>$admin_role->id,
        ]);






    }
}
