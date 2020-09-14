<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/26
 * Time: 15:13
 */

namespace App\Pool;


use EasySwoole\Kafka\Config\ProducerConfig;
use EasySwoole\Kafka\Kafka as EasyKafka;
use EasySwoole\Pool\AbstractPool;

class KafkaProducerPool extends AbstractPool
{
    protected $kafkaConfig;

    public function __construct(\EasySwoole\Pool\Config $conf, $kafkaConfig)
    {
        $this->kafkaConfig = $kafkaConfig;
        parent::__construct($conf);
    }

    protected function createObject()
    {
        $config = $this->kafkaConfig;
        $producerConfig = new ProducerConfig();
        $producerConfig->setMetadataBrokerList(implode(",", $config['brokers']));
        $producerConfig->setBrokerVersion($config['version']);
        $producerConfig->setRequiredAck(1);
        $kafka = new EasyKafka($producerConfig);

        return $kafka->producer();
    }
}
