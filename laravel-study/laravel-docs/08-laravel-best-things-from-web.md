# Coisas boas do laravel

## Best Structure Rules

### 0 - Documentação

https://laravel.com/docs/8.x/structure#the-root-app-directory

### 1 - Dev.to nomenclature

https://dev.to/lathindu1/laravel-best-practice-coding-standards-part-01-304l

**Article 1 : Naming Conventions. ✊**

Here we will talk about naming conventions about PHP. Following conventions have accepted by Laravel community.

 01.01 Controller 👈

- Name should be in **singular** form.
- Should use **PascalCase**.

| Should Do                | Should't Do               |
| :----------------------- | :------------------------ |
| `CustomerController.php` | `CustomersController.php` |

------

 01.02 Route 👈

**01.02.01 Route Url 👈**

- Url should be in **plural** form.
- Can use **kebab-case** if there are two words in single part For best Practice.

| Should Do                   | Should't Do                 |
| :-------------------------- | :-------------------------- |
| `/customers/25`             | `customer/25`               |
| `/customers/password-reset` | `/customers/password_reset` |
| `"`                         | `/customers/passwordReset`  |

**01.02.02 Route Name 👈**

- Should use **snake_case** with dot notation.
- Better to use same name like in URL.

| Should Do                        | Should't Do                      |
| :------------------------------- | :------------------------------- |
| `->('customers.view')`           | `->('customers-view')`           |
| `"`                              | `->('customers_view')`           |
| `->('customers.password_reset')` | `->('customers.password.reset')` |
| `"`                              | `->('customers.password-reset')` |
| `"`                              | `->('customer-password-reset')`  |

------

 01.03 DataBase Related 👈

**01.03.01 Migration 👈**

- Should use name as what you want to do with **snake_case**.

| Should Do                                                  | Should't Do                                       |
| :--------------------------------------------------------- | :------------------------------------------------ |
| `2021_03_19_033513_create_customers_table.php`             | `2021_03_19_033513_customers.php`                 |
| `2021_03_19_033513_add_image_id_to_customers_table.php`    | `2021_03_19_033513_add_image_id_customers.php`    |
| `2021_03_19_033513_drop_image_id_from_customers_table.php` | `2021_03_19_033513_remove_image_id_customers.php` |

**01.03.02 Table 👈**

- Table name must be in **plural** form.
- Should use **snake_case**.

| Should Do    | Should't Do                             |
| :----------- | :-------------------------------------- |
| `customers`  | `customer`                              |
| `cart_items` | `cartItems` , `CartItems` , `Cart_item` |

**01.03.03 Pivot Table 👈**

- Table name must be in **singular** form.
- Should use **snake_case**
- Names should be in **alphabetical** Order.

| Should Do        | Should't Do                                               |
| :--------------- | :-------------------------------------------------------- |
| `course_student` | `student_courses` , `students_courses` ,`course_students` |

**01.03.04 Table Columns 👈**

- Should use **snake_case**.
- Should not use table name with column names.
- Readable name can use for better practice.

| Should Do    | Should't Do                     |
| :----------- | :------------------------------ |
| `first_name` | `user_first_name` , `FirstName` |

**01.03.05 Foreign key 👈**

- Should use **snake_case**.
- Should use **singular** table name with **id** prefix.

| Should Do   | Should't Do                                  |
| :---------- | :------------------------------------------- |
| `course_id` | `courseId` , `id` ,`courses_id` ,`id_course` |

**01.03.06 Primary key 👈**

- only use name as **id**.

| Should Do | Should't Do      |
| :-------- | :--------------- |
| `id`      | `custom_name_id` |

**01.03.07 Model 👈**

- Model name must be in **singular** form.
- Should Use **PascalCase**
- Model name must be a singular form or table name.

| Should Do  | Should't Do            |
| :--------- | :--------------------- |
| `Customer` | `Customers`,`customer` |

**01.03.08 Model Single relations [Has One, Belongs To] 👈**

- Method name must be in **singular** form.
- Should Use **camalCase**

| Should Do       | Should't Do                                       |
| :-------------- | :------------------------------------------------ |
| `studentCourse` | `StudentCourse`,`student_course`,`studentCourses` |

**01.03.09 Model all other relations and methods [Has Many,other] 👈**

- Method name must be in **plural** form.
- Should use **camalCase**

| Should Do   | Should't Do                       |
| :---------- | :-------------------------------- |
| `cartItems` | `CartItem`,`cart_item`,`cartItem` |

------

 01.04 Functions 👈

- Should Use **snake_case**

| Should Do    | Should't Do             |
| :----------- | :---------------------- |
| `show_route` | `showRoute`,`ShowRoute` |

------

 01.05 Methods in resources controller 👈

- Should use **camelCase**
- Must use singles words related to action

| Should Do | Should't Do        |
| :-------- | :----------------- |
| `store`   | `saveCustomer`     |
| `show`    | `viewCustomer`     |
| `destroy` | `deleteCustomer`   |
| `index`   | `allCustomersPage` |

------

 01.06 Variables 👈

- Should use **camelCase**
- Must use readable words which are describe about value.

| Should Do           | Should't Do                                                  |
| :------------------ | :----------------------------------------------------------- |
| `$customerMessages` | `$CustomerMessages` ,`$customer_messages` , `$c_messages` , `$c_m` |

------

 01.07 Collection 👈

- Must described about the value.
- Must be plural

| Should Do                                           | Should't Do                             |
| :-------------------------------------------------- | :-------------------------------------- |
| `$verifiedCustomers = $customer->verified()->get()` | `$verified` ,`$data` , `$resp` , `$v_c` |

------

 01.07 Object 👈

- Must described about the value.
- Must be singular

| Should Do                                            | Should't Do                             |
| :--------------------------------------------------- | :-------------------------------------- |
| `$verifiedCustomer = $customer->verified()->first()` | `$verified` ,`$data` , `$resp` , `$v_c` |

------

 01.08 Configs 👈

- Should use **snake_case**
- Must described about the value.

| Should Do          | Should't Do                                         |
| :----------------- | :-------------------------------------------------- |
| `comments_enabled` | `CommentsEnabled` ,`comments` , `c_enabled` , `$ce` |

------

 01.09 Traits 👈

- Should be adjective.

| Should Do | Should't Do                 |
| :-------- | :-------------------------- |
| `Utility` | `UtilityTrait` ,`Utilities` |

------

 01.10 Interface 👈

- Should be adjective or a noun.

| Should Do       | Should't Do                               |
| :-------------- | :---------------------------------------- |
| `Authenticable` | `AuthenticationInterface` ,`Authenticate` |

So above I have talked about naming convetion in Laravel projects. not only Laravel you guys can use this rules with any other PHP framework.

## Best Practices Coding

### 1 - Laravel Best Practices from GitHub (GREAT)

https://github.com/jonaselan/laravel-best-practices

## Best Tips

### 1 - Medium

https://medium.com/@marslan.ali/laravel-best-practices-every-developer-should-know-and-follow-it-cebccfb1cc3e

> ***Tip 2. Unsigned Integer\***

For foreign key migrations instead of integer() use **unsignedInteger()** type or **integer()->unsigned() ,** otherwise you may get SQL errors.

```php
Schema::create('employees', function (Blueprint $table) {   $table-> unsignedInteger ('company_id'); //if primary id Increments
   $table-> unsignedBigInteger ('company_id'); //if primary id BigIncrements
   $table->foreign('company_id')->references('id')->on('companies');});
```

> ***Tip 4 : Raw DB Queries\***

You can use RAW DB queries in various places, including havingRaw() function after groupBy() .

```
Product::groupBy('category_id')->havingRaw('COUNT(*) > 1')->get();
```

> ***Tip 5 . $loop variable in foreach\***

Inside of foreach loop, check if current entry is first/last by just using $loop variable.

```
@foreach ($users as $user)  @if ($loop->first)
    This is the first iteration.
  @endif  @if ($loop->last)
    This is the last iteration.
  @endif  This is user {{ $user->id }}
@endforeach
```

There are also other properties like $loop->iteration or $loop->count .

> ***Tip 6. Eloquent where date methods\***

In Eloquent, check the date with functions whereDay() , whereMonth() , whereYear() , whereDat**e()** and whereTime() .

```
$products = Product::whereDate('created_at', '2018-01-31')->get();
$products = Product::whereMonth('created_at', '12')->get();
$products = Product::whereDay('created_at', '31')->get();
$products = Product::whereYear('created_at', date('Y'))->get();
$products = Product::whereTime('created_at', '=', '14:13:58')->get();
```

> ***Tip 7. Route group within a group\***

in Routes, you can create a group within a group, assigning a certain middleware only to some URLs in the “parent” group.

```
Route::group(['prefix' => 'account', 'as' => 'account.'], function() { Route::get('login', 'AccountController@login');
 Route::get('register', 'AccountController@register');  Route::group(['middleware' => 'auth'], function() {
    Route::get('edit', 'AccountController@edit');
  });});
```

> ***Tip 8. Increments and decrements\***

if you want to increment some DB column in some table, just use increment() function. Oh, and you can increment not only by 1, but also by some number, like 50.

```
Post::find($post_id)->increment('view_count');
User::find($user_id)->increment('points', 50);
```

> ***Tip 10. No timestamp columns\***

If your DB table doesn’t contain timestamp fields created_at and updated_at , you can specify that Eloquent model wouldn’t use them, with $timestamps = false property.

```
class Company extends Model
{
  public $timestamps = false;
}
```

> ***Tip 11. Database migrations column types\***

There are interesting column types for migrations, here are a few examples.

```
$table->geometry('positions');
$table->ipAddress('visitor');
$table->macAddress('device');
$table->point('position');
$table->uuid('id');
```

See all column types:[ visit here](https://laravel.com/docs/master/migrations#creating-columns)

> ***Tip 12. Soft-deletes: multiple restore\***

When using soft-deletes, you can restore multiple rows in one sentence

```
Post::withTrashed()->where('author_id', 1)->restore();
```

> ***Tip 13. Image validation\***

While validating uploaded images, you can specify the dimensions you require.

```
'photo' => 'dimensions:max_width=4096,max_height=4096'
```

> ***Tip 15. Don’t create Controllers\***

If you want route to just show a certain view, don’t create a Controller method, just use **Route::view()** function.

```
// Instead of this
Route::get('about', 'TextsController@about');// And this
class TextsController extends Controller
{
  public function about()
  {
    return view('texts.about');
  }
}
```

Do this

```
// Do this
Route::view('about', 'texts.about');
```

> ***Tip 17 Model all: columns\***

When calling Eloquent’s **Model::all() ,** you can specify which columns to return.

```
$users = User::all(['id', 'name', 'email']);
```

> ***Tip 18. To Fail or not to Fail\***

In addition to findOrFail() , there’s also Eloquent method firstOrFail() which will return 404 page if no records for query are found.

```
$user = User::where('email','codechief@gmail.com')->firstOrFail();
```

> ***Tip 19. Use hasMany to create Many\***

If you have hasMany() relationship, you can use saveMany() to save multiple “child” entries from your “parent” object, all in one sentence.

```
$post = Post::find(1);$post->comments()->saveMany([ new Comment(['message' => 'First comment']),
 new Comment(['message' => 'Second comment']),]);
```

> ***Tip 20. More convenient DD\***

Instead of doing dd($result); you can put ->dd() as a method directly at the end of your Eloquent sentence, or any Collection.

```
// Instead of
$users = User::where('name', 'Taylor')->get();
dd($users);// Do this
$users = User::where('name', 'Taylor')->get()->dd();
```

> ***Tip 21. How to avoid error in {{ $post->user->name }} if user is deleted?\***

You can assign a default model in belongsTo relationship, to avoid fatal errors when calling it like {{ $post->user->name }} if $post->user doesn’t exist.

```
/**
* Get the author of the post.
*/
public function user()
{
   return $this->belongsTo('App\User')->withDefault();
}
```

### 2 - Innofield

https://www.innofied.com/top-10-laravel-best-practices/

**3. Use Eloquent Orm** 

[Eloquent ORM](https://www.innofied.com/eloquent-relationships-in-laravel-development/) is one of the most powerful features, which is used to extract data that will be shown to the end users through a single query. This is one of the best practice of developing in laravel would be to take care of the naming convention of your model. Eloquent ORM is also working for optimizing the queries part.

Not to Do:

```
SELECT *
FROM `posts`
WHERE EXISTS (SELECT *
FROM `users`
WHERE `posts`.`user_id` = `users`.`id`
AND EXISTS (SELECT *
FROM `profiles`
WHERE `profiles`.`user_id` = `users`.`id`)
AND `users`.`deleted_at` IS NULL)
AND `active` = ‘1’
ORDER BY `created_at` DESC
```

Should Use:

```
Post::has(‘user.profile’)->active()->latest()->get();
```

**4. Use Naming Conventions**

https://www.innofied.com/how-much-does-an-app-cost/The recommended standards should be followed as per the experts are PSR-2 and PSR-4.

| **What**                         | **How**                                    | **Should Follow**                    | **Not to Use**                       |
| -------------------------------- | ------------------------------------------ | ------------------------------------ | ------------------------------------ |
| Controller                       | singular                                   | PostController                       | PostsController                      |
| Route                            | plural                                     | posts/1                              | post/1                               |
| Named route                      | snake_case with dot notation               | users.show_active                    | users.show-active, show-active-users |
| Model                            | singular                                   | User                                 | Users                                |
| hasOne or belongsTo relationship | singular                                   | postComment                          | postComments, post_comment           |
| All other relationships          | plural                                     | postComments                         | postComment, post_comments           |
| Table                            | plural                                     | post_comments                        | post_comment, postComments           |
| Pivot table                      | singular model names in alphabetical order | post_user                            | user_post, posts_users               |
| Model property                   | snake_case                                 | $model->created_at                   | $model->createdAt                    |
| Foreign key                      | singular model name with _id suffix        | post_id                              | PostId, id_post, posts_id            |
| Primary key                      | –                                          | id                                   | custom_id                            |
| Migration                        | –                                          | 2019_05_08_000000_create_posts_table | 2019_05_08_000000_posts              |
| Method                           | camelCase                                  | getAll                               | get_all                              |
| Method in resource controller    | table                                      | store                                | savePost                             |
| Variable                         | camelCase                                  | $postsWithAuthor                     | $posts_with_creator                  |

**8. Validation**

Move validation from controllers to Request classes.

Not To Use:

```
public function store(Request $request) {

    $request->validate([

    ‘title’ => ‘required|unique:articles|max:255’,

    ‘body’ => ‘required’,

    ‘publish_at’ => ‘nullable|date’,

    ]);

}
```

Should Use:

```php
public function store(ArticleRequest $request) { }

class ArticleRequest extends Request {
    public function rules(){
        return [
            ‘title’ => ‘required|unique:articles|max:255’,
            ‘body’ => ‘required’,
            ‘publish_at’ => ‘nullable|date’,
        ];
    }
}
```

