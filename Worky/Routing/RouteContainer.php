<?php

namespace Worky\Routing;

class RouteContainer
{
    /**
     * @var Route[]
     */
    private $routes = [];

    public function add(Route $route)
    {
        $this->routes[$route->name] = $route;
    }

    public function get($name)
    {
        if (isset($this->routes[$name])) {
            return $this->routes[$name];
        }

        throw new \Exception(sprintf('Route %s does not exist', $name));
    }

    public function getAll()
    {
        return $this->routes;
    }
}