<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/26
 * Time: 22:20
 */

namespace App\Exception;


use App\Helper\CursorPage;
use Throwable;

class CursorEmptyException extends ShowException
{
    protected $page;
    public function __construct(CursorPage $page, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->page = $page;
        parent::__construct($message, $code, null, $previous);
    }

    /**
     * @return CursorPage
     */
    public function getPage()
    {
        return $this->page;
    }

}
