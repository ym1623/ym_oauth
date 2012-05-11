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
<h1>QQ互联集成PHP SDK - 发表日志</h1>
<div class="list-div">
<?php
    if(isset($_POST)&&!empty($_POST)){
        echo "<table>";
        $sUrl = "https://graph.qq.com/blog/add_one_blog";
        $aPOST = array(
                "access_token" => $_SESSION["access_token"],
                "oauth_consumer_key"    =>    $aConfig["appid"],
                "openid"                =>    $_SESSION["openid"],
                "format"                =>    "json",
                "content"                =>    (get_magic_quotes_runtime()?stripslashes($_POST["content"]):$_POST["content"]),
                "title"                =>    (get_magic_quotes_runtime()?stripslashes($_POST["title"]):$_POST["title"])
        );
        $sContent = post($sUrl,$aPOST);
        if($sContent!==FALSE){
            $aResult = json_decode($sContent,true);
            if($aResult["ret"]==0){
                echo "<tr><td>文章发表:</td><td><a href='".$aResult["url"]."' target='_blank'>点此查看,博文编号:".$aResult["blogid"]."</a><input type='button' onclick='location.href=\"../index.php\";' value='返回首页'  class='button'/></td></tr>";
            }else{
                debug($aResult);
            }
        }
        echo "</table>";
    }else{
?>
<form action="add_one_blog.php" method="POST">
<table>
  <tr>
    <td class="narrow-label">标题</td>
    <td><input type="text" name="title" class="longinput" /></td>
  </tr>
  <tr>
    <td class="narrow-label">内容</td>
    <td><textarea name="content" class="text"></textarea></td>
  </tr>
  <tr>
    <td></td>
    <td><input type="submit" value="发表一篇日志" class="button" /><input type="button" onclick='location.href="../index.php";' value="返回首页"  class="button"/></td>
  </tr>
</table>
<?php
    }
?>
</div>
</html>