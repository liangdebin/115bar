<?php
/**
 * Redis连接配置
 * @package library
 */
class ConfigRedis
{

    /**
     * dbmaster Redis
     * @var array
     */
    private static $dbmaster = array(
        // DEV
        'dev' => array("127.0.0.1", 6379),
        // ONLINE
        'ol' => array("127.0.0.1", 6379),
    );

    /**
     * get Percona master
     * @return [type] [description]
     */
    public static function getDBMaster()
    {
        return self::$dbmaster[CONFIG_ENV];
    }

}
