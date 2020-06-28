<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/27
 * Time: 13:07
 */

namespace App\Exception;


use Throwable;

class TokenException extends ShowException
{
    public function __construct($message = "", $code = 401, $data = null, Throwable $previous = null)
    {
        parent::__construct($message, $code, $data, $previous);
    }
}
