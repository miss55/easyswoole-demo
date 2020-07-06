<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/7/4
 * Time: 20:56
 */

namespace App\Third\Kafka;


use App\Provider\RedisProvider;
use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\Kafka\Config\ConsumerConfig;
use EasySwoole\Kafka\Kafka;
use Swoole\IDEHelper\StubGenerators\Swoole;
use Swoole\Process\Pool;
use Swoole\Runtime;
use Swoole\Table;
use Swoole\Timer;
use function foo\func;

class CustomerManage
{
    /**
     * @var array
     */
    private $config;
    /**
     * @var string
     */
    private $rootProcessName;
    /**
     * @var string
     */
    private $childProcessName;
    /**
     * @var string
     */
    private $baseProcessName;

    public function __construct()
    {
        $this->init();
    }

    /**
     * 启动进程池处理队列
     * 目前部分配置写死
     */
    public function start()
    {
        swoole_set_process_name($this->rootProcessName);
        Runtime::enableCoroutine();
        $config = $this->config;
        $poolConfig = $this->config['pool'];
        $childName = $this->childProcessName;

        $pool = new Pool($poolConfig['workerNum'], 0, 0, true);

        $table = $this->getInitTable($poolConfig['workerNum']);
        $atomic = new \Swoole\Atomic();

        // \Swoole\Process::daemon();
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
            $running = true;

            $table->set($workerId, [
                'workId' => $workerId,
                'running' => $running,
            ]);
            $consumers = [];
            foreach (range(0, 3) as $index) {
                $consumers[] = (new Process($config))->getConsumer();
            }

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
                    if (! $running) {
                        break;
                    }
                    if ($workerId == 0) {
                        go(function () use ($atomic, $workerId) {
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
                    pcntl_signal_dispatch();
                }
            });

            // 设置消费回调
            $func = function ($topic, $partition, $message) use ($workerId, $atomic) {
                $atomic->add();
                $message = $message['message'];
                \co::sleep(3.0);
                # ["customer ==> ","topic_deal",0,{"offset":4,"size":41,"message":{"crc":81777897,"magic":1,"attr":0,"timestamp":1593952938,"key":"what","value":"i am topic_deal"}}]
                console("do ...customer ==> workerId:{$workerId} partition:{$partition}", $message['value']);
            };
            foreach ($consumers as $consumer) {
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
     * 停止进程
     */
    public function stop()
    {
        $cmd = "ps -ef | grep \"{$this->rootProcessName}\" | grep -v grep | awk '{print $2}'| xargs kill -15";
        exec($cmd);

        return ["stop success"];
    }

    /**
     * 展示进程状态
     *
     * @return array
     */
    public function status()
    {
        $cmd = "ps -ef | grep \"{$this->baseProcessName}\" | grep -v grep";
        exec($cmd, $output);
        if (empty($output)) {
            $output = ["当前没进程..."];
        } else {
            array_unshift($output, "进程状态:");
        }

        return $output;
    }

    /**
     * 初始化配置
     */
    private function init()
    {
        $this->config = Config::getInstance()->getConf("KAFKA");
        $this->baseProcessName = $this->config["pool"]['queueName'];
        $this->rootProcessName = "{$this->baseProcessName}_root";
        $this->childProcessName = "{$this->baseProcessName}_child";
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @return string
     */
    public function getRootProcessName(): string
    {
        return $this->rootProcessName;
    }

    /**
     * @return string
     */
    public function getChildProcessName(): string
    {
        return $this->childProcessName;
    }

    /**
     * @return string
     */
    public function getBaseProcessName()
    {
        return $this->baseProcessName;
    }

    private function getInitTable($workerNum)
    {
        $table = new Table(1024);
        foreach (range(0, $workerNum - 1) as $num) {
            $table->column('workId', \Swoole\Table::TYPE_INT, 4);
            $table->column('running', \Swoole\Table::TYPE_INT, 4);
        }
        $table->create();

        return $table;
    }

}
