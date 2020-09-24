<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/9/24
 * Time: 20:42
 */

namespace App\Helper\Validate;


use App\Exception\ValidateErrorException;
use EasySwoole\Spl\SplArray;
use EasySwoole\Validate\Error;

class ArrayRule extends BaseRule
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
        if (! $this->isIndexArray($data)) {
            throw new ValidateErrorException(new Error($column, $data, null, 'func', "{$column}必须是索引数组", null));
        }

        $newData = [];
        foreach ($data as $index => $datum) {
            if ($this->validate->validate($datum)) {
                $newData[] = $this->validate->getVerifiedData();
            } else {
                $index = $index + 1;
                $error = $this->validate->getError();
                $error->setFieldAlias("{$column}第{$index}个的" . $error->getField());
                throw new ValidateErrorException($error);
            }
        }
        $spl->set($column, $newData);

        return true;
    }

}
