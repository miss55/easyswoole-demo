<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/29
 * Time: 11:01
 */

namespace App\Pool;


use App\Third\RabbitMq\RabbitMqProducer;
use EasySwoole\Pool\AbstractPool;
use EasySwoole\Pool\Config;

class RabbitMqProducerPool extends AbstractPool
{
    private $mqConf;

    public function __construct(Config $conf, \App\Third\RabbitMq\Config $mqConf)
    {
        $this->mqConf = $mqConf;
        parent::__construct($conf);
    }

    protected function createObject()
    {
        $conf = $this->mqConf->getConfig();
        return new RabbitMqProducer($conf['cons']);
    }
}
