<?php

namespace Worky\Http;

class RequestFactory
{
    /**
     * Creates Request object from PHP superglobals
     *
     * @param bool $fakeMethods If true and $_POST['_method'] is provided, real method will be overridden by that value.
     *                          Example:
     *                              Request with $_POST['_method'] == 'DELETE' will be treated as DELETE request instead of POST
     *                              Request with $_POST['_method'] == 'PATCH' will be treated as PATCH request instead of POST
     *
     * @return Request
     */
    public function fromSuperglobals($fakeMethods = true)
    {
        $request = new Request();
        $request->get = $_GET;
        $request->post = $_POST;
        $request->files = $_FILES;
        $request->request = $_REQUEST;
        $request->cookie = $_COOKIE;
        $request->server = $_SERVER;

        if ($fakeMethods) {
            $this->overrideRealMethod($request);
        }

        return $request;
    }

    /**
     * Overrides real request method with fake one
     *
     * @param Request $request
     */
    private function overrideRealMethod(Request $request)
    {
        if ($override = @$request->post['_method']) {
            $request->server['REQUEST_METHOD'] = $override;
        }
    }
} 