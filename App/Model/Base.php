<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/27
 * Time: 10:56
 */

namespace App\Model;


use App\Helper\Page;
use EasySwoole\ORM\AbstractModel;
use EasySwoole\ORM\DbManager;

class Base extends AbstractModel
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    private $orderFlag = false;

    public static function fastInvoke(\Closure $func)
    {
        return DbManager::getInstance()->invoke($func);
    }

    public function generatePageData(Page $page)
    {
        if (empty($this->orderFlag)) {
            $this->order($this->schemaInfo()->getPkFiledName(), SORT_DESC);
        }
        $this->withTotalCount()->page($page->getPage(), $page->getPageSize())->all();
        $result = $this->lastQueryResult();

        return $page->generatePage($result->getResult(), $result->getTotalCount());
    }

    public function order(...$args)
    {
        $this->orderFlag = true;
        if (isset($args[1])) {
            $sort = $args[1];
            if ($sort == SORT_ASC) {
                $args[1] = 'ASC';
            } else if ($sort == SORT_DESC) {
                $args[1] = 'DESC';
            }
        }

        return parent::order(...$args);
    }
}
