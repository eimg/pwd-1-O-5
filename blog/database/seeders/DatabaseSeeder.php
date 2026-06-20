<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Article::factory(20)->create();
        \App\Models\Comment::factory(40)->create();

        \App\Models\User::updateOrCreate([
            "email" => "alice@gmail.com",
        ], [
            "name" => "Alice",
            "password" => Hash::make("password"),
            "role" => "admin",
        ]);

        \App\Models\User::updateOrCreate([
            "email" => "bob@gmail.com",
        ], [
            "name" => "Bob",
            "password" => Hash::make("password"),
            "role" => "user",
        ]);

        $list = ['News', 'Tech', 'App', 'Mobile', 'Web'];
        foreach($list as $name) {
            \App\Models\Category::create([ 'name' => $name ]);
        }
    }
}
