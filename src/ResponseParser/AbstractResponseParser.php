<?php

namespace ShopRenter\ResponseParser;

abstract class AbstractResponseParser
{
    /**
     * @var string
     */
    protected $response;

    /**
     * @param string $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return array
     */
    abstract public function parse();
}
