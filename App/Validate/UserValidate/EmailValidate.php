<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/27
 * Time: 11:25
 */

namespace App\Validate\UserValidate;


use EasySwoole\Spl\SplArray;
use EasySwoole\Validate\ValidateInterface;

class EmailValidate implements ValidateInterface
{
    const REG = '/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/';

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return "email";
    }

    /**
     * @inheritDoc
     */
    public function validate(SplArray $spl, $column, ...$args): ?string
    {
        $data = $spl->get($column);
        if (preg_match(self::REG, $data)) {
            return null;
        }
        return "{$column} must be email";
    }
}
