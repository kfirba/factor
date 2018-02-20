<?php

namespace Tests;

use App\User;
use Kfirba\PendingModel;
use PHPUnit\Framework\TestCase;

class FactorTest extends TestCase
{
    /** @test */
    function it_returns_a_pending_model_instance()
    {
        $createInstance = create(User::class);
        $makeInstance = make(User::class);

        $this->assertInstanceOf(PendingModel::class, $createInstance);
        $this->assertInstanceOf(PendingModel::class, $makeInstance);
    }

    /** @test */
    function it_accepts_a_state()
    {
        // create a pending model and work on it.
    }
}

namespace App;

class User {
    // ...
}
