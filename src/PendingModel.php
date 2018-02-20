<?php

namespace Kfirba;

class PendingModel
{
    protected $action;

    protected $abstract;

    protected $overrides;

    protected $times;

    public function __construct($action, $abstract, $overrides, $times)
    {

        $this->action = $action;
        $this->abstract = $abstract;
        $this->overrides = $overrides;
        $this->times = $times;
    }
}