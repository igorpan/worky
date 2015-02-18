<?php

namespace Worky\Http;

class Response
{
    public $headers = [];

    public $body;

    public $status;

    public function __construct($body, $status = 200, $headers = [])
    {
        $this->body = $body;
        $this->status = $status;
        $this->headers = $headers;
    }
}
