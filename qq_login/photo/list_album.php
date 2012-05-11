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
<h1>QQ互联集成PHP SDK - 相册列表</h1>
<div class="list-div">
<table>
<?php 
    $sUrl = "https://graph.qq.com/photo/list_album";
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
        	foreach($aResult["album"] as $k=>$v){
        		echo "<tr><td class='narrow-label'>相册编号</td><td>".$v["albumid"]."</td></tr>";
        		echo "<tr><td class='narrow-label'>相册名称</td><td>".$v["name"]."</td></tr>";
        		echo "<tr><td class='narrow-label'>相册描述</td><td>".$v["desc"]."</td></tr>";
        		echo "<tr><td class='narrow-label'>相册封页</td><td><img src='".$v["coverurl"]."' border='0' /></td></tr>";
        		echo "<tr><td class='narrow-label'>建立时间</td><td>时间戳".$v["createtime"]."标准时间".date("Y-m-d H:i:s",$v["createtime"])."</td></tr>";
        	    echo "<tr><td class='narrow-label'>相片张数</td><td>".$v["picnum"]."</td></tr>";
        	    echo "<tr><td class='narrow-label'>相册分类</td><td>".$v["classid"]."</td></tr>";
        	    $aPriv = array("1"=>"公开","3"=>"仅主人可见","4"=>"QQ好友可见","5"=>"问答加密");
        	    echo "<tr><td class='narrow-label'>相册权限</td><td>".$aPriv[$v["priv"]]."</td></tr>";
        	}
        	
        	echo "<tr><td class='narrow-label'>相册个数</td><td>".$aResult["albumnum"]."</td></tr>";
        }else{
        	echo "<tr><td class='narrow-label'>错误信息</td><td>".$aResult["msg"]."</td></tr>";
        }
    }
    
    echo "<tr><td class='narrow-label'></td><td><input type='button' class='button' value='返回首页' onclick='location.href=\"../index.php\";' /></td></tr>";
?>
</table>