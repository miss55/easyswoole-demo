<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/27
 * Time: 10:56
 */

namespace App\Model;


/**
 * 管理员表
 * Class AdminModel
 *
 * @package App\Model
 * @property string $name
 * @property string email
 * @property string password
 * @property int status
 * @property string note
 * @property string created_date
 * @property string updated_date
 * @property string last_login_date
 *
 */
class AdminModel extends Base
{
    protected $tableName = 'admin';
}
