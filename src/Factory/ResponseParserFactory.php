<?php

namespace ShopRenter\Factory;

use InvalidArgumentException;
use ShopRenter\ResponseParser\HtmlResponseParser;
use ShopRenter\ResponseParser\JsonResponseParser;
use ShopRenter\ResponseParser\ResponseParserInterface;
use ShopRenter\ResponseParser\XmlResponseParser;

class ResponseParserFactory
{
    /**
     * @param string $contentType
     * @return ResponseParserInterface
     */
    public function createParser($contentType)
    {
        if (false !== strpos($contentType, 'application/xml')) {
            return new XmlResponseParser();
        }

        if (false !== strpos($contentType, 'application/json')) {
            return new JsonResponseParser();
        }

        if (false !== strpos($contentType, 'text/html')) {
            return new HtmlResponseParser();
        }

        throw new InvalidArgumentException('Invalid content type given!');
    }
}
