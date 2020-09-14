<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/26
 * Time: 16:02
 */

namespace App\Third\RabbitMq;


use App\Exception\ShowException;
use App\Helper\Helper;
use App\Model\OrderModel;

class OrderConsumer
{
    /**
     * @var \PhpAmqpLib\Message\AMQPMessage
     */
    private $message;
    private $workerId;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function run()
    {
        // console("workerId:{$this->workerId} partition:{$this->partition} ++1");
        // console("do ...customer ==> workerId:{$this->workerId} ", $this->message->body);
        $data = json_decode($this->message->body, true);
        $date = now();
        $data['status'] = 1;
        $data['add_time'] = $date;
        $data['update_time'] = $date;
        // console("test", $data);
        if (! OrderModel::create($data)->save()) {
            # 可以额外处理异常
            Helper::error("添加失败");
            throw new ShowException("添加失败");
        } else {
            $this->message->ack();
        }
    }
    public function __destruct()
    {
    }

    /**
     * @param mixed $workerId
     */
    public function setWorkerId($workerId): void
    {
        $this->workerId = $workerId;
    }

}
