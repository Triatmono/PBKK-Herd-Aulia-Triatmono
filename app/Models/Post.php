<?php 

namespace App\Models;

use Illuminate\Support\Arr;

class Post{
    public static function all(){
        return [
            [
                'id' => 1,
                'slug' => 'judul-artikel-1',
               'title' => 'Judul Artikel 1',
               'author' => 'Aulia Triatmono',
                'body' => ' Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                            Aperiam totam vero exercitationem, rem quod magnam ut labore deleniti 
                            praesentium distinctio corporis placeat eligendi, quidem dolor, quae quas! 
                            Beatae, quaerat in.'
            ],
            [
                'id' => 2,
                'slug' => 'judul-artikel-2',
                'title' => 'Judul Artikel 2',
                'author' => 'Aulia Triatmono',
                 'body' => ' Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                             Aperiam totam vero exercitationem, rem quod magnam ut labore deleniti 
                             praesentium distinctio corporis placeat eligendi, quidem dolor, quae quas! 
                             Beatae, quaerat ini.'
             ]
            ];
    }
    public static function find($slug): array{
        
        $post = Arr::first(static::all(), fn ($post) => 
        $post['slug'] == $slug);

        if(!$post){
            abort(404);
        }

        return $post;
    }

}


