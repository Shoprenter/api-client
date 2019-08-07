<?php

namespace ShopRenter;

use Exception;

class ApiCall
{
    /**
     * @var string
     */
    protected $username = '';

    /**
     * @var string
     */
    protected $apiKey = '';

    /**
     * @var string
     */
    protected $response;

    /**
     * @var string
     */
    protected $format = 'json';

    /**
     * @param string $username
     * @param string $apiKey
     */
    public function __construct($username, $apiKey)
    {
        $this->username = $username;
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $data
     * @throws Exception
     * @return ApiResponse
     */
    public function execute($method, $url, array $data = array())
    {
        $this->ensure($this->username !== '', 'Username cannot be empty!');
        $this->ensure($this->apiKey !== '', 'Api key cannot be empty!');

        $curlHandle = curl_init();
        $this->setUrl($curlHandle, $url);
        $this->setAuth($curlHandle);
        $this->setOptions($curlHandle);

        switch ($method) {
            case 'GET':
                $this->executeGet($curlHandle);
                break;
            case 'POST':
                $this->executePost($curlHandle, $data);
                break;
            case 'PUT':
                $this->executePut($curlHandle, $data);
                break;
            case 'DELETE':
                $this->executeDelete($curlHandle);
                break;
            default:
                throw new Exception('Invalid HTTP method');
        }

        return $this->response;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @param $curlHandle
     * @param string $url
     */
    protected function setUrl($curlHandle, $url)
    {
        curl_setopt($curlHandle, CURLOPT_URL, $url);
    }

    /**
     * @param $curlHandle
     */
    protected function setAuth($curlHandle)
    {
        curl_setopt($curlHandle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curlHandle, CURLOPT_USERPWD, $this->username . ':' . $this->apiKey);
    }

    /**
     * @param $curlHandle
     */
    protected function setOptions($curlHandle)
    {
        curl_setopt($curlHandle, CURLOPT_HEADER, 1);
        curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curlHandle, CURLOPT_MAXREDIRS, 5);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, ['Content-type: multipart/form-data']);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, ['Expect:']);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, ['Accept: application/' . $this->format]);
    }

    /**
     * @param $curlHandle
     */
    protected function executeGet($curlHandle)
    {
        $this->doExecute($curlHandle);
    }

    /**
     * @param $curlHandle
     * @param array $data
     */
    protected function executePost($curlHandle, array $data)
    {
        $postFields = [];
        $this->processLevel($postFields, ['data' => $data]);
        curl_setopt($curlHandle, CURLOPT_POST, true);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postFields);

        $this->doExecute($curlHandle);
    }

    /**
     * @param $curlHandle
     * @param array $data
     */
    protected function executePut($curlHandle, array $data)
    {
        $postFields = [];
        $this->processLevel($postFields, ['data' => $data]);
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postFields);

        $this->doExecute($curlHandle);
    }

    /**
     * @param $curlHandle
     */
    protected function executeDelete($curlHandle)
    {
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $this->doExecute($curlHandle);
    }

    /**
     * @param $curlHandle
     * @return void
     */
    protected function doExecute($curlHandle)
    {
        ob_start();
        curl_exec($curlHandle);
        $content = ob_get_contents();
        ob_end_clean();

        $headerSize = curl_getinfo($curlHandle, CURLINFO_HEADER_SIZE);

        $headers = substr($content, 0, $headerSize);
        $responseBody = substr($content, $headerSize);

        $statusCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($curlHandle, CURLINFO_CONTENT_TYPE);

        preg_match("!\r\n(?:Location|URI): *(.*?) *\r\n!", $headers, $matches);

        $location = isset($matches[1]) ? $matches[1] : '';

        $this->response = new ApiResponse($statusCode, $contentType, $location, $responseBody);

        curl_close($curlHandle);
    }

    /**
     * @param bool $bool
     * @param string $errorMessage
     * @throws Exception
     */
    protected function ensure($bool, $errorMessage)
    {
        if (!$bool) {
            throw new Exception($errorMessage);
        }
    }

    /**
     * @param array $result
     * @param array $source
     * @param null $previousKey
     */
    protected function processLevel(array &$result, array $source, $previousKey = null)
    {
        foreach ($source as $k => $value) {
            $key = $previousKey ? "{$previousKey}[{$k}]" : $k;
            if (!is_array($value)) {
                $result[$key] = $value;
            } else {
                $this->processLevel($result, $value, $key);
            }
        }
    }
}
