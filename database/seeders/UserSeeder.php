<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory(1000)->create();
        $data = [];
        // Admin Account for testing
        $data[] = [
            'first_name' => 'shehab',
            'last_name' => 'waleed',
            'username' => 'shehabwaleed',
            'email' => 'shehab@gmail.com',
            'country' => 'Egypt',
            'password' => bcrypt('shehab2010'),
            'role' => Roles::ADMIN->value,
        ];

        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'first_name' => fake()->name(),
                'last_name' => fake()->name(),
                'username' => fake()->unique()->username(),
                'email' => fake()->unique()->safeEmail(),
                'country' => fake()->country(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.',
                // password
                'role' => Roles::USER->value,
            ];
        }

        foreach (array_chunk($data, 5000) as $chunk) {
            User::insert($chunk);
        }
    }
}
