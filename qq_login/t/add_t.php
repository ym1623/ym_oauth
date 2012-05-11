<?php
    header("Content-type:text/html; charset=UTF-8;");
	if(!file_exists('../common/config.php')){
		header("Location:../install/index.php");
		exit;
	}
    include_once("../common/function.php");
?>
<html>
<head>
<link href="../style/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
</head>
<body>
<h1>QQ互联 - 发表微博</h1>
<div class="list-div">
<?php
    if(isset($_POST)&&!empty($_POST)){
        echo "<table>";
        $sUrl = "https://graph.qq.com/t/add_t";
        $aPOSTParam = array(
            "access_token" => $_SESSION["access_token"],
            "oauth_consumer_key"    =>    $aConfig["appid"],
            "openid"                =>    $_SESSION["openid"],
            "format"                =>    "json",
            "content"                =>        (get_magic_quotes_runtime()?stripslashes($_POST["content"]):$_POST["content"]),
            "clientip"                =>        getip(),
            "syncflag"                =>        $_POST["syncflag"]
        );
        $sContent = post($sUrl,$aPOSTParam);
        if($sContent!==FALSE){
            $aResult = json_decode($sContent,true);
            if($aResult["ret"]==0){
                echo "<tr><td class='narrow-label'>微博编号</td><td>".$aResult["data"]["id"]."</td></tr>";
                echo "<tr><td class='narrow-label'>发表时间</td><td>时间戳:".$aResult["data"]["time"]." 发布时间:".date("Y-m-d H:i:s",$aResult["data"]["time"])."</td></tr>";
                echo "<tr><td class='narrow-label'>辅助功能:</td><td><a href='./del_t.php?id=".$aResult["data"]["id"]."'>删除微博</a>&nbsp;<a href='./get_repost_list.php?id=".$aResult["data"]["id"]."'>获取回复</a></td></tr>";
            }else{
                echo "<tr><td class='narrow-label'>错误信息</td><td>".$aResult["msg"]."</td></tr>";
            }
        }
        echo "<tr><td class='narrow-label'></td><td><input type='button' class='button' value='返回首页' onclick='location.href=\"../index.php\";' /></td></tr>";
        echo "</table>";
    }else{
?>
<form action="add_t.php" method="POST">
<table>
  <tr>
    <td class="narrow-label">微博内容</td>
    <td><textarea name="content" class="text"></textarea></td>
  </tr>
  <tr>
    <td class="narrow-label">同步到QQ空间</td>
    <td>
      <label for="syncflag_1"><input type="radio" name="syncflag" id="syncflag_1" value="1" />不同步</label>
      <label for="syncflag_0"><input type="radio" name="syncflag" id="syncflag_0" value="0" />同步</label>
    </td>
  </tr>
  <tr>
    <td class="narrow-label"></td>
    <td><input type="submit" value="发表微博" class="button" /><input type="button" class="button" value="返回首页" onclick="location.href='../index.php';" /></td>
  </tr>
</table>
</form>
<?php
    }
?>
</div>
</body>
</html>