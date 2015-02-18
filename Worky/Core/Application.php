<?php

namespace Worky\Core;

use Worky\DependencyInjection\Container;
use Worky\Http\Request;

/**
 * Base Application class. It is in charge of handling requests and returning responses.
 */
abstract class Application
{
    /**
     * @var Container
     */
    protected $container;

    public function __construct()
    {
        $this->container = new Container();

        // Register framework services within container
        $provider = new FrameworkServicesProvider();
        $provider->provide($this->container);

        $this->boot();
    }

    abstract protected function boot();

    public function handle(Request $request)
    {
        $this->container->set('request', $request);
        $action = $this->container->get('action_resolver')->resolve($request);

        return call_user_func($action->callable, $action->parameters);
    }
}
