<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/25
 * Time: 23:37
 */

namespace App\HttpController;


use App\Exception\PageEmptyException;
use App\Exception\ShowException;
use App\Helper\CursorPage;
use App\Helper\Helper;
use App\Helper\Page;
use App\Validate\ValidateFactory;
use EasySwoole\EasySwoole\Core;
use EasySwoole\Http\AbstractInterface\Controller;

class Base extends Controller
{
    protected $params;

    protected $service;

    /**
     * @var array
     */
    protected $validateData;

    /**
     * @var Page
     */
    private $page;

    public function onRequest(?string $action): ?bool
    {
        # 校验参数
        $this->params = array_merge($this->request()->getQueryParams(), $this->request()->getParsedBody());
        $this->doValidate($action);

        return parent::onRequest($action);
    }

    protected function afterAction(?string $actionName): void
    {
        unset($this->page, $this->params, $this->validateData, $this->service);
    }

    public function onException(\Throwable $throwable): void
    {
        if ($throwable instanceof PageEmptyException) {
            $this->writeSuccess($throwable->getPage()->generatePage([]));
        } else if ($throwable instanceof ShowException) {
            $this->writeError($throwable->getMessage(), $throwable->data, $throwable->getCode());
        } else if ($throwable instanceof \Exception) {
            $name = strtolower($this->getControllerParent());
            $category = "controller.{$name}";
            Helper::error("控制器捕获到一个异常", null, $throwable, $category);
            $msg = "服务器跑路了";
            if (Core::getInstance()->isDev()) {
                $msg = $throwable->getMessage();
            }
            $this->writeError($msg);
        }
    }

    /**
     * 成功响应json
     *
     * @param string|array $msg 字符串为message，数组为data
     * @param array        $data
     */
    protected function writeSuccess($msg = null, $data = null)
    {
        if (is_array($msg)) {
            $data = $msg;
            $msg = 'success';
        }
        return $this->writeJson(200, $data, $msg);
    }

    /**
     * @param string     $msg            错误消息提示
     * @param null|array $data           可选返回数据
     * @param int        $statusCode     status
     * @param int        $httpStatusCode http状态码
     *
     * @return bool
     */
    protected function writeError($msg, $data = null, $statusCode = 0, $httpStatusCode = 200)
    {
        return $this->writeJson($statusCode, $data, $msg, $httpStatusCode);
    }

    /**
     * @param int  $statusCode
     * @param null $result
     * @param null $msg
     * @param null $httpStatusCode
     *
     * @return bool
     */
    protected function writeJson($statusCode = 200, $result = null, $msg = null, $httpStatusCode = null)
    {
        if (! $this->response()->isEndResponse()) {
            $httpStatusCode = $httpStatusCode ?: $statusCode;
            $data = [
                "code" => $statusCode,
                "data" => $result,
                "msg" => $msg,
            ];
            $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->response()->withStatus($httpStatusCode);

            return true;
        } else {
            return false;
        }
    }

    /**
     * 做参数校验
     *
     * @param $action
     *
     * @throws \App\Exception\ValidateException
     */
    private function doValidate($action)
    {
        # 根据控制器名称 获取相应的校验类
        $class = str_replace(__NAMESPACE__, "", get_class($this));
        try {
            $v = ValidateFactory::get($class, $action);
        } catch (ShowException $e) {
            # 如果没有校验类则不处理
            return;
        }
        $v->invalidAndThrowNewException($this->params);
        $this->validateData = $v->getVerifiedData();
    }

    private function getControllerParent()
    {
        $arr = explode("\\", get_class($this));
        if (! empty($arr)) {
            array_pop($arr);

            return array_pop($arr);
        }

        return '';
    }

    protected function getPage()
    {
        if (empty($this->page)) {
            $this->page = new Page($this->params);
        }

        return $this->page;
    }

}
