<?php

namespace Kfirba\Factor;

use Countable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class PendingModel implements Countable
{
    /**
     * The underlying model instance.
     *
     * @var Model
     */
    protected $model;

    /**
     * 'create'/'make' action name.
     *
     * @var string
     */
    protected $action;

    /**
     * The model's class path.
     *
     * @var string
     */
    protected $abstract;

    /**
     * Attributes to override.
     *
     * @var array
     */
    protected $overrides;

    /**
     * Number of instances to create.
     *
     * @var int
     */
    protected $times;

    /**
     * Model state.
     *
     * @var array|mixed
     */
    protected $states;

    public function __construct($action, $abstract, $overrides = [], $times = 1)
    {
        $this->action = $action;
        $this->abstract = $abstract;
        $this->overrides = $overrides;
        $this->times = $times;

        $this->swap();
    }

    /**
     * Delegate attribute access to the underlying model.
     *
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->model()->{$name};
    }

    /**
     * Delegate method calls to the underlying model.
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->model()->{$name}(...$arguments);
    }

    /**
     * Swap the 'times' and 'overrides' parameters when needed.
     */
    public function swap()
    {
        if (is_integer($this->overrides)) {
            $temp = $this->times;
            $this->times = $this->overrides;
            $this->overrides = $temp;
        }
    }

    /**
     * Set the states to be applied to the model.
     *
     * @param array|mixed $states
     *
     * @return $this
     */
    public function states($states)
    {
        $this->states = $states;

        return $this;
    }

    /**
     * Creates and caches the model instance.
     *
     * @return Model
     */
    public function now()
    {
        return $this->model();
    }

    /**
     * Get the underlying model instance.
     *
     * @return Model
     */
    public function model()
    {
        if (empty($this->model)) {
            $this->model = $this->buildModel();
        }

        return $this->model;
    }

    /**
     * Builds the underlying model.
     *
     * @return Model|Collection
     */
    public function buildModel()
    {
        $builder = factory($this->abstract)->times($this->times === 1 ? null : $this->times);

        if (! empty($this->states)) {
            $builder->states($this->states);
        }

        return $builder->{$this->action}($this->overrides);
    }

    /**
     * Count elements of an object.
     *
     * @return int
     */
    public function count()
    {
        if (method_exists($this->model(), 'count')) {
            return $this->model()->count();
        }

        throw new \BadMethodCallException('This object is not countable');
    }
}
