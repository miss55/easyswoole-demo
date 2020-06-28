<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/26
 * Time: 21:08
 */

namespace App\Helper;


use App\Exception\PageEmptyException;

/**
 * 页数类
 * Class Page
 *
 * @package App\Helper
 */
class Page
{
    private $pageName = 'page';
    private $pageSizeName = 'page_size';

    private $page;

    private $pageSize;

    public $cursor;

    private $defaultPageSize = 20;
    private $maxPageSize = 50;

    public function __construct($params)
    {
        $this->page = data_get($params, $this->pageName);
        $this->pageSize = data_get($params, $this->pageSizeName);
        $this->checkPageSize();
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return mixed
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    public function start()
    {
        return ($this->page - 1) * $this->pageSize ;
    }

    public function offset()
    {
        return $this->start() + $this->pageSize - 1;
    }

    private function checkPageSize()
    {
        if (empty($this->page) || ! is_numeric($this->page) || $this->page < -1) {
            $this->page = 1;
        }
        if (empty($this->pageSize) || is_numeric($this->pageSize) || $this->pageSize > $this->maxPageSize) {
            $this->pageSize = $this->defaultPageSize;
        }
    }

    public function generatePage($rows)
    {
        return [
            'page' => count($rows) > 0 ? $this->getPage() + 1 : -1,
            'pageSize' => $this->getPageSize(),
            'data' => $rows,
        ];
    }

    public function checkEmpty()
    {
        if ($this->page == -1) {
            throw new PageEmptyException('');
        }
    }

}
