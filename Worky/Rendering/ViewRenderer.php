<?php

namespace Worky\Rendering;

class ViewRenderer
{
    /**
     * @var string
     */
    private $viewDir;

    /**
     * Keys are helper aliases, values are helper object instances
     *
     * @var array
     */
    private $viewHelpers;

    public function __construct($viewDir, $viewHelpers = [])
    {
        $this->viewDir = $viewDir;
        $this->viewHelpers = $viewHelpers;
    }

    /**
     * Renders a view
     *
     * @param string $viewPath
     * @param array $parameters Parameters that will be available to view
     *
     * @return string
     *
     * @throws \Exception
     */
    public function render($viewPath, $parameters = [])
    {
        $viewPath = $this->viewDir . '/' . $viewPath;

        if (!file_exists($viewPath)) {
            throw new \Exception(sprintf('Could not find view file %s', $viewPath));
        }

        $renderingClosure = function ($viewPath, $parameters) {
            extract($this->viewHelpers);
            extract($parameters);
            ob_start();
            require $viewPath;
            return ob_get_clean();
        };

        return $renderingClosure($viewPath, $parameters);
    }
} 