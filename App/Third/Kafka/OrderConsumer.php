<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/26
 * Time: 16:02
 */

namespace App\Third\Kafka;


use App\Exception\ShowException;
use App\Model\OrderModel;

/**
 * 消费者
 * Class OrderConsumer
 *
 * @package App\Third\Kafka
 */
class OrderConsumer
{
    private $message;
    private $topic;
    private $partition;
    private $workerId;

    public function __construct($topic, $partition, $message)
    {
        $this->message = $message;
        $this->setTopic($topic);
        $this->setPartition($partition);
    }

    public function run()
    {
        // console("workerId:{$this->workerId} partition:{$this->partition} ++1");
        console("do ...customer ==> workerId:{$this->workerId} partition:{$this->partition}", $this->message);
        // $data = json_decode($this->message['value'], true);
        // $date = now();
        // $data['status'] = 1;
        // $data['add_time'] = $date;
        // $data['update_time'] = $date;
        // // console("test", $data);
        // if (! OrderModel::create($data)->save()) {
        //     throw new ShowException("添加失败");
        // }

    }

    /**
     * @param mixed $topic
     */
    public function setTopic($topic): void
    {
        $this->topic = $topic;
    }

    /**
     * @param mixed $partition
     */
    public function setPartition($partition): void
    {
        $this->partition = $partition;
    }

    /**
     * @param mixed $workerId
     */
    public function setWorkerId($workerId): void
    {
        $this->workerId = $workerId;
    }

}
