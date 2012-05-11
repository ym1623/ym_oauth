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
<h1>QQ互联 - 发表图片微博</h1>
<div class="list-div">
<?php
    if(isset($_FILES["pic"]["tmp_name"])&&!empty($_FILES["pic"]["tmp_name"])){
        echo "<table>";
        $sUrl = "https://graph.qq.com/t/add_pic_t";
        $aPOSTParam = array(
            "access_token" => $_SESSION["access_token"],
            "oauth_consumer_key"    =>    $aConfig["appid"],
            "openid"                =>    $_SESSION["openid"],
            "format"                =>    "json",
            "content"                =>        (get_magic_quotes_runtime()?stripslashes($_POST["content"]):$_POST["content"]),
            "clientip"                =>        getip(),
            "syncflag"                =>        $_POST["syncflag"]
        );
        $aFileParam = array(
            "pic"    =>    $_FILES["pic"]["tmp_name"]
        );
        $sContent = upload($sUrl,$aPOSTParam,$aFileParam);
        if($sContent!==FALSE){
            $aResult = json_decode($sContent,true);
            if($aResult["ret"]==0){
                echo "<tr><td class='narrow-label'>微博编号</td><td>".$aResult["data"]["id"]."</td></tr>";
                echo "<tr><td class='narrow-label'>发表时间</td><td>时间戳:".$aResult["data"]["time"]." 发布时间:".date("Y-m-d H:i:s",$aResult["data"]["time"])."</td></tr>";
                echo "<tr><td class='narrow-label'>图片地址</td><td><img src='".$aResult["imgurl"]."/460' border='0' /></td></tr>";
                echo "<tr><td class='narrow-label'>辅助功能:</td><td><a href='./del_t.php?id=".$aResult["data"]["id"]."'>删除微博</a>&nbsp;<a href='./get_repost_list.php?id=".$aResult["data"]["id"]."'>获取回复</a></td></tr>";
            }else{
                echo "<tr><td class='narrow-label'>错误信息</td><td>".$aResult["msg"]."</td></tr>";
            }
        }
        echo "<tr><td class='narrow-label'></td><td><input type='button' class='button' value='返回首页' onclick='location.href=\"../index.php\";' /></td></tr>";
        echo "</table>";
    }else{
?>
<form action="add_pic_t.php" method="POST" enctype="multipart/form-data">
<table>
  <tr>
    <td class="narrow-label">微博图片</td>
    <td><input type="file" name="pic" class="input" /></td>
  </tr>
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
    <td><input type="submit" value="发表图片微博" class="button" /><input type="button" value="返回首页" class="button" onclick="location.href='../index.php';" /></td>
  </tr>
</table>
</form>
<?php
    }
?>
</div>
</body>
</html>