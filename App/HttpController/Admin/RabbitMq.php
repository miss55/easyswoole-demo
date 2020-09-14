<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/29
 * Time: 11:40
 */

namespace App\HttpController\Admin;


class RabbitMq extends Auth
{

    public function push()
    {
        $producer = get_rabbit_producer();
        foreach (range(0, 9) as $index) {
            $producer->push([
                'index' => $index,
            ]);
        }

        return $this->writeSuccess("yes");
    }
}
