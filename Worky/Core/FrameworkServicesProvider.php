<?php

namespace Worky\Core;

use Worky\DependencyInjection\Container;

class FrameworkServicesProvider
{
    public function provide(Container $container)
    {
        $container->provide('action_resolver', function ($container) {
            return new \Worky\Routing\ActionResolver($container, $container->get('route_container'));
        });

        $container->provide('route_container', function ($container) {
            return new \Worky\Routing\RouteContainer();
        });

        $container->provide('url_generator', function ($container) {
            return new \Worky\Routing\UrlGenerator($container->get('route_container'), $container->get('request'));
        });
        $container->mark('url_generator', 'view_helper', ['alias' => 'urlGenerator']);

        $container->provide('view_renderer', function ($container) {
            $helpers = $container->getMarkedServices('view_helper');

            $helperInstances = [];
            foreach ($helpers as $serviceName => $markParams) {
                if (!isset($markParams['alias'])) {
                    throw new \Exception('When marking service as view_helper, alias parameter must be provided');
                }
                $helperInstances[$markParams['alias']] = $container->get($serviceName);
            }

            return new \Worky\Rendering\ViewRenderer($container->parameters['view_dir'], $helperInstances);
        });

        $container->provide('pdo', function ($container) {
            $config = $container->parameters['pdo'];

            $options = @$config['options'];
            $dsn = $config['driver'];
            $username = @$config['username'];
            $password = @$config['password'];

            if (is_array($options)) {
                $dsnOptions = '';
                foreach ($options as $key => $value) {
                    $dsnOptions .= "$key=$value;";
                }
            } else if (is_string($options)) {
                $dsnOptions = $options;
            }

            if ($options) {
                $dsn .= ":$dsnOptions";
            }

            $pdo = new \PDO($dsn, $username, $password);

            return $pdo;
        });
    }
}