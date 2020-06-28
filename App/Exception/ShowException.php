<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/25
 * Time: 23:55
 */

namespace App\Exception;


use Throwable;

class ShowException extends \Exception
{
    public $data;

    public function __construct($message = "", $code = 0, $data = null, Throwable $previous = null)
    {
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }

}
