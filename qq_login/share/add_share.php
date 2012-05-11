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
<script>
$(document).ready(function(){
    $("#type_4").click(function(){
        $("#playlist").hide();
    });
    $("#type_5").click(function(){
        $("#playlist").show();
    });
});
</script>
</head>
<body>
<h1>QQ互联集成PHP SDK - 添加分享</h1>
<div class="list-div">
<?php
    if(isset($_POST)&&!empty($_POST)){
        echo "<table>";
        $sUrl = "https://graph.qq.com/share/add_share";
        $aPOSTParam = array(
            "access_token" => $_SESSION["access_token"],
            "oauth_consumer_key"    =>    $aConfig["appid"],
            "openid"                =>    $_SESSION["openid"],
            "format"                =>    "json",
            "title"                    =>        (get_magic_quotes_runtime()?stripslashes($_POST["title"]):$_POST["title"]),
            "url"                    =>        (get_magic_quotes_runtime()?stripslashes($_POST["url"]):$_POST["url"]),
            "comment"                =>        (get_magic_quotes_runtime()?stripslashes($_POST["comment"]):$_POST["comment"]),
            "summary"                =>        (get_magic_quotes_runtime()?stripslashes($_POST["summary"]):$_POST["summary"]),
            "images"                =>        (get_magic_quotes_runtime()?stripslashes($_POST["images"]):$_POST["images"]),
            "source"                =>        $_POST["source"],
            "type"                    =>        $_POST["type"],
            "site"                    =>        (get_magic_quotes_runtime()?stripslashes($_POST["site"]):$_POST["site"])
        );
        if(isset($_POST["nswb"])&&!empty($_POST["nswb"])){
            $aPOSTParam["nswb"] = intval($_POST["nswb"]);
        }
        if($aPOSTParam["type"]==5){
            $aPOSTParam["playweb"] = $_POST["playweb"];
        }
        $sContent = post($sUrl,$aPOSTParam);
        if($sContent!==FALSE){
            $aResult = json_decode($sContent,true);
            if($aResult["ret"]==0){
                echo "<tr><td class='narrow-label'>分享标号:</td><td>".$aResult['share_id']."</td></tr>";
            }else{
                echo "<tr><td class='narrow-label'>错误反馈：</td><td>".$aResult['msg']."</td></tr>";
            }
        }
        echo "<tr><td></td><td><input type='button' onclick='location.href=\"../index.php\";' value='返回首页'  class='button' /></td></tr>";
        echo "</table>";
    }else{
?>
<form action="add_share.php" method="POST">
<table>
  <tr>
    <td class="narrow-label">分享标题</td>
    <td><input type="input" name="title" class="input" /></td>
  </tr>
  <tr>
    <td class="narrow-label">分享网址</td>
    <td><input type="input" name="url" class="input" /></td>
  </tr>
  <tr>
    <td class="narrow-label">分享理由</td>
    <td><input type="input" name="comment" class="input" /></td>
  </tr>
  <tr>
    <td class="narrow-label">分享摘要</td>
    <td><input type="input" name="summary" class="input" /></td>
  </tr>
  <tr>
    <td class="narrow-label">分享图片</td>
    <td><input type="input" name="images" class="input" /></td>
  </tr>
  <tr>
    <td class="narrow-label">分享场景</td>
    <td>
      <label for="source_1"><input type="radio" value="1" id="source_1" name="source" />通过网页</label>
      <label for="source_2"><input type="radio" value="2" id="source_2" name="source" />通过手机</label>
      <label for="source_3"><input type="radio" value="3" id="source_3" name="source" />通过软件</label>
      <label for="source_4"><input type="radio" value="4" id="source_4" name="source" />通过IPHONE</label>
      <label for="source_5"><input type="radio" value="5" id="source_5" name="source" />通过IPAD</label>
    </td>
  </tr>
  <tr>
    <td class="narrow-label">分享类型</td>
    <td>
      <label for="type_4"><input type="radio" value="4" name="type" id="type_4" />网站</label>
      <label for="type_5"><input type="radio" value="5" name="type" id="type_5" />视频</label>
    </td>
  </tr>
  <tr id="playlist" style="display:none;">
    <td class="narrow-label">视频地址</td>
    <td><input type="input" value="" name="playurl" class="input" /></td>
  </tr>
  <tr>
    <td class="narrow-label">分享来源</td>
    <td><input type="input" name="site" value="" class="input" /></td>
  </tr>
  <tr>
    <td class="narrow-label"></td>
    <td><label for="nswb"><input type="checkbox" name="nswb" value="1" id="nswb" />分享不默认同步到微博</label></td>
  </tr>
  <tr>
    <td class="narrow-label"></td>
    <td><input type="submit" value="添加分享" class="button" /><input type="button" onclick='location.href="../index.php";' value="返回首页"  class="button"/></td>
  </tr>
</table>
<?php
    }
?>
</div>
</html>