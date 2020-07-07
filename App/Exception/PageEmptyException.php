<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/26
 * Time: 22:11
 */

namespace App\Exception;


use App\Helper\Page;
use Throwable;

class PageEmptyException extends ShowException
{
    protected $page;
    public function __construct(Page $page, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->page = $page;
        parent::__construct($message, $code, null, $previous);
    }

    /**
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }
}
