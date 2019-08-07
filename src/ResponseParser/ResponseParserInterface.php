<?php

namespace ShopRenter\ResponseParser;

interface ResponseParserInterface
{
    /**
     * @param string $response
     */
    public function setResponse($response);

    /**
     * @return array
     */
    public function parse();
}
