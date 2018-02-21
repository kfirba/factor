# Factor

Short and expressive way to use Laravel's model factories.

## Install
```bash
$ composer require --dev kfirba/factor
```

## Usage

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

## License
Factor is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).