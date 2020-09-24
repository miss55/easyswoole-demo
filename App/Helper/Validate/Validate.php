<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/9/24
 * Time: 20:40
 */

namespace App\Helper\Validate;


use App\Exception\ValidateErrorException;
use App\Exception\ValidateException;
use EasySwoole\Validate\Rule;

class Validate extends \EasySwoole\Validate\Validate
{
    protected $multiValidates = [];

    /**
     * 如果校验不通过，直接抛ValidateException 异常
     *
     * @param $data
     *
     * @throws ValidateException
     */
    public function validOrThrowNewException($data)
    {
        if (! $this->validate($data)) {
            throw new ValidateException($this->getError()->__toString());
        }
    }

    public function validate(array $data)
    {
        $bool = false;

        try {
            $bool = parent::validate($data);
        } catch (ValidateErrorException $e) {
            $this->error = $e->data;
        }

        return $bool;
    }

    /**
     * 校验索引数组
     * @param $name
     *
     * @return Validate
     */
    public function validateArray($name)
    {
        if (!isset($this->multiValidates[$name]) ) {
            $rule = new ArrayRule($name);
            $this->addColumn($name)->required()->notEmpty()->func([$rule, 'validate']);
            $this->multiValidates[$name] = $rule;
        }

        return $this->multiValidates[$name]->getValidate();
    }

    /**
     * 校验关联数组
     * @param $name
     *
     * @return Validate
     */
    public function validateAssociate($name)
    {
        if (!isset($this->multiValidates[$name]) ) {
            $rule = new AssociateRule($name);
            $this->addColumn($name)->required()->notEmpty()->func([$rule, 'validate']);
            $this->multiValidates[$name] = $rule;
        }

        return $this->multiValidates[$name]->getValidate();
    }
}
