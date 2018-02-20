<?php

namespace Tests;

use App\User;
use Kfirba\PendingModel;
use Faker\Generator as Faker;
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
    function it_delegates_back_to_the_factory_class_when_attempting_to_create_multiple_instances()
    {
        $instances = make(User::class, [], 4);

        $this->assertCount(4, $instances);
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
