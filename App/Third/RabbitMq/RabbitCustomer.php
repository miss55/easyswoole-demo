<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/29
 * Time: 10:29
 */

namespace App\Third\RabbitMq;


/**
 * rabbit mq 订阅
 * Class RabbitCustomer
 *
 * @package App\Third\RabbitMq
 */
class RabbitCustomer extends RabbitMq
{
    public function subscript($callback)
    {
        $this->channel->basic_consume($this->queue, $this->consumerTag, false, false, false, false, $callback);
        while ($this->channel ->is_consuming()) {
            $this->channel->wait();
            \co::sleep(0.1);

        }
    }

}
