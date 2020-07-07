<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/27
 * Time: 17:26
 */

namespace App\HttpController\Admin;


use App\Api\TestApi;

class Test extends Base
{
    public function index()
    {
        $bean = new \EasySwoole\ElasticSearch\RequestBean\Search();
        $bean->setIndex('megacorp');
        $bean->setType('employee');
        $bean->setBody(['query' => ['match' => ['last_name' => 'Smith']]]);
        $response = get_elastic_search()->search($bean)->getBody();
        $this->writeSuccess('', json_decode($response, true));
    }

    public function header()
    {
        $header = $this->request()->getHeaders();
        $this->writeSuccess($header);
    }

    public function testUnique()
    {
        $result = [];
        foreach (range(0, 10) as $index) {
            $result[] = uniqid();
        }

        return $this->writeSuccess('成功', array_unique($result));
    }

    public function testApi()
    {
        $api = new TestApi();
        $result = $api->getTestError();

        return $this->writeSuccess('', $result);
    }
}
