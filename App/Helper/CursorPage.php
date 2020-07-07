<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/26
 * Time: 21:08
 */

namespace App\Helper;


use App\Exception\CursorEmptyException;

/**
 * redis页数类
 * Class CursorPage
 *
 * @package App\Helper
 */
class CursorPage
{
    private $pageSizeName = 'page_size';
    private $cursorName = 'cursor';


    private $pageSize;

    public $cursor;

    private $defaultPageSize = 20;
    private $maxPageSize = 50;
    private $emptyCursor = -1;

    public function __construct($params)
    {
        $this->cursor = data_get($params, $this->cursorName);
        $this->pageSize = data_get($params, $this->pageSizeName);
        $this->checkPageSize();
    }

    /**
     * @return mixed
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    private function checkPageSize()
    {
        if (empty($this->pageSize) || is_numeric($this->pageSize) || $this->pageSize > $this->maxPageSize) {
            $this->pageSize = $this->defaultPageSize;
        }

        if (empty($this->cursor) || ! is_numeric($this->cursor)) {
            $this->cursor = null;
        }
    }

    public function getReturnCursor()
    {
        if ($this->cursor == 0) {
            return $this->emptyCursor;
        }

        return $this->cursor;
    }

    public function checkEmpty()
    {
        if (is_numeric($this->cursor) && $this->cursor == $this->emptyCursor) {
            throw new CursorEmptyException($this);
        }
    }

    public function generateReturn($rows = null)
    {
        return [
            $this->cursorName => $this->getReturnCursor(),
            $this->pageSizeName => $this->getPageSize(),
            'data' => $rows,
        ];
    }

}
