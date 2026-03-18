<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'dio@example.com'],
            [
                'name' => 'DIO',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );

        $this->call(JoJoSeeder::class);
    }
}
