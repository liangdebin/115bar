<?php

/**
 * Ap定义了如下的7个Hook,
 * 插件之间的执行顺序是先进先Call
 */
class UserPlugin extends Yaf\Plugin_Abstract
{

    public function routerStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {
        // 防止get post注入
        $_POST = cat_escape($_POST);
        $_GET  = cat_escape($_GET);
    }

    /**
     * 路由判断权限
     * @author dbb
     * @data 2014/12/26
     */
    public function routerShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {
        $controller = strtolower($request->getControllerName());
        $action = strtolower($request->getActionName());

        // 登陆页之外的处理
        if ($controller != "sign") {

            //项目开始，未登录跳转到登陆页面
            if (!Yaf\Session::getInstance()->user) {
                $response->setRedirect("/sign/index");
            }
        }
    }

    public function dispatchLoopStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {

    }

    public function preDispatch(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {
//        $id = $_REQUEST["id"];
//        $controller = strtolower($request->getControllerName());
//        $action = strtolower($request->getActionName());
//        User_LogModel::addLog($controller, $action, "123".$id);
    }

    public function postDispatch(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {

    }

    public function dispatchLoopShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {

    }

    public function preResponse(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {

    }

}
