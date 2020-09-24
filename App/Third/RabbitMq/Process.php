<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/27
 * Time: 16:25
 */

namespace App\Third\RabbitMq;


use App\Helper\Helper;
use App\Provider\MysqlProvider;
use App\Provider\RedisProvider;
use EasySwoole\EasySwoole\Logger;
use Swoole\Atomic;
use Swoole\Table;
use Swoole\Timer;

/**
 * rabbit mq 进程
 * Class Process
 *
 * @package App\Third\RabbitMq
 */
class Process
{
    /**
     * @var Table
     */
    private $table;
    /**
     * @var Atomic
     */
    private $atomic;
    /**
     * @var bool
     */
    private $running;

    private $workId;

    /**
     * @var Config
     */
    private $configObj;

    public function __construct($configObj, $workId)
    {
        $this->workId = $workId;
        $this->configObj = $configObj;
        $this->table = $this->configObj->getTable();
        $this->atomic = $this->configObj->getAtomic();
        $this->running = true;

        $this->table->set($this->workId, [
            'workId' => $this->workId,
            'running' => $this->running,
        ]);
        $this->init();
    }

    protected function init()
    {
        RedisProvider::register();
        MysqlProvider::initialize();
    }

    public function run()
    {
        console("----run-----");
        $consumers = [];
        $configs = $this->configObj->getConfig();

        foreach (range(0, 1) as $item) {
            $consumers[] = new RabbitCustomer($configs['cons']);
            break;
        }
        pcntl_async_signals(true);
        pcntl_signal(SIGTERM, function () use ($consumers) {
            Logger::getInstance()->info("exit ... {$this->workId}");
            $row = $this->table->get($this->workId);
            $this->running = false;
            $row['running'] = $this->running;
            $this->table->set($this->workId, $row);
            foreach ($consumers as $consumer) {
                $consumer->close();
            }
            Timer::clearAll();
        });

        go(function () {
            # 设置信号
            while (true) {
                if (! $this->running) {
                    break;
                }
                if ($this->workId == 0) {
                    go(function () {
                        $count = $this->atomic->get();
                        if ($count > 0) {
                            $redis = get_redis('redis');
                            $redis->incrBy("", $count);
                            unset($redis);
                            $this->atomic->sub($count);
                        }
                    });
                }
                \co::sleep(2.0);
            }
        });

        console("===consumer count:". count($consumers));
        foreach ($consumers as $index => $consumer) {
            go(function () use($consumer, $index) {
                /**
                 * @var RabbitCustomer $consumer
                 */
                try {
                    console("----subscript-----");
                    $consumer->subscript(function ($message) use($index) {
                        try {
                            $order = new OrderConsumer($message);
                            $order->setWorkerId($this->workId);
                            $order->run();
                            // Send a message with the string "quit" to cancel the consumer.
                            if ($message->body === 'quit') {
                                console("workId: {$this->workId}; index: {$index} quit!");
                                $message->getChannel()->basic_cancel($message->getConsumerTag());
                            }
                        } catch (\Exception $e) {
                            Helper::error("消费消息异常", $message->body, $e);
                        }
                    });
                } catch (\Exception $e) {
                    Helper::error("消费异常", null, $e);
                }
            });
        }

    }
}
