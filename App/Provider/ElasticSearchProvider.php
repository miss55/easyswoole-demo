<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/27
 * Time: 17:18
 */

namespace App\Provider;


class ElasticSearchProvider
{
    public static function register()
    {
        $config = new \EasySwoole\ElasticSearch\Config([
            'host' => 'elasticsearch',
            'port' => 9200,
        ]);

        $elasticsearch = new \EasySwoole\ElasticSearch\ElasticSearch($config);
        set_elastic_search($elasticsearch);
    }
}
