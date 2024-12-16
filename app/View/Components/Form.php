<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Form extends Component
{
    public $method;
    public $action;
    public $showBackButton;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($method, $action, $showBackButton = false)
    {
        $this->method = $method;
        $this->action = $action;
        $this->showBackButton = $showBackButton;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form');
    }
}

