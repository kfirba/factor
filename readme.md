# Factor

Short and expressive way to use Laravel's model factories.

## Install
```bash
$ composer require --dev kfirba/factor
```

## Usage

> I've written a more [detailed blog about this package](https://kfirba.me/blog/factor-short-and-expressive-way-to-use-laravels-factories). Make sure to check it out!

The package registers 2 global methods:

1. `make($model, $overrides = [], $times = 1)`
2. `create($model, $overrides = [], $times = 1)`

These methods are almost identical to the `create()` and `make()` method that the `Illuminate\Database\Eloquent\Factory` offers (usually you use the `factory()` global method to obtain an instance of the factory).

> The examples below are the same for both the `create()` and `make()` methods. The only difference is that the `create()` method will also persist the generated instances.
```php
$user = make(User::class);
// -> Equivalent to `factory(User::class)->make()`

$user = make(User::class, $overrides);
// -> Equivalent to `factory(User::class)->make($overrides);`

$user = make(User::class, $overrides, $times);
// -> Equivalent to `factory(User::class, $times)->make($overrides);
```

It looks like that `create()` and `make()` methods are just simple aliases to the `factory()->{$method}()`, but this is where it gets interesting.

## I Want to Decide!

It may sometimes feel "right" to pass the number of objects we want to build as the second argument instead of the overrides. Factor lets you do that. If you pass an integer as the second argument, it will assume you are trying to set the number of objects to create:

```php
make(User::class, 3);
// Equivalent to:
make(User::class, [], 3);

make(User::class, 3, ['name' => 'John']);
// Equivalent to:
make(User::class, ['name' => 'John'], 3);
```

## States

So we decided we want to use a shorthand to the `factory()->create()` call, however, what happens when we want to give it a certain state?
The simplest solution is to tweak the `make()` and `create()` methods a little bit and make it accepts an additional argument.

However, the argument list gets pretty long and given that both the `$overrides` and the `$times` arguments are optionals, it makes it a mess to do it that way.

This package lets you use an expressive syntax to declare your states:

```php
// Given the factory definition [name => 'Fake User'] and the state 'admin' [name => 'Admin']:
Class User 
{
    public function callMe() { echo 'called'; }
}

$user = make(User::class);
echo $user->name;
// -> 'Fake User'
echo $user->callMe();
// -> 'called';

$admin = make(User::class)->states('admin');
echo $admin->name;
// -> 'Admin'
echo $admin->callMe();
// -> 'called';
```

Pay close attention to the above `admin` state.
We call the `states()` method directly on the `make()` returned value and we are still able to access any property or method off of the actual instance. **This is exactly the added bonus this package offers.**

## <small>small</small> Caveat (Immediate Creation of Models)

Sometimes we want to create models just as a part of the test's setup but we never really need to directly interact with them.
Factor will not immediately create the models but will decide in real-time when they need to be created.
This behavior will break your tests if you rely on the models to exist in the database only.
To overcome this shortcoming, Factor also provides a `now()` method which will let you use it's elegant and expressive syntax but tell to immediately create the models:

```php
create(User::class);
// -> 'Kfirba\Factor\PendingModel'
create(User::class)->now();
// -> 'App\User'

create(User::class)->states('admin');
// -> 'Kfirba\Factor\PendingModel'
create(User::class)->states('admin')->now();
// -> 'App\User'
```

## License
Factor is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).