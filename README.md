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

```php
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

```php
Route::get('/posts', function () {
    return view('posts',[
        'title'=>'Blog',
        'posts'=> Post::all()
    ]);
});
```
### Viewing the data in blade
Using <code> @foreach </code> function will help with the looping for each post we want to show.

```php
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

## Progress up to Episode 13
### Relationship Model

We can specify the relationship between models. Assuming a one-to-many relationship between Category and Post, we can use the following code to state that a Category has many Posts and a Post belongs to a Category ```Post.php```

```php
public function category(): BelongsTo{
    return $this->belongsTo(Category::class);
}
```
And also on the ```category.php```

```php
public function posts():HasMany{
    return $this->hasMany(Post::class);
}
```

By doing this, we can obtain the model of the category associated with the post when we have a model of the post and call the category method. All posts belonging to that category would be obtained if we called posts in a category model.

### Factory
We may create dummy data by defining the Faker Library as a factory. To obtain the necessary dummy data, we simply call the functions inside fake(). We can also call the factory of another model to get the model in a factory ```PostFactory.php```

```php
public function definition(): array{
    return [
        'title'=>fake()->sentence(),
        'author'=>fake()->name(),
        'author_id'=>User::factory(),
        'slug'=>Str::slug(fake()->sentence()),
        'body'=>fake()->text()
    ];
}
```

### Seeder
A seeder file can be used to call a factory ```DatabaseSeeder.php```

```php
class DatabaseSeeder extends Seeder{
    public function run(): void{
        $this->call([CategorySeeder::class, UserSeeder::class]);
        Post::factory(100)->recycle([Category::all(),User::all()])->create();
    }
}
```
## Progress up to Episode 17
### N + 1
is a frequent query issue that arises when Eloquent ORM is used. Assuming we are obtaining a list of size N, it will query another piece of data for each element, resulting in N+1. By simply specifying relations as an array in the model, Laravel offers a simple solution to this problem.

```php
protected $with = ['author','category'];
```
### Searching
```php
public function scopeFilter(Builder $query, array $filters):void{
    $query->when(
        $filters['search'] ?? false,
        fn($query,$search)=>
        $query->where('title','like','%'.request('search').'%')
    );
    $query->when(
        $filters['category'] ?? false,
        fn($query,$category)=>
        $query->whereHas('category',fn($query)=>$query->where('slug',$category))
    );
    $query->when(
        $filters['author'] ?? false,
        fn($query,$author)=>
        $query->whereHas('author',fn($query)=>$query->where('username',$author))
    );
    
}
```php
<form>
          @if(request('category'))
          <input type="hidden" name="category" value="{{ request('category') }}">
          @endif
          @if(request('author'))
          <input type="hidden" name="author" value="{{ request('author') }}">
          @endif
            <div class="items-center mx-auto mb-3 space-y-4 max-w-screen-sm sm:flex sm:space-y-0">
                <div class="relative w-full">
                    <label for="search" class="hidden mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Search</label>
                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                      <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                      </svg>
                    </div>
                    <input class="block p-3 pl-10 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 sm:rounded-none sm:rounded-l-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Search for article" type="search" id="search" name="search" autocomplete="off">
                </div>
                <div>
                    <button type="submit" class="py-3 px-5 w-full text-sm font-medium text-center text-white rounded-lg border cursor-pointer bg-primary-700 border-primary-600 sm:rounded-none sm:rounded-r-lg hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Search</button>
                </div>
            </div>
        </form>
```

```php
Route::get('/posts', function () {
    $posts = Post::filter(request(['search','category','author']))->latest();
    return view('posts',[
        'title'=>'Blog',
        'posts'=> $posts
    ]);
});
```
### Pagination
Laravel has a straightforward method for dividing the requested data into many pages, which is by

```php
    return view('posts', ['title' => 'Blog', 'posts' => Post::filter(request(['search', 'category', 'author']))->latest()->simplePaginate(9)]);
```
indicates how many entries should be shown on a single page. A new issue with our searching tool is that when we search for a category and move on to the next page, the search is erased and we are taken to the next page of unsearched data. 

## End Output

<img width="1440" alt="Screenshot 2024-10-20 at 4 34 05 PM" src="https://github.com/user-attachments/assets/360d2e7c-4131-4d2a-92d5-c19876d189df">

