<?php

namespace Worky\Http;

class Request
{
    public $get;

    public $post;

    public $files;

    public $request;

    public $server;

    public $cookie;

    /**
     * @return string
     */
    public function getPathInfo()
    {
        $base = $this->server['BASE'];
        $uri  = $this->server['REQUEST_URI'];

        // Strip base from uri
        $pathInfo = substr($uri, strlen($base));

        // If it contains query string, remove it
        if (false !== $pos = strpos($pathInfo, '?')) {
            $pathInfo = substr($pathInfo, 0, $pos);
        }

        return $pathInfo;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->server['REQUEST_METHOD'];
    }
}
