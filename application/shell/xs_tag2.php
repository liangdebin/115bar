<?php
/**
 * 获取tag 对没有内容的进行处理
 */
ini_set('memory_limit','256M');

// include_once __DIR__."/../library/func_common.php";
include_once __DIR__."/../library/Environment.php";
include_once __DIR__."/../library/Singleton.php";
include_once __DIR__."/../library/Otable.php";
include_once __DIR__."/../library/ConfigPg.php";
include_once __DIR__."/../library/PgsqlHelper.php";
include_once __DIR__."/../library/FuncHelper.php";
include_once __DIR__."/../models/Topic.php";
include_once __DIR__."/../../vendor/hightman/xunsearch/lib/XS.class.php";

$xs_path = __DIR__."/../../conf";
define ('XS_APP_ROOT', $xs_path);

$max_num = 5;//tag个数

$xs = new XS('115zone'); // 建立 XS 对象，项目名称为：demo
// $index = $xs->index; // 获取 索引对象
$tokenizer = new XSTokenizerScws;   // 直接创建实例

$dbname = Otable::DB_115;
$db = PgsqlHelper::getInstance();
$servers = ConfigPg::getDBMaster($dbname);
$db ->connect($servers[0], $servers[1], $servers[2], $dbname, $servers[3]);


$sql = 'SELECT "id","subject","abstract","deal_content","image_thumbs","post_time","deal_content" FROM '.Otable::TABLE_115_TOPIC." WHERE need_format!=0 AND tags='{666666}'  LIMIT 200";

$sql_tmp = 'UPDATE ' . Otable::TABLE_115_TOPIC .' SET tags=?,update_time=? WHERE id=?;';

$count = 0;
$list_arr = $db->getAll($sql, array());//var_dump($list_arr);exit;
while ($list_arr) {
    foreach ($list_arr as $key => $value) {
        $id = $value['id'];
        $deal_content = $value['subject'] . ' ' .$value['abstract'];
        $deal_content = strip_tags($deal_content);
        $deal_content = FuncHelper::trim_string($deal_content);
        try {
            $tops = $tokenizer->getTops($deal_content, $max_num, 'n,v,vn,nr,ns,nt,nz,nz,s,l,i');//http://www.xunsearch.com/scws/docs.php#attr
            // 过滤
            foreach ($tops as $k => $v) {
                if (strpos($v['word'], '礼包')!==false) {
                    unset($tops[$k]);
                } elseif (strpos($v['word'], 'baidu')!==false) {
                    unset($tops[$k]);
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            $tops = array();
        }

        if (empty($tops)) {
            $vars = array('{66666}',time(),$id);//6个6
            // continue;//继续下一条
        }else{
            $temp = array();
            foreach ($tops as $v) {
                array_push($temp,$v['word']);
            }
            $temp_str = '{'. implode(',', $temp) .'}';
            $vars = array($temp_str, time(), $id);
        }
        $db->query($sql_tmp, $vars);
        $count++;
    }
    $list_arr = $db->getAll($sql, array());
    // if ($count>15) {
    //     break;
    // }
}


echo "xstag over:",$count;
