<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/7/4
 * Time: 21:23
 */

namespace App\HttpController\Admin;


use EasySwoole\EasySwoole\Config;
use EasySwoole\Kafka\Config\ProducerConfig;
use EasySwoole\Kafka\Kafka as EasyKafka;

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
        $config = Config::getInstance()->getConf("KAFKA");
        $producerConfig = new ProducerConfig();
        $producerConfig->setMetadataBrokerList(implode(",", $config['brokers']));
        $producerConfig->setBrokerVersion($config['version']);
        $producerConfig->setRequiredAck(1);
        $kafka = new EasyKafka($producerConfig);
        if (empty($this->index)) {
            $this->index = 0;
        }
        for ($i = 0; $i < 20; $i++) {
            $result = $kafka->producer()->send([
                [
                    'topic' => 'topic_test',
                    'value' => "{$this->index}",
                    'key' => '',
                ],
            ]);
            $this->index++;
        }

        $this->writeSuccess("ok", $result);
    }
}
