<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/26
 * Time: 16:05
 */

namespace App\Model;


/**
 * Class OrderModel
 *
 * @package App\Model
 * @property int $id
 * @property string $name
 * @property int $num
 * @property int $status
 * @property double $total
 * @property string $add_time
 * @property string $update_time
 */
class OrderModel extends Base
{
    protected $tableName = 'orders';

}
