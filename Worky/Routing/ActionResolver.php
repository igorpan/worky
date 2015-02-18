<?php

namespace Worky\Routing;

use Worky\DependencyInjection\Container;
use Worky\Http\Request;

/**
 * Decides which action should be taken for requests.
 */
class ActionResolver
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var RouteContainer
     */
    private $routeContainer;

    public function __construct(Container $container, RouteContainer $routeContainer)
    {
        $this->container = $container;
        $this->routeContainer = $routeContainer;
    }

    /**
     * Decides which action should be taken for request passed.
     *
     * @param Request $request
     *
     * @throws \Exception When it can't resolve action
     *
     * @return ResolvedAction
     */
    public function resolve(Request $request)
    {
        $path = $request->getPathInfo();

        foreach ($this->routeContainer->getAll() as $route) {
            $parameters = $this->match($path, $request->getMethod(), $route);
            if (null !== $parameters) {
                list($controllerClass, $controllerMethod) = explode('#', $route->controller);
                $controller = new $controllerClass($request, $this->container);

                $resolvedAction = new ResolvedAction();
                $resolvedAction->callable = [$controller, $controllerMethod];
                $resolvedAction->parameters = $parameters;

                return $resolvedAction;
            }
        }

        throw new \Exception(sprintf('Could not match request %s %s to any controller', $request->getMethod(), $path));
    }

    /**
     * Tries to match path with a route. If it matches, returns array of parameters. If not, returns false.
     *
     * @param string $path
     * @param string $method
     * @param Route  $route
     *
     * @return array|null
     */
    private function match($path, $method, Route $route)
    {
        // If request method doesn't match
        if ($method !== $route->method) {
            return;
        }

        $routeRegex = str_replace('/', '\\/', $route->path);
        $routeRegex = preg_replace('/:(\w+)/', '(?<$1>[^\/]+)', $routeRegex);
        $routeRegex = '/^'.$routeRegex.'$/';
        if (preg_match($routeRegex, $path, $matches)) {
            return $matches;
        }

        return;
    }
}
