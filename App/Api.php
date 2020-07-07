<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/14
 * Time: 21:05
 */

namespace App;


use EasySwoole\HttpClient\HttpClient;

class Api
{
    /**
     * @var \Swoole\Table $table
     */
    private $table ;
    public function __construct($table)
    {
        $this->table = $table;
    }

    public function vote($atomic)
    {
        $begin = time();
        $api = 'http://tp.hnkj1688.com/index.php?app_ajax=1&app_act=vote&id=22&itemid=1';
        $client = new HttpClient($api);
        [$ip, $port] = $this->getIpPort();
        $client->setProxyHttp($ip, (int) $port);
        $client->setTimeout(60.0);
        $client->setConnectTimeout(60);
        $client->setHeader("User-Agent", $this->getUserAgent());

        $response = $client->get();
        if ($response->getStatusCode() != '200') {
            $diff = time()-$begin;
            echo $response->getErrCode() . "==> " . $response->getErrMsg() . "; diff={$diff}\n";
        } else {
            $data = json_decode($response->getBody(), true);
            if ($data['code'] == 0) {
                $atomic->add();
                $count = $atomic->get();
                echo "刷成功 {$count}\n";
            } else {
                echo $data['msg'] . "\n";
            }
        }
    }

    private function getUserAgent()
    {
        $agents = [
            "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36",
            "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; AcooBrowser; .NET CLR 1.1.4322; .NET CLR 2.0.50727)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; Acoo Browser; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.0.04506)",
            "Mozilla/4.0 (compatible; MSIE 7.0; AOL 9.5; AOLBuild 4337.35; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)",
            "Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET CLR 2.0.50727; Media Center PC 6.0)",
            "Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET CLR 1.0.3705; .NET CLR 1.1.4322)",
            "Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 5.2; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.2; .NET CLR 3.0.04506.30)",
        ];

        return $agents[random_int(0, count($agents) - 1)];
    }

    private function getIpPort() {
        $int = random_int(0, 4);
        $ipPort = $this->table->get('default', "default_{$int}");
        return explode(':', $ipPort);
    }
    public function table(\Swoole\Atomic $lock)
    {
        # 每次返回5个
        $apiUrl = "http://api.ip.data5u.com/dynamic/get.html?order=4978419d4c2bded4ead09c01b05dbe2a&random=1&sep=3";
        $client = new HttpClient($apiUrl);
        // $client->setProxyHttp($ip, (int) $pool);
        $client->setTimeout(60.0);
        $client->setConnectTimeout(60);
        $client->setHeader("User-Agent", $this->getUserAgent());

        $response = $client->get();
        $ips = explode("\n", $response->getBody());

        $array = [];
        foreach ($ips as $index => $ip) {
            if (empty($ip)) {
                continue;
            }
            $array["default_{$index}"] = $ip;
        }
        var_export($array);
        $this->table->set('default', $array);
        $lock->add();

    }

}
