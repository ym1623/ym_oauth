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
<h1>QQ互联集成PHP SDK - 验证是否为空间粉丝</h1>
<div class="list-div">

<?php 
    if(isset($_GET["page_id"])&&!empty($_GET["page_id"])){
       echo "<table>";
	    $sUrl = "https://graph.qq.com/user/check_page_fans";
	    $aGetParam = array(
	    	"access_token" => $_SESSION["access_token"],
	    	"oauth_consumer_key"    =>    $aConfig["appid"],
	    	"openid"                =>    $_SESSION["openid"],
	    	"format"                =>    "json",
	    	"page_id"					=>	  $_GET["page_id"]
	    );
	    $sContent = get($sUrl,$aGetParam);
	    if($sContent!==FALSE){
	    	$aResult = json_decode($sContent,true);
	    	if($aResult["ret"]==0){
	    		echo "<tr><td class='narrow-label'>是否为空间粉丝</td><td>".($aResult["isfans"]?"是":"否")."</td></tr>";
	    	}else{
	    		echo "<tr><td class='narrow-label'>错误反馈</td><td>".$aResult["msg"]."</td></tr>";
	    	}
	    }
	    echo "<tr><td class='narrow-label'></td><td><input type='button' value='返回首页' onclick='location.href=\"../index.php\";' class='button' /></td></tr>";
	    echo "</table>";
    }else{
	?>
<form action="check_page_fans.php" method="GET">
<table>
<tr>
  <td class="narrow-label">空间UIN:</td>
  <td><input type="input" name="page_id" class="input" /></td>
</tr>
<tr>
  <td class="narrow-label"></td>
  <td><input type="submit" value="验证是否为空间粉丝" class="button" /><input type="button" value="返回首页" class="button" onclick="location.href='../index.php';" /></td>
</tr>
</table>
</form>
<?php
    }
?>
</div>
</body>