<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/27
 * Time: 9:55
 */

namespace App\Service\Admin;


use App\Exception\CursorEmptyException;
use App\Helper\CursorPage;
use App\Helper\Page;

class RedisService
{

    /**
     * @param null|string $pattern
     * @param CursorPage  $page
     *
     * @return array
     * @throws CursorEmptyException
     * @throws \Throwable
     */
    public function list($pattern, CursorPage $page)
    {
        $redis = get_redis('redis');
        $rows = [];

        $page->checkEmpty();

        $list = $redis->scan($page->cursor, $pattern, $page->getPageSize());
        foreach ($list as $name) {
            $rows[] = [
                'name' => $name,
                'type' => $redis->type($name),
            ];
        }

        return $page->generateReturn($rows);
    }

    /**
     * @param string $name
     *
     * @return array
     * @throws \Throwable
     */
    public function string($name)
    {
        $redis = get_redis('redis');
        $value = $redis->get($name);

        return [
            'name' => $name,
            'value' => $value,
        ];
    }

    /**
     * @param string $name
     * @param Page   $page
     *
     * @return array
     * @throws \App\Exception\PageEmptyException
     * @throws \Throwable
     */
    public function array($name, Page $page)
    {
        $redis = get_redis('redis');
        $page->checkEmpty();
        $list = $redis->lRange($name, $page->start(), $page->offset());

        return $page->generatePage($list);
    }

    /**
     * @param string      $name
     * @param string|null $pattern
     * @param CursorPage  $page
     *
     * @return array
     * @throws CursorEmptyException
     * @throws \Throwable
     */
    public function select($name, $pattern, CursorPage $page)
    {
        $redis = get_redis('redis');

        $page->checkEmpty();
        $type = $redis->type($name);

        $pattern = $pattern ? $pattern . '*' : $pattern;

        switch ($type) {
            case 'set':
                $list = $redis->sScan($name, $page->cursor, $pattern, $page->getPageSize());
                break;
            case 'zset':
                $list = $redis->zScan($name, $page->cursor, $pattern, $page->getPageSize());
                break;
            case 'hash':
                $list = $redis->hScan($name, $page->cursor, $pattern, $page->getPageSize());
                break;
        }

        return $page->generateReturn($list);
    }


}
