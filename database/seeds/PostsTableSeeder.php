<?php

use Illuminate\Database\Seeder;
use App\Post;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path() . '/database/examplePostData.json';
        $file = File::get($path);
        $data = json_decode($file);
        foreach ($data as $obj) {
            Post::create([
                'key' => $obj->key,
                'title' => $obj->title,
                'content' => $obj->content,
                'image' => $obj->image,
                'description' => $obj->description,
                'published' => $obj->published,
                'author' => $obj->author
            ]);
        }
    }
}
