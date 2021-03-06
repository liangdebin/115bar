<?php

/**
 * 当有未捕获的异常, 则控制流会流到这里
 */
class ErrorController extends Yaf\Controller_Abstract
{

    public function init()
    {
        Yaf\Dispatcher::getInstance()->disableView();
    }

    public function errorAction($exception = null)
    {
        if (empty($exception)) {
            throw new Exception('Exception is empty',YAF\ERR\NOTFOUND\ACTION);
        }

        /* error occurs */
        switch ($exception->getCode()) {
            case YAF\ERR\NOTFOUND\MODULE: //no break;
            case YAF\ERR\NOTFOUND\CONTROLLER:
            case YAF\ERR\NOTFOUND\ACTION:
            case YAF\ERR\NOTFOUND\VIEW:
                $array           = array();
                $array["result"] = false;
                $array["code"]   = -99;
                $array["msg"]    = $exception->getMessage();
                $array["data"]   = $exception->getTrace();
                // $array["data"]   = $exception->getTraceAsString();
                // echo json_encode($array);
                // break;
                $this->getResponse()->setHeader($this -> getRequest() -> getServer( 'SERVER_PROTOCOL' ), '404 Not Found');
                $this->getResponse()->response();

                $output            = array();
                $output['referer'] = $this->getRequest()->getServer('HTTP_REFERER','');
                $output['title']   =  FuncHelper::_("la_102").' › '. FuncHelper::_("la_106");
                $this->getView()->assign("output", $output);
                $this->getView()->display("error/404.html");
                return;
            default :
                $array           = array();
                $array["result"] = false;
                $array["code"]   = -98;
                $array["msg"]    = $exception->getMessage();
                $array["data"]   = $exception->getTrace();
                // $array["data"] = $exception->getTraceAsString();
                // echo json_encode($array);

                // 文件日志
                Log::addLog($array);

                // 邮件提醒  5分钟一次
                $subject = FuncHelper::_("la_108").":".date("m-d H:i").' '.CONFIG_ENV;
                $text = $exception->getMessage()."\n".$exception->getTraceAsString();
                if (CONFIG_ENV=='ol') {
                    MailHelper::getInstance()->sendTip($subject,$text);
                }


                $this->getResponse()->setHeader($this->getRequest()->getServer( 'SERVER_PROTOCOL' ), '500 Internal Server Error');
                $this->getResponse()->response();

                $output            = array();
                $output['referer'] = $this->getRequest()->getServer('HTTP_REFERER','');
                $output['title']   =  FuncHelper::_("la_102").' › '. FuncHelper::_("la_107");
                $this->getView()->assign("output", $output);
                $this->getView()->display("error/error.html");
                return;

        }
        return;
    }

    private function _pageNotFound()
    {
        $this->getResponse()->setHeader('HTTP/1.0 404 Not Found');
        $this->_view->error = 'Page was not found';
    }

    private function _unknownError()
    {
        $this->getResponse()->setHeader('HTTP/1.0 500 Internal Server Error');
        $this->_view->error = 'Application Error';
    }
}
