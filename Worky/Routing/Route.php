<?php

namespace Worky\Routing;

class Route
{
    public $name;

    public $path;

    public $controller;

    public $method;

    public function __construct($name, $path, $controller, $method = 'GET')
    {
        $this->name = $name;
        $this->path = $path;
        $this->controller = $controller;
        $this->method = $method;
    }
}
