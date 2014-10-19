<?php

namespace Worky\DependencyInjection;

class Container
{
    /**
     * @var array
     */
    private $providers = [];

    /**
     * @var array
     */
    private $provided = [];

    /**
     * @var array
     */
    private $marks = [];

    /**
     * @var array
     */
    public $parameters = [];

    /**
     * Registers a service provider
     *
     * @param string $name
     * @param callable $provider
     */
    public function provide($name, $provider)
    {
        $this->providers[$name] = $provider;
    }

    /**
     * Marks a service with mark given
     *
     * @param string $serviceName
     * @param string $mark
     * @param array  $params
     */
    public function mark($serviceName, $mark, $params = [])
    {
        if (!isset($this->marks[$mark])) {
            $this->marks[$mark] = [];
        }

        $this->marks[$mark][$serviceName] = $params;
    }

    /**
     * Gets names of all services marked with mark given
     *
     * @param string $mark
     *
     * @return array Service names are keys, values are mark parameters
     */
    public function getMarkedServices($mark)
    {
        if (!isset($this->marks[$mark])) {
            return [];
        }

        return $this->marks[$mark];
    }

    /**
     * Gets a service
     *
     * @param string $name
     *
     * @return object
     *
     * @throws \Exception If service doesn't exist
     */
    public function get($name)
    {
        if (!isset($this->provided[$name])) {
            if (!isset($this->providers[$name])) {
                throw new \Exception(sprintf('Provider for service %s not registered', $name));
            }

            $this->provided[$name] = $this->providers[$name]($this);
        }

        return $this->provided[$name];
    }

    /**
     * Sets a service
     *
     * @param string $name
     * @param object $serviceObject
     */
    public function set($name, $serviceObject)
		{
				$this->provided[$name] = $serviceObject;
		}
} 