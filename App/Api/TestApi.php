<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/7/6
 * Time: 23:43
 */

namespace App\Api;


use App\Exception\HttpDecodeException;
use EasySwoole\HttpClient\Bean\Response;

class TestApi extends Base
{
    const API_TEST_SUCCESS_JSON = '/api/test/testSuccessJson';
    const API_TEST_ERROR_JSON = '/api/test/testErrorJson';
    const API_TEST_ERROR = '/api/test/testError';

    public function __construct()
    {
        parent::__construct();
        $this->baseUrl = 'http://127.0.0.1:8500';
    }

    public function getTestSuccessJson()
    {
        return $this->get(self::API_TEST_SUCCESS_JSON, ['a' => 1, 'b' => 2]);
    }

    public function getTestErrorJson()
    {
        return $this->get(self::API_TEST_ERROR_JSON, ['a' => 1, 'b' => 2]);
    }

    public function getTestError()
    {
        return $this->get(self::API_TEST_ERROR, ['a' => 1, 'b' => 2]);
    }

    function generateResponse(Response $response)
    {
        return $this->generateResponseByJson($response);
    }

    function checkResponseData($data)
    {
        if (! isset($data['code']) || $data['code'] != 200) {
            throw new HttpDecodeException("data code is not 200");
        }
    }
}
