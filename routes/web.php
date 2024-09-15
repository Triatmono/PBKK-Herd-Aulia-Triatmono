<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', ['title' => 'Home']);
});

Route::get('/about', function () {
    return view('about', ['title' => 'about']);
});

Route::get('/posts', function () {
    return view('posts', ['title' => 'Blog', 'posts' =>[
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
    ]]);
});

Route::get('/posts/{slug}', function($slug){
    $posts = [
        [
            'id' => 1,
           'title' => 'Judul Artikel 1',
           'slug' => 'judul-artikel-1',
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

        $post = Arr::first($posts, function($post) use ($slug) {
            return $post['slug'] == $slug;
        });

        return view('post', ['title' => 'Single Post', 'post' => $post]);
});

Route::get('/contact', function () {
    return view('contact', ['title' => 'contact']);
});
