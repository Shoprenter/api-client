<?php

namespace ShopRenter;

use ShopRenter\Factory\ResponseParserFactory;

class ApiResponse
{
    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var string
     */
    protected $contentType;

    /**
     * @var int
     */
    protected $contentLength;

    /**
     * @var string
     */
    protected $location;

    /**
     * @var string
     */
    protected $responseBody;

    /**
     * @var array
     */
    protected $parsedResponseBody;

    /**
     * @param $statusCode
     * @param $contentType
     * @param $location
     * @param $responseBody
     */
    public function __construct($statusCode, $contentType, $location, $responseBody)
    {
        $this->statusCode = $statusCode;
        $this->contentType = $contentType;
        $this->contentLength = strlen($responseBody);
        $this->location = $location;
        $this->responseBody = $responseBody;

        if ($this->responseBody) {
            $parserFactory = new ResponseParserFactory();
            $parser = $parserFactory->createParser($this->contentType);
            $parser->setResponse($this->responseBody);
            $this->parsedResponseBody = $parser->parse();
        } else {
            $this->parsedResponseBody = '';
        }
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return int
     */
    public function getContentLength()
    {
        return $this->contentLength;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }

    /**
     * @return array
     */
    public function getParsedResponseBody()
    {
        return $this->parsedResponseBody;
    }
}
