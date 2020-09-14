<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/7/4
 * Time: 21:23
 */

namespace App\HttpController\Admin;


class Kafka extends Auth
{
    private $index;

    /**
     * @throws \EasySwoole\Kafka\Exception\Config
     * @throws \EasySwoole\Kafka\Exception\Exception
     * @throws \EasySwoole\Kafka\Exception\InvalidRecordInSet
     */
    public function test()
    {

        if (empty($this->index)) {
            $this->index = 0;
        }
        $producer = get_kafka_producer();

        for ($i = 0; $i < 1; $i++) {
            $result = $producer->send([
                [
                    'topic' => 'test_group',
                    'value' => json_encode_chinese([
                            'name' => "商品名称--{$this->index}===" . mt_rand(1, 1000),
                            // 'num' => mt_rand(1, 5),
                            // 'total' => ((mt_rand(200, 100000) * 1.00) / 100). "",
                            // 'status' => 1,
                        ]),
                    'key' => '',
                ],
            ]);
            $this->index++;
        }
        $this->writeSuccess("ok", $result);
    }
}
