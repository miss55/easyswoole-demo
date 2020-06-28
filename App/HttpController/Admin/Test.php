<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/27
 * Time: 17:26
 */

namespace App\HttpController\Admin;


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
}
