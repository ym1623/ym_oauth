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
<h1>QQ互联集成PHP SDK - 获取其他用户信息</h1>
<div class="list-div">
<table>
<?php
    $sUrl = "https://graph.qq.com/user/get_other_info";
    $aGetParam = array(
        "access_token" => $_SESSION["access_token"],
        "oauth_consumer_key"    =>    $aConfig["appid"],
        "openid"                =>    $_SESSION["openid"],
        "format"                =>    "json",
        "fopenid"                =>        $_GET["fopenid"]
    );
    $sContent = get($sUrl,$aGetParam);
    if($sContent!==FALSE){
        $aResult = json_decode($sContent,true);
        if($aResult["ret"]==0){
            echo "<tr><td class='narrow-label'>用户昵称:</td><td>".$aResult["data"]["nick"]."</td></tr>";
            echo "<tr><td class='narrow-label'>小等头像:</td><td><img src='".$aResult["data"]["head"]."/100' border='0' /></td></tr>";
            echo "<tr><td class='narrow-label'>中等头像:</td><td><img src='".$aResult["data"]["head"]."/50' border='0' /></td></tr>";
            echo "<tr><td class='narrow-label'>大等头像:</td><td><img src='".$aResult["data"]["head"]."/30' border='0' /></td></tr>";
            echo "<tr><td class='narrow-label'>用户性别:</td><td>".($aResult["data"]["sex"]?"男":"女")."</td></tr>";
            echo "<tr><td class='narrow-label'>认证用户:</td><td>".($aResult["data"]["isvip"]?"是":"否")."</td></tr>";
            echo "<tr><td class='narrow-label'>用户邮件:</td><td>".$aResult["data"]["email"]."</td></tr>";
            echo "<tr><td class='narrow-label'>听众个数:</td><td>".$aResult["data"]["fansnum"]."</td></tr>";
            echo "<tr><td class='narrow-label'>接听个数:</td><td>".$aResult["data"]["idolnum"]."</td></tr>";
            echo "<tr><td class='narrow-label'>个人简介:</td><td>".$aResult["data"]["introduction"]."</td></tr>";
            echo "<tr><td class='narrow-label'>企业机构:</td><td>".($aResult["data"]["isent"]?"是":"否")."</td></tr>";
            echo "<tr><td class='narrow-label'>用户帐号:</td><td>".$aResult["data"]["name"]."</td></tr>";
            echo "<tr><td class='narrow-label'>微博个数:</td><td>".$aResult["data"]["tweetnum"]."</td></tr>";
            echo "<tr><td class='narrow-label'>认证信息:</td><td>".$aResult["data"]["verifyinfo"]."</td></tr>";
            echo "<tr><td class='narrow-label'>用户OPENID:</td><td>".$aResult["data"]["openid"]."</td></tr>";
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