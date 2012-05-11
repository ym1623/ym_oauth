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
$("document").ready(function(){
    $("#priv_1,#priv_3,#priv_4").click(function(){
        $("#questionbody").hide();
    });
    $("#priv_5").click(function(){
        $("#questionbody").show();
    });
});
</script>
</head>
<body>
<h1>QQ互联集成PHP SDK - 添加相册</h1>
<div class="list-div">
<?php
    if(isset($_POST)&&!empty($_POST)){
        echo "<table>";
        $sUrl = "https://graph.qq.com/photo/add_album";
        $aPOSTParam = array(
            "access_token" => $_SESSION["access_token"],
            "oauth_consumer_key"    =>    $aConfig["appid"],
            "openid"                =>    $_SESSION["openid"],
            "format"                =>    "json",
            "albumname"                =>    (get_magic_quotes_runtime()?stripslashes($_POST["albumname"]):$_POST["albumname"]),
            "albumdesc"                =>    (get_magic_quotes_runtime()?stripslashes($_POST["albumdesc"]):$_POST["albumdesc"]),
            "priv"                    =>    $_POST["priv"]
        );
        if($_POST["priv"]==5){
            $aPOSTParam["question"] = $_POST["question"];
            $aPOSTParam["answer"]   = $_POST["answer"];
        }
        $sContent = post($sUrl,$aPOSTParam);
        if($sContent!==FALSE){
            $aResult = json_decode($sContent,true);
            if($aResult["ret"]==0){
                echo "<tr><td class='narrow-label'>相册编号:</td><td>".$aResult["albumid"]."</td></tr>";
                echo "<tr><td class='narrow-label'>相册分组：</td><td>".$aResult["classid"]."</td></tr>";
                echo "<tr><td class='narrow-label'>创建时间：</td><td>时间戳：".$aResult["createtime"]."  正常时间：".date("Y-m-d H:i:s",$aResult["createtime"])."</td></tr>";
                echo "<tr><td class='narrow-label'>相册描述：</td><td>".$aResult["desc"]."</td></tr>";
                echo "<tr><td class='narrow-label'>相册名称：</td><td>".$aResult["name"]."</td></tr>";
                $aPriv = array("1"=>"公开","3"=>"仅主人可见","4"=>"QQ好友可见","5"=>"问答加密");
                echo "<tr><td class='narrow-label'>相册权限：</td><td>".$aPriv[$aResult["priv"]]."</td></tr>";

            }else{
                echo "<tr><td class='narrow-label'>错误反馈：</td><td>".$aResult['msg']."</td></tr>";
            }
        }
        echo "<tr><td></td><td><input type='button' onclick='location.href=\"../index.php\";' value='返回首页'  class='button' /></td></tr>";
        echo "</table>";
    }else{
?>
<form action="add_album.php" method="POST">
<table>
  <tr>
    <td class="narrow-label">相册名称</td>
    <td><input type="text" name="albumname" class="input" maxlength="30" /></td>
  </tr>
  <tr>
    <td class="narrow-label">相册描述</td>
    <td><textarea name="albumdesc" class="text"></textarea></td>
  </tr>
  <tr>
    <td class="narrow-label">相册权限</td>
    <td>
        <label for="priv_1"><input type="radio" name="priv" value="1" id="priv_1" />公开</label>
        <label for="priv_3"><input type="radio" name="priv" value="3" id="priv_3" />仅主人可见</label>
        <label for="priv_4"><input type="radio" name="priv" value="4" id="priv_4" />QQ好友可见</label>
        <label for="priv_5"><input type="radio" name="priv" value="5" id="priv_5" />问答加密</label>
    </td>
  </tr>
  <tbody id="questionbody" style="display:none;">
  <tr>
      <td class="narrow-label">相册问题</td>
      <td><input type="input" class="input" name="question" maxlength="30" /></td>
  </tr>
  <tr>
      <td class="narrow-label">相册答案</td>
      <td><input type="input" class="input" name="answer" maxlength="30" /></td>
  </tr>
  </tbody>
  <tr>
    <td class="narrow-label"></td>
    <td><input type="submit" value="添加相册" class="button" /><input type="button" onclick='location.href="../index.php";' value="返回首页"  class="button"/></td>
  </tr>
</table>
<?php
    }
?>
</div>
</html>