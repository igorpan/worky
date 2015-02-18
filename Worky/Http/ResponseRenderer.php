<?php

namespace Worky\Http;

class ResponseRenderer
{
    /**
     * Renders response.
     *
     * @param Response $response
     */
    public function render(Response $response)
    {
        header(sprintf('HTTP/1.1 %s', $response->status));
        foreach ($response->headers as $header) {
            header($header);
        }
        if ($response->body) {
            echo $response->body;
        }
    }
}
