<?php

use Kfirba\Factor\PendingModel;

/**
 * Create a collection of models and persist them to the database.
 *
 * @param       $model
 * @param array $overrides
 * @param int   $times
 *
 * @return PendingModel|mixed
 */
function create($model, $overrides = [], $times = 1)
{
    return new PendingModel('create', $model, $overrides, $times);
}

/**
 * Create a collection of models.
 *
 * @param       $model
 * @param array $overrides
 * @param int   $times
 *
 * @return PendingModel|mixed
 */
function make($model, $overrides = [], $times = 1)
{
    return new PendingModel('make', $model, $overrides, $times);
}
