<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/29
 * Time: 10:43
 */

namespace App\Third\RabbitMq;


use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Connection\Heartbeat\PCNTLHeartbeatSender;
use PhpAmqpLib\Exchange\AMQPExchangeType;

class RabbitMq
{
    /**
     * @var AMQPStreamConnection
     */
    protected $connection;
    /**
     * @var \PhpAmqpLib\Channel\AMQPChannel
     */
    protected $channel;

    # 先写死
    protected $exchange = 'router';
    protected $queue = 'msgs';
    protected $consumerTag = 'consumer';

    public function __construct($cons)
    {
        $this->setConnection($cons);
        $this->registerHeader();
        $this->setChannel();
    }

    protected function setChannel()
    {
        $channel = $this->connection->channel();
        $message = $channel->queue_declare($this->queue, false, true, false, false);
        console("current ready message :", $message);
        $channel->exchange_declare($this->exchange, AMQPExchangeType::DIRECT, false, true, false);
        $channel->queue_bind($this->queue, $this->exchange);
        $this->channel = $channel;
    }

    public function close()
    {
        if ($this->channel) {
            $this->channel->close();
            $this->channel = null;
        }
        if ($this->connection) {
            $this->connection->close();
            $this->connection = null;
        }
    }

    public function __destruct()
    {
        $this->close();
    }

    protected function registerHeader()
    {
        $sender = new PCNTLHeartbeatSender($this->connection);
        $sender->register();
    }

    private function setConnection($cons)
    {
        $this->connection = AMQPStreamConnection::create_connection($cons,
            [
                'insist' => false,
                'login_method' => 'AMQPLAIN',
                'login_response' => null,
                'locale' => 'en_US',
                'connection_timeout' => 6.0,
                'read_write_timeout' => 10.0,
                'context' => null,
                'keepalive' => true,
                'heartbeat' => 5
            ]);
    }
}
