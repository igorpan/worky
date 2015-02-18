<?php

namespace Worky\Core;

use Worky\DependencyInjection\Container;
use Worky\Http\Request;
use Worky\Http\Response;

class Controller
{
    /**
     * @var \Worky\Http\Request
     */
    protected $request;

    /**
     * @var \Worky\DependencyInjection\Container
     */
    protected $container;

    public function __construct(Request $request, Container $container)
    {
        $this->request = $request;
        $this->container = $container;
    }

    /**
     * @param string $view
     * @param array  $parameters
     *
     * @return Response
     */
    protected function render($view, array $parameters = [])
    {
        $rendered = $this->container->get('view_renderer')->render($view, $parameters);

        return $this->respond($rendered);
    }

    /**
     * Returns a response.
     *
     * @param string $content
     * @param int    $status
     * @param array  $headers
     *
     * @return Response
     */
    protected function respond($content, $status = 200, $headers = [])
    {
        return new Response($content, $status, $headers);
    }

    /**
     * Returns redirect response.
     *
     * @param string $path
     *
     * @return Response
     */
    protected function redirect($path)
    {
        return new Response(null, 302, [sprintf('Location: %s%s', $this->request->server['BASE'], $path)]);
    }

    /**
     * Returns redirect response to given route.
     *
     * @param string $routeName
     * @param array  $routeParams
     *
     * @return Response
     */
    protected function redirectRoute($routeName, $routeParams = [])
    {
        $generator = $this->container->get('url_generator');

        return $this->redirect($generator->path($routeName, $routeParams));
    }
}
