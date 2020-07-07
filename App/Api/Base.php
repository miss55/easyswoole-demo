<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/7/6
 * Time: 22:08
 */

namespace App\Api;


use App\Exception\HttpDecodeException;
use App\Helper\Helper;
use EasySwoole\EasySwoole\Core;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\HttpClient\Bean\Response;
use EasySwoole\HttpClient\HttpClient;

abstract class Base
{

    /**
     * 是否是单例client 默认不单例
     *
     * @var bool
     */
    private $singletonClient = false;

    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var array
     */
    private $requestRows;

    /**
     * 日志存储目录
     *
     * @var string
     */
    private $category;

    /**
     * @var string
     */
    protected $baseUrl;

    const LOGGER_TYPE_REQUEST = 'request';
    const LOGGER_TYPE_RESPONSE = 'response';
    const LOGGER_TYPE_ERROR = 'error';

    public function __construct()
    {
        $this->requestRows = [];
        $this->logger = Logger::getInstance();
        $this->initCategory();
    }

    /**
     * get 请求
     *
     * @param string $url
     * @param array  $data
     * @param array  $headers
     *
     * @return mixed
     * @throws \EasySwoole\HttpClient\Exception\InvalidUrl
     */
    public function get($url, $data = [], $headers = [])
    {
        $query = http_build_query($data);
        $url = $this->getUrl($url);
        $url .= strpos($url, '?') === false ? '?' . $query : $query;
        $client = $this->getClient();
        $client->setUrl($url);
        $unique = uniqid();
        $this->addLoggerRequestStack($unique, compact('url', 'query', 'headers'));

        return $this->_checkResponse($unique, $client->get($headers));
    }

    /**
     * @param string $url
     * @param array  $data
     * @param array  $headers
     *
     * @return mixed
     * @throws \EasySwoole\HttpClient\Exception\InvalidUrl
     */
    public function post($url, $data = [], $headers = [])
    {
        $client = $this->getClient();
        $url = $this->getUrl($url);
        $client->setUrl($url);
        $unique = uniqid();
        $this->addLoggerRequestStack($unique, compact('url', 'query', 'headers'));

        return $this->_checkResponse($unique, $client->post($data, $headers));
    }

    /**
     * @param string $url
     * @param array  $data
     * @param array  $headers
     *
     * @return mixed
     * @throws \EasySwoole\HttpClient\Exception\InvalidUrl
     */
    public function postJson($url, $data = [], $headers = [])
    {
        $client = $this->getClient();
        $url = $this->getUrl($url);
        $client->setUrl($url);
        $unique = uniqid();
        $this->addLoggerRequestStack($unique, compact('url', 'query', 'headers'));

        return $this->_checkResponse($unique, $client->postJson(json_encode_chinese($data), $headers));
    }


    public function getClient()
    {
        if (! $this->singletonClient) {
            return new HttpClient();
        }
        if (empty($this->client)) {
            $this->client = new HttpClient();
        }

        return $this->client;
    }

    private function _checkResponse($unique, Response $response)
    {
        if ($response->getStatusCode() != '200') {
            $this->addLoggerErrorStack($unique, ['code' => $response->getStatusCode(), 'body' => $response->getBody()]);
        }

        if ($this->isDebug()) {
            $this->addLoggerResponseStack($unique,
                ['code' => $response->getStatusCode(), 'body' => $response->getBody()]);
        }

        try {
            $data = $this->generateResponse($response);
            $this->checkResponseData($data);
        } catch (HttpDecodeException $e) {
            $this->addLoggerErrorStack($unique, [
                'code' => $response->getStatusCode(),
                'data' => $e->data ? $e->data : $response->getBody(),
            ]);
        } finally {
            $this->flushLoggerStack($unique);
        }

        return $data;
    }

    abstract function generateResponse(Response $response);

    abstract function checkResponseData($data);

    /**
     * 返回字符串
     *
     * @param Response $response
     *
     * @return string
     */
    public function generateResponseByString(Response $response)
    {
        return $response->getBody();
    }

    /**
     * 返回json格式
     *
     * @param Response $response
     *
     * @return array
     * @throws HttpDecodeException
     */
    public function generateResponseByJson(Response $response)
    {
        $body = $response->getBody();
        if (! is_json_str($body)) {
            throw new HttpDecodeException('不符合json字符串', 0, $body);
        }
        $data = json_decode($body, true);
        if (json_last_error()) {
            throw new HttpDecodeException(json_last_error_msg(), 0, $body);
        }

        return $data;

    }

    private function addLoggerRequestStack($unique, $data)
    {
        $this->addLoggerStack($unique, self::LOGGER_TYPE_REQUEST, $data);
    }

    protected function addLoggerResponseStack($unique, $data)
    {
        $this->addLoggerStack($unique, self::LOGGER_TYPE_RESPONSE, $data);
    }

    protected function addLoggerErrorStack($unique, $data)
    {
        $this->addLoggerStack($unique, self::LOGGER_TYPE_ERROR, $data);
    }

    private function addLoggerStack($unique, $type, $data)
    {
        if (! isset($this->requestRows[$unique])) {
            $this->requestRows[$unique] = [];
        }
        $this->requestRows[$unique][$type] = $data;
    }


    private function flushLoggerStack($unique)
    {
        try {
            if (count($this->requestRows[$unique]) < 2) {
                unset($this->requestRows[$unique]);

                return;
            }
            $rows = $this->requestRows[$unique];
            if (isset($rows[self::LOGGER_TYPE_ERROR])) {
                $this->error($rows);
            }
            if (isset($rows[self::LOGGER_TYPE_RESPONSE])) {
                $this->notice($rows);
            }
        } finally {
            unset($this->requestRows[$unique]);
        }

    }

    protected function isDebug()
    {
        return Core::getInstance()->isDev();
    }

    private function initCategory()
    {
        $name = get_basename(get_class($this));
        $this->category = "api.{$name}";
    }

    private function error($rows)
    {
        Helper::error(self::LOGGER_TYPE_REQUEST, $rows[self::LOGGER_TYPE_REQUEST], null, $this->category);
        Helper::error(self::LOGGER_TYPE_ERROR, $rows[self::LOGGER_TYPE_ERROR], null, $this->category);
    }

    private function notice($rows)
    {
        Helper::notice(self::LOGGER_TYPE_REQUEST, $rows[self::LOGGER_TYPE_REQUEST], $this->category);
        Helper::notice(self::LOGGER_TYPE_RESPONSE, $rows[self::LOGGER_TYPE_RESPONSE], $this->category);
    }

    private function getUrl($path)
    {
        if ($path[0] === '/') {
            return $this->baseUrl . $path;
        }

        return $path;
    }

}
