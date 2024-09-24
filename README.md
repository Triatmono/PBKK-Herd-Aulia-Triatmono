# PBKK using Laravel Herd

## Progress up to Episode 5
### Installation
Laravel Herd is used to create this Laravel project
### TailwindCSS
Without needing CSS files, the CSS framework is utilized to apply styling directly to HTML components.
### Alpine.js
JavaScript framework applied directly in HTML
### Navbar
Using a pre-built navbar component from Tailwind UI, fixed with Alpine.js to bring functionality
### Component
Running <code>php artisan make:component ComponentName</code> will create 2 files, the blade <code>component-name.blade.php</code> and the class <code>ComponentName.php</code>. Components are used for reusability in the main blade view. Components are rendered with 
```blade
<x-component-name></x-component-name>
```
Anything that is inside the tag will be passed into the <code>{{$slot}}</code> in the component. Components <code>header.blade.php</code> and <code>navbar.blade.php</code> are created and rendered inside <code>layout.blade.php</code>. To make sure that the title text in the header corresponds with the page, the right text is passed as a variable <code>title</code> from route to <code>layout.blade.php</code> and then passed to <code>header.blade.php</code> by 
```blade
<x-header>{{$title}}</x-header>
```
then in <code>home.blade.php</code> is accessed with
```blade
<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>
    <h3>ini adalah halaman homepage</h3>
</x-layout>
```

### Output

<img width="1317" alt="Screenshot 2024-09-24 at 4 31 03 PM" src="https://github.com/user-attachments/assets/2ee852aa-1469-4356-bb5b-7b5a2d58c414">


## Progress up to Episode 7
### Storing data in model
From the video, we are required to make a new file called <code>Post.php</code> to store the data by using <code>class Post{} </code>

```blade
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
```
It uses attributes such as id, slug, title, author, and body. 

### Passing the data from the model to the view
We can use the <code> Route::get </code> function. This will pass the variables to the view.

```blade
Route::get('/posts', function () {
    return view('posts',[
        'title'=>'Blog',
        'posts'=> Post::all()
    ]);
});
```
### Viewing the data in blade
Using <code> @foreach </code> function will help with the looping for each post we want to show.

```blade
@foreach ($posts as $post)
  

  <article class="py-8 max-w-screen-md border-b border-gray-300">
    <a href="/posts/{{ $post['slug'] }}" class="hover:underline">
    <h2 class="mb-1  text-3xl tracking-tight font-bold text-gray-900">{{ $post['title']}}</h2> 
    </a>
    <div class="text-base text-gray-500">
      <a href="#"> {{ $post['author']}}</a> | 1 January 2024
    </div>
    <p class="my-4 font-light">{{ Str::limit ($post['body'], 150) }}</p>
      <a href="/posts/{{ $post ['slug'] }}" class="font-medium text-blue-500 hover:underline">Read More &raquo;</a>
  </article>
@endforeach
```
### Output

<img width="1440" alt="Screenshot 2024-09-24 at 4 29 32 PM" src="https://github.com/user-attachments/assets/5c4c3246-69a8-4011-a86b-8fa6a3bfe219">

<img width="1440" alt="Screenshot 2024-09-24 at 4 29 42 PM" src="https://github.com/user-attachments/assets/d2c51e56-5e8a-4ff1-b1be-e0143c5fe87b">


