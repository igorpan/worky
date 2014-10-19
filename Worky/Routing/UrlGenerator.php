<?php

namespace Worky\Routing;

use Worky\Http\Request;

class UrlGenerator
{
    /**
     * @var RouteContainer
     */
    private $routeContainer;

    /**
     * @var Request
     */
    private $request;

    public function __construct(RouteContainer $routeContainer, Request $request)
    {
        $this->routeContainer = $routeContainer;
        $this->request = $request;
    }

    /**
     * Generates an url for route given
     *
     * @param string $name
     * @param array  $params
     *
     * @return string
     */
    public function url($name, array $params = [])
    {
        return $this->request->server['BASE'] . $this->path($name, $params);
    }

    /**
     * Generates a path for route given
     *
     * @param string $name
     * @param array $params
     *
     * @return string
     */
    public function path($name, array $params = [])
    {
        $route = $this->routeContainer->get($name);

        $paramNames = array_map(
            function ($param) {
                return ":$param";
            },
            array_keys($params)
        );

        $path = str_replace($paramNames, array_values($params), $route->path);

        return $path;
    }
}