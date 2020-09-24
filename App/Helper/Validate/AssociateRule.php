<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/9/24
 * Time: 21:17
 */

namespace App\Helper\Validate;


use App\Exception\ValidateErrorException;
use EasySwoole\Spl\SplArray;
use EasySwoole\Validate\Error;

class AssociateRule extends BaseRule
{

    /**
     * @param SplArray $spl
     * @param          $column
     *
     * @return bool
     * @throws ValidateErrorException
     */
    public function validate(SplArray $spl, $column)
    {
        $data = $spl->get($column);
        $this->checkIsArray($data);
        if ($this->isIndexArray($data)) {
            throw new ValidateErrorException(new Error($column, $data, null, 'func', "{$column}必须是关联数组", null));
        }

        if ($this->validate->validate($data)) {
            $newData = $this->validate->getVerifiedData();
        } else {
            $error = $this->validate->getError();
            $error->setFieldAlias("{$column}的". $error->getField());
            throw new ValidateErrorException($error);
        }
        $spl->set($column, $newData);
        return true;
    }

    private function checkIsAssociate(?array $data)
    {

    }
}
