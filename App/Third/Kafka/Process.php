<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/7/5
 * Time: 23:41
 */

namespace App\Third\Kafka;


use App\Provider\RedisProvider;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\Kafka\Config\ConsumerConfig;
use EasySwoole\Kafka\Kafka;
use Swoole\Atomic;
use Swoole\Process\Pool;
use Swoole\Table;
use Swoole\Timer;

class Process
{

    /**
     * @var array
     */
    private $config;
    /**
     * @var Pool
     */
    private $pool;
    /**
     * @var Table
     */
    private $table;
    /**
     * @var Atomic
     */
    private $atomic;

    private $workerId;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getConsumer()
    {
        $config = $this->config;
        $customerConfig = new ConsumerConfig();
        $customerConfig->setRefreshIntervalMs($config['refreshIntervalMs']);
        $customerConfig->setMetadataBrokerList(implode(",", $config['brokers']));
        $customerConfig->setBrokerVersion($config['version']);
        $customerConfig->setConsumeMode($config['consume_mode']);

        $customerConfig->setGroupId('group_test');
        $customerConfig->setTopics(['topic_test']);

        $customerConfig->setOffsetReset('earliest');

        $kafka = new Kafka($customerConfig);

        return $kafka->consumer();
    }
}
