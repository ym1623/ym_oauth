<?php

require_once("../comm/config.php");
require_once("../comm/utils.php");

function list_album()
{
    //获取相册列表的接口地址, 不要更改!!
    $url = "https://graph.qq.com/photo/list_album?"
        ."access_token=".$config["access_token"]
        ."&oauth_consumer_key=".$config["appid"]
        ."&openid=".$config["openid"]
        ."&format=json";
    //echo $url;
    $ret = get_url_contents($url);
    return $ret;
}

//接口调用示例：
$ret = list_album();
$arr = json_decode($ret, true);
print_r($arr);
?>
