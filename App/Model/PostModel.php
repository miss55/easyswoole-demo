<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/7/4
 * Time: 10:32
 */

namespace App\Model;

use App\Helper\Page;

/**
 * Class PostModel
 *
 * @package App\Model
 * @property string $title
 * @property string $content
 * @property string $pre_content
 * @property int    $status
 * @property string $created_date
 * @property string $updated_date
 */
class PostModel extends Base
{

    const POST_FIELDS = [
        'id',
        'title',
        'pre_content',
        'created_date',
        'updated_date',
    ];
    protected $tableName = 'post';

    public function list($fields, Page $page)
    {
        return $this->field($fields)->generatePageData($page);
    }

    public function generateWheres($params)
    {
        if (! empty($params['title'])) {
            $this->where('title', $params['title'] . '%', 'like');
        }

        return $this;

    }
}
