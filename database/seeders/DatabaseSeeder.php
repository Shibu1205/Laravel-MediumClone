<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'John Doe',
            'username'=>'testuser',
            'email' => 'john@example.com',
        ]);

        $categories = [
            'Technology' => 'Latest updates and insights from the tech world.',
            'Health' => 'Wellness, nutrition, and healthy living tips.',
            'Science' => 'Curious discoveries and scientific explanations.',
            'Sports' => 'Game coverage, fitness, and athletic stories.',
            'Politics' => 'Political analysis and current affairs.',
            'Entertainment' => 'Culture, media, and entertainment highlights.',
        ];

        foreach ($categories as $title => $content) {
            Category::create([
                'title' => $title,
                'slug' => Str::slug($title),
                'content' => $content,
            ]);
        }

        Post::factory(100)->create();
    }
}
