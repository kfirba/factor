<?php

use Kfirba\PendingModel;

function create($model, $overrides = [], $times = 1)
{
    return new PendingModel('create', $model, $overrides, $times);
}

function make($model, $overrides = [], $times = 1)
{
    return new PendingModel('make', $model, $overrides, $times);
}