<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/29
 * Time: 11:03
 */

namespace App\Third\RabbitMq;


use PhpAmqpLib\Message\AMQPMessage;

class RabbitMqProducer extends RabbitMq
{

    public function push($body)
    {
        $message = new AMQPMessage(
            is_array($body) ? json_encode_chinese($body) : $body,
            [
                'content_type' => 'text/plain',
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
            ]
        );
        $this->channel->basic_publish($message, $this->exchange);
    }

}
