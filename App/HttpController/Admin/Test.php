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
use App\Helper\Helper;
use App\Helper\RedisLock;
use App\Helper\Validate\Validate;
use App\Model\ComStatic;
use App\Model\TestUserModel;
use App\Service\Factory\Factory;
use EasySwoole\Utility\Random;

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

    public function testValidate()
    {
        $validate = new Validate();
        $validateArray = $validate->validateArray('test');
        $validateArray->addColumn('one')->required()->integer();
        $validateArray->addColumn('two')->required()->notEmpty();

        $validateAssociate = $validate->validateAssociate('two');
        $validateAssociate->addColumn('a')->notEmpty();
        $data = [
            'test' => [
                [
                    'one' => 111, 'two' => 'asdfasdf'
                ]
            ],
            'two' => ['a' => 'asdfasdf'],
        ];
        $validate->validOrThrowNewException($data);
        return $this->writeSuccess('yes', $validate->getVerifiedData());
    }

    public function testCommand()
    {
        $test = new \App\Command\Test();
        $test->exec(['stop']);
    }

    public function header()
    {
        $header = $this->request()->getHeaders();
        $this->writeSuccess($header);
    }

    public function testRedisLock()
    {
        $lock = new RedisLock("test_123", 1000, '123456');
        // $result = $lock->acquire();
        $result = $lock->release();
        console("===>", $result);

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

    public function testFactory()
    {
        return $this->writeSuccess("", [
            ucfirst("what the fuck"),
            ucwords("what the fuck"),
        ]);
    }

    public function testStatic()
    {
        $count = ComStatic::getInstance()->get();
        return $this->writeSuccess("index:". $count);
    }

    public function testSql()
    {

    }
}
