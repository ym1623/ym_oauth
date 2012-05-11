<?php
require_once("../comm/config.php");


function qq_login($appid, $scope, $callback)
{
	global $state;
    $state['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
    $login_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=" 
        . $appid . "&redirect_uri=" . urlencode($callback)
        . "&state=" . $state['state']
        . "&scope=".$scope;
    header("Location:$login_url");
}

//用户点击qq登录按钮调用此函数
qq_login($config["appid"], $config["scope"], $config["callback"]);
?>
