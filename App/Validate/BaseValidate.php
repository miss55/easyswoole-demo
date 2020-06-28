<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/26
 * Time: 10:16
 */

namespace App\Validate;


use App\Exception\ValidateException;
use EasySwoole\Spl\SplArray;
use EasySwoole\Validate\Error;
use EasySwoole\Validate\Rule;
use EasySwoole\Validate\Validate;
use EasySwoole\Validate\ValidateInterface;

abstract class BaseValidate extends Validate
{

    /**
     * @var string
     */
    protected $action;

    public function __construct($action)
    {
        $this->action = $action;
        $this->run();
    }

    /**
     * 如果校验不通过，直接抛ValidateException 异常
     * @param $data
     *
     * @throws ValidateException
     */
    public function invalidAndThrowNewException($data)
    {
        if (! $this->validate($data)) {
            throw new ValidateException($this->getError()->__toString());
        }
    }

    abstract protected function run();
}
