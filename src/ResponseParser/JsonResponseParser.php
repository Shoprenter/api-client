<?php

namespace ShopRenter\ResponseParser;

class JsonResponseParser extends AbstractResponseParser implements ResponseParserInterface
{
    /**
     * @return array
     */
    public function parse()
    {
        return json_decode($this->response, true);
    }
}
