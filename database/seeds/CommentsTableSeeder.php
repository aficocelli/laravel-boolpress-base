<?php

use Illuminate\Database\Seeder;
use App\Comment;
use Faker\Generator as Faker;
use App\Post;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        // importiamo solo i post che sono stati pubblicati
        
        $posts = Post::where('published', 1)->get();
    
        // ciclo sui posts

        foreach($posts as $post){
            // ciclo per creare i commenti
            for($i = 0; $i < rand(0, 3); $i++){

                $newComment = new Comment();

                $newComment->post_id = $post->id;

                $newComment->name = $faker->name();

                $newComment->content = $faker->text();

                $newComment->save();
            }


        }
    
    
    }
}
