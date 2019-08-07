<?php

namespace ShopRenter\ResponseParser;

class HtmlResponseParser extends AbstractResponseParser implements ResponseParserInterface
{
    /**
     * @return string
     */
    public function parse()
    {
        return $this->response;
    }
}
