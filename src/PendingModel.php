<?php

namespace Kfirba\Factor;

use Illuminate\Database\Eloquent\Model;

class PendingModel
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
        $this->ensureModelExists();

        return $this->model->{$name};
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
        $this->ensureModelExists();

        return $this->model->{$name}(...$arguments);
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
     * Builds the underlying model.
     *
     * @return void
     */
    public function buildModel()
    {
        $builder = factory($this->abstract)->times($this->times === 1 ? null : $this->times);

        if (! empty($this->states)) {
            $builder->states($this->states);
        }

        $this->model = $builder->{$this->action}($this->overrides);
    }

    /**
     * Ensures that the underlying model was already built.
     *
     * @return void
     */
    protected function ensureModelExists()
    {
        if (empty($this->model)) {
            $this->buildModel();
        }
    }
}
