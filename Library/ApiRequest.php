<?php
namespace Afiqiqmal\SolatJakim\Library;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiRequest
{
    protected $baseUrl = null;
    protected $requestBody = [];
    protected $param = [];
    protected $method = "GET";
    protected $requestUrl = null;
    protected $header = null;
    protected $isRaw = false;
    protected $initOption = [];

    function baseUrl($url)
    {
        $this->baseUrl = $url;
        return $this;
    }

    function setParam($option = [])
    {
        $this->param = $option;
        return $this;
    }

    function setRequestBody($param = [])
    {
        $this->requestBody = $param;
        return $this;
    }

    function getMethod()
    {
        $this->method = "GET";
        return $this;
    }

    function postMethod()
    {
        $this->method = "POST";
        return $this;
    }

    function patchMethod()
    {
        $this->method = "PATCH";
        return $this;
    }

    function deleteMethod()
    {
        $this->method = "DELETE";
        return $this;
    }

    function setMethod($method = "GET")
    {
        $this->method = $method;
        return $this;
    }

    function setHeader($header = null)
    {
        $this->header = $header;
        return $this;
    }

    function requestUrl($requestUrl)
    {
        $this->requestUrl = $requestUrl;
        return $this;
    }

    function getRaw()
    {
        $this->isRaw = true;
        return $this;
    }

    function fetch($requestUrl = null, $params = [], $method = null, $header = null)
    {
        if ($requestUrl) {
            $this->requestUrl = $requestUrl;
        }

        if (count($params) > 0) {
            $this->requestBody = $params;
        }

        if (count($this->param) > 0){
            $this->requestBody = $this->param;
        }

        if ($method) {
            $this->method = $method;
        }

        if ($header) {
            $this->header = $header;
        }

        if (!$this->baseUrl) {
            throw new \RuntimeException('Base URL need to be set!!');
        }

        if (substr($this->baseUrl, -1) != '/') {
            $this->baseUrl = $this->baseUrl."/";
        }

        if ($this->requestUrl && substr($this->requestUrl, -1) == "/") {
            $this->requestUrl = ltrim($this->requestUrl, "/");
        }

        if (!$this->requestUrl) {
            $this->baseUrl = rtrim($this->baseUrl, '/');
        }

        $url = $this->baseUrl . $this->requestUrl;

        try {
            $client = new Client();
            switch ($this->method) {
                case SOLAT_METHOD_GET:
                    $param = [
                        'query' => $this->requestBody,
                        'headers' => $this->header
                    ];
                    break;
                case SOLAT_METHOD_POST:
                    $param = [
                        'form_params' => $this->requestBody,
                        'headers' => $this->header
                    ];
                    break;
                case SOLAT_METHOD_PATCH:
                    $param = [
                        'form_params' => $this->requestBody,
                        'headers' => $this->header
                    ];
                    break;
                case SOLAT_METHOD_DELETE:
                    $param = [
                        'form_params' => $this->requestBody,
                        'headers' => $this->header
                    ];
                    break;
                default:
                    $param = null;
                    break;
            }
            $param = array_merge($param, $this->param);
            $response = $client->request($this->method, $url, $param);

            return [
                'error' => false,
                'body' =>  $this->isRaw ? $response->getBody()->getContents() : json_decode($response->getBody(), true),
                'header' =>  $response->getHeaders(),
                'status_code' => $response->getStatusCode()
            ];
        } catch (\Exception $ex) {
            return [
                'error' => true,
                'message' => 'Server is currently unavailable',
                'reference' => $ex->getMessage(),
                'status_code' => strpos($ex->getMessage(), 'SSL_ERROR_SYSCALL') !== false ? 403 : 400
            ];
        } catch (GuzzleException $ex) {
            return [
                'error' => true,
                'message' => 'Server is currently unavailable',
                'reference' => $ex->getMessage(),
                'status_code' => strpos($ex->getMessage(), 'SSL_ERROR_SYSCALL') !== false ? 403 : 400
            ];
        }


    }
}
