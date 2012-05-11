<?php
    header("Content-type:text/html; charset=UTF-8;");
	if(!file_exists('../common/config.php')){
		header("Location:../install/index.php");
		exit;
	}
    include_once '../common/function.php';
?>
<html>
<head>
<link href="../style/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
</head>
<body>
<h1>QQ互联集成PHP SDK - 获取用户信息</h1>
<div class="list-div">
<table>
<?php
    $sUrl = "https://graph.qq.com/user/get_user_info";
    $aGetParam = array(
        "access_token" => $_SESSION["access_token"],
        "oauth_consumer_key"    =>    $aConfig["appid"],
        "openid"                =>    $_SESSION["openid"],
        "format"                =>    "json"
    );
    $sContent = get($sUrl,$aGetParam);
    if($sContent!==FALSE){
        $aResult = json_decode($sContent,true);
        if($aResult["ret"]==0){
            echo "<tr><td class='narrow-label'>用户昵称:</td><td>".$aResult["nickname"]."</td></tr>";
            echo "<tr><td class='narrow-label'>小等头像:</td><td><img src='".$aResult["figureurl"]."' border='0' /></td></tr>";
            echo "<tr><td class='narrow-label'>中等头像:</td><td><img src='".$aResult["figureurl_1"]."' border='0' /></td></tr>";
            echo "<tr><td class='narrow-label'>大等头像:</td><td><img src='".$aResult["figureurl_2"]."' border='0' /></td></tr>";
            echo "<tr><td class='narrow-label'>用户性别:</td><td>".$aResult["gender"]."</td></tr>";
            echo "<tr><td class='narrow-label'>用户黄钻:</td><td>".$aResult["vip"]."</td></tr>";
            echo "<tr><td class='narrow-label'>黄钻等级:</td><td>".$aResult["level"]."</td></tr>";
        }else{
            echo "<tr><td class='narrow-label'>反馈消息:</td><td>".$aResult["msg"]."</td></tr>";
        }
    }else{
        echo "<tr><td class='narrow-label'>反馈消息:</td><td>获取用户信息失败。</td></tr>";
    }
?>
<tr>
  <td></td>
  <td><input type="button" onclick='location.href="../index.php";' value="返回首页"  class="button"/></td>
</tr>
</table>
</div>
</html>