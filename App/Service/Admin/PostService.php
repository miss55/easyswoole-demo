<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/7/4
 * Time: 10:36
 */

namespace App\Service\Admin;


use App\Exception\ShowException;
use App\Helper\Page;
use App\Model\PostModel;

class PostService
{
    public function posts($params, Page $page)
    {
        return PostModel::create()->generateWheres($params)->field(PostModel::POST_FIELDS)->generatePageData($page);
    }

    public function add($data)
    {
        $date = now();
        $data['created_date'] = $date;
        $data['updated_date'] = $date;
        $data['pre_content'] = $this->generatePreContent($data['content']);
        if (! PostModel::create($data)->save()) {
            throw new ShowException("添加失败");
        }
    }

    public function update($data, $id)
    {
        unset($data['id']);
        $data['pre_content'] = $this->generatePreContent($data['content']);
        $post = PostModel::create()->get($id);
        if (! $post->update($data)) {
            throw new ShowException("更新失败");
        }
    }

    private function generatePreContent($content)
    {
        return mb_substr($content, 0, 200);
    }
}
