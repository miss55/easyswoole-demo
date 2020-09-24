<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/9/24
 * Time: 21:09
 */

namespace App\Exception;


use Throwable;

class ValidateErrorException extends ShowException
{
    public function __construct($data = null, Throwable $previous = null)
    {
        parent::__construct("", 0, $data, $previous);
    }

}
