<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $alice = User::factory()->create([
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'password',
        ]);

        $bob = User::factory()->create([
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'password' => 'password',
        ]);

        $users = [$alice, $bob];

        $categoryNames = ['Technology', 'Lifestyle', 'Travel', 'Food', 'Health'];
        $categories = collect($categoryNames)->map(
            fn (string $name) => Category::create(['name' => $name])
        );

        $posts = [
            ['title' => 'Getting Started with Laravel 12', 'category' => 'Technology', 'author' => 'Alice', 'seed' => 'laravel-blog'],
            ['title' => 'Weekend Hiking Guide', 'category' => 'Travel', 'author' => 'Bob', 'seed' => 'hiking-trail'],
            ['title' => 'Building Better Daily Habits', 'category' => 'Lifestyle', 'author' => 'Alice', 'seed' => 'daily-habits'],
            ['title' => 'Mastering Tailwind CSS Utilities', 'category' => 'Technology', 'author' => 'Bob', 'seed' => 'tailwind-css'],
            ['title' => 'Meal Prep for Busy Weeks', 'category' => 'Food', 'author' => 'Alice', 'seed' => 'meal-prep'],
            ['title' => 'Morning Routines That Actually Stick', 'category' => 'Health', 'author' => 'Bob', 'seed' => 'morning-routine'],
            ['title' => 'Exploring Kyoto on a Budget', 'category' => 'Travel', 'author' => 'Alice', 'seed' => 'kyoto-budget'],
            ['title' => 'Understanding Eloquent Relationships', 'category' => 'Technology', 'author' => 'Bob', 'seed' => 'eloquent-relations'],
            ['title' => 'Simple Home Workouts Without Equipment', 'category' => 'Health', 'author' => 'Alice', 'seed' => 'home-workout'],
            ['title' => 'The Art of Slow Living', 'category' => 'Lifestyle', 'author' => 'Bob', 'seed' => 'slow-living'],
            ['title' => 'Homemade Sourdough for Beginners', 'category' => 'Food', 'author' => 'Alice', 'seed' => 'sourdough'],
            ['title' => 'Deploying Laravel with Zero Downtime', 'category' => 'Technology', 'author' => 'Bob', 'seed' => 'zero-downtime'],
            ['title' => 'Digital Detox: A 7-Day Plan', 'category' => 'Lifestyle', 'author' => 'Alice', 'seed' => 'digital-detox'],
            ['title' => 'Road Trip Essentials Checklist', 'category' => 'Travel', 'author' => 'Bob', 'seed' => 'road-trip'],
            ['title' => 'Sleep Hygiene Tips That Work', 'category' => 'Health', 'author' => 'Alice', 'seed' => 'sleep-hygiene'],
            ['title' => 'One-Pot Pasta Recipes', 'category' => 'Food', 'author' => 'Bob', 'seed' => 'one-pot-pasta'],
            ['title' => 'Writing Policies in Laravel', 'category' => 'Technology', 'author' => 'Alice', 'seed' => 'laravel-policies'],
            ['title' => 'Minimalist Wardrobe Guide', 'category' => 'Lifestyle', 'author' => 'Bob', 'seed' => 'minimal-wardrobe'],
            ['title' => 'Backpacking Through Southeast Asia', 'category' => 'Travel', 'author' => 'Alice', 'seed' => 'sea-backpacking'],
            ['title' => 'Stretching Routines for Desk Workers', 'category' => 'Health', 'author' => 'Bob', 'seed' => 'desk-stretching'],
        ];

        $createdPosts = collect($posts)->map(function (array $data) use ($categories, $alice, $bob) {
            $author = $data['author'] === 'Alice' ? $alice : $bob;
            $category = $categories->firstWhere('name', $data['category']);

            return Post::create([
                'title' => $data['title'],
                'body' => "This is a seeded post about {$data['title']}.\n\n{$author->name} shares practical notes, lessons learned, and a few tips you can try this week.",
                'feature_image' => "https://picsum.photos/seed/{$data['seed']}/800/400",
                'category_id' => $category->id,
                'user_id' => $author->id,
            ]);
        });

        $commentTemplates = [
            'Really enjoyed this — clear and actionable.',
            'Could you expand on the second section?',
            'I tried this last week and it worked well.',
            'Great post, thanks for sharing your experience.',
            'This answered exactly what I was looking for.',
            'Would love a follow-up with more examples.',
            'Bookmarked for later. Very helpful.',
            'Interesting perspective, I had not thought of it that way.',
            'The picsum image fits the topic nicely!',
            'Shared this with a friend who needed it.',
        ];

        $commentIndex = 0;
        foreach ($createdPosts as $post) {
            for ($i = 0; $i < 2; $i++) {
                $commenter = $users[($commentIndex + $i) % 2];
                Comment::create([
                    'content' => $commentTemplates[$commentIndex % count($commentTemplates)],
                    'post_id' => $post->id,
                    'user_id' => $commenter->id,
                ]);
                $commentIndex++;
            }
        }
    }
}
