<?php
/**
 * 命令行请求入口
 *
 * Created by IntelliJ IDEA.
 * User: chenzhidong
 * Date: 13-12-5
 * Time: 上午11:43
 *
 * use: php indexc.php "request_uri=/test/time" 
 */
define("ROOT", dirname(__FILE__));
define("APPLICATION_PATH", ROOT . "/application");

try {
    $application = new Yaf\Application("conf/application.ini");
    Yaf\Loader::import("Environment.php");

    $yaf_request = new Yaf\Request\Simple();
    $response = $application
            ->bootstrap()/* bootstrap是可选的调用 */
            ->getDispatcher()
            ->dispatch($yaf_request);
    // var_dump($yaf_request);
} catch (Exception $exc) {
    echo $exc->getMessage();
}
