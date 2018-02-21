<?php

namespace Tests;

use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Model;
use Kfirba\Factor\PendingModel;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\Factory;

class FactorTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        app()->instance(
            Factory::class,
            Factory::construct(new Faker, getcwd() . '/factories')
        );
    }

    /** @test */
    function it_returns_a_pending_model_instance()
    {
        $makeInstance = make(User::class);
        $createInstance = create(User::class);

        $this->assertInstanceOf(PendingModel::class, $makeInstance);
        $this->assertInstanceOf(PendingModel::class, $createInstance);
    }

    /** @test */
    function it_returns_the_underlying_model_immediately()
    {
        $proxy = make(User::class);
        $instance = make(User::class)->now();
        $stateProxy = make(User::class)->states('admin');
        $stateInstance = make(User::class)->states('admin')->now();

        $this->assertInstanceOf(PendingModel::class, $proxy);
        $this->assertInstanceOf(Model::class, $instance);
        $this->assertInstanceOf(PendingModel::class, $stateProxy);
        $this->assertInstanceOf(Model::class, $stateInstance);
    }

    /** @test */
    function it_delegates_back_to_the_factory_class_when_attempting_to_create_multiple_instances()
    {
        $instances = make(User::class, [], 4);

        $this->assertEquals(4, $instances->count());
    }

    /** @test */
    function it_allows_the_swap_of_the_overrides_and_the_times_parameters()
    {
        $instances = make(User::class, 2, ['name' => 'swap']);

        $this->assertCount(2, $instances);
        $this->assertEquals(['swap', 'swap'], $instances->pluck('name')->toArray());
    }

    /** @test */
    function it_delegates_attributes_access_to_the_underlying_model()
    {
        $pendingModel = make(User::class);

        $this->assertEquals('Fake User', $pendingModel->name);
        $this->assertEquals('fake@example.com', $pendingModel->email);
    }

    /** @test */
    function it_delegates_method_calls_to_the_underlying_model()
    {
        $pendingModel = make(User::class);

        $this->assertEquals('User was called!', $pendingModel->callUser());
    }

    /** @test */
    function it_accepts_overrides()
    {
        $pendingModel = make(User::class, ['name' => 'Override']);

        $this->assertEquals('Override', $pendingModel->name);
    }

    /** @test */
    function it_accepts_a_state()
    {
        $pendingModel = make(User::class)->states('admin');

        $this->assertEquals('Admin', $pendingModel->name);
        $this->assertEquals('admin@example.com', $pendingModel->email);
    }
}

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function callUser()
    {
        return 'User was called!';
    }
}
