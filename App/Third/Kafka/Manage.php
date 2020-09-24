<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/7/4
 * Time: 20:56
 */

namespace App\Third\Kafka;


use App\Helper\Helper;
use App\Provider\MysqlProvider;
use App\Provider\RedisProvider;
use App\Third\AbstractConfig;
use App\Third\AbstractManager;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\Kafka\Consumer;
use Swoole\Process\Pool;
use Swoole\Timer;

/**
 * kafka 消费者管理
 * Class Manage
 *
 * @package App\Third\Kafka
 */
class Manage extends AbstractManager
{

    /**
     * 启动进程池处理队列
     * 目前部分配置写死
     */
    protected function dealStart()
    {
        $config = $this->config->getConfig();
        $poolConfig = $this->config->getPoolConfig();
        $childName = $this->config->getChildProcessName();

        $pool = new Pool($poolConfig['workerNum'], 0, 0, true);

        $table = $this->config->getTable();
        $atomic = $this->config->getAtomic();

        \Swoole\Process::daemon();
        \Swoole\Process::signal(SIGTERM, function (Pool $pool) {
            $pool->shutdown();
            unset($pool);
        });

        $pool->on("WorkerStart", function (Pool $pool, $workerId) use ($childName, $config, $table, $atomic) {
            $processName = "{$childName}_{$workerId}";
            $pool->getProcess()->name($processName);
            if ($table->exist($workerId) && $table->get('running') == false) {
                sleep(2);

                return null;
            }
            RedisProvider::register();
            MysqlProvider::initialize();
            $running = true;

            $table->set($workerId, [
                'workId' => $workerId,
                'running' => $running,
            ]);
            $consumers = [];
            foreach (range(0, 1) as $index) {
                # 测试不同group 订阅同一个topic topic写死
                $config['topic'] = 'test_group';
                $config['group'] = 'test_group';
                // if ($workerId == 0) {
                //     $config['group'] = 'g0';
                // } else {
                //     $config['group'] = 'g1';
                // }
                $consumers[] = (new Process($config))->getConsumer();
            }
            pcntl_async_signals(true);
            pcntl_signal(SIGTERM, function () use ($table, $consumers, &$running, $workerId) {
                Logger::getInstance()->info("exit ... {$workerId}");
                $row = $table->get($workerId);
                $running = false;
                $row['running'] = $running;
                $table->set($workerId, $row);
                foreach ($consumers as $consumer) {
                    $consumer->stop();
                }
                Timer::clearAll();
            });

            go(function () use ($atomic, $workerId, &$running) {
                while (true) {
                    if ($workerId == 0) {
                        go(function () use ($atomic) {
                            $count = $atomic->get();
                            if ($count > 0) {
                                $redis = get_redis('redis');
                                $redis->incrBy('queue_count', $count);
                                unset($redis);
                                $atomic->sub($count);
                            }
                        });
                    }
                    \co::sleep(2.0);
                    if (! $running) {
                        break;
                    }
                }
            });

            // 设置消费回调
            $func = function ($topic, $partition, $message) use ($workerId, $atomic) {
                $atomic->add();
                $message = $message['message'];
                // \co::sleep(3.0);
                # ["customer ==> ","topic_deal",0,{"offset":4,"size":41,"message":{"crc":81777897,"magic":1,"attr":0,"timestamp":1593952938,"key":"what","value":"i am topic_deal"}}]
                try {
                    $order = new OrderConsumer($topic, $partition, $message);
                    $order->setWorkerId($workerId);
                    $order->run();
                } catch (\Exception $e) {
                    Helper::error("消费异常", null, $e);
                }
            };
            # 多消费者协程订阅
            foreach ($consumers as $consumer) {
                /**
                 * @var $consumer Consumer
                 */
                go(function () use ($func, $consumer) {
                    $consumer->subscribe($func);
                });
            }

        });

        $pool->on("WorkerStop", function ($pool, $workerId) {
            echo "Worker#{$workerId} is stopped\n";
        });

        $pool->start();
    }

    /**
     * 初始化配置
     */
    protected function init()
    {
        parent::init();
    }

    protected function setConfig()
    {
        $this->config = new Config();
    }

    /**
     * @return Config|AbstractConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

}
