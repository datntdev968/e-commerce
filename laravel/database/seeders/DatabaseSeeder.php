<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // \App\Models\User::factory(100000)->create();
        Permission::create(['name' => 'viewAny catalogues']);
        Permission::create(['name' => 'view catalogues']);
        Permission::create(['name' => 'store catalogues']);
        Permission::create(['name' => 'update catalogues']);
        Permission::create(['name' => 'delete catalogues']);
    }
}
