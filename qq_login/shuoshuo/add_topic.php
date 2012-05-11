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
    $("#richtype").change(function(){
        i = $("#richtype").val();
        if(i!=1){
            $("#piclist").hide();
        }else{
            $("#piclist").show();
        }
        if(i!=2){
            $("#weblist").hide();
        }else{
            $("#weblist").show();
        }
        if(i!=3){
            $("#videolist").hide();
        }else{
            $("#videolist").show();
        }
    });
});
</script>
</head>
<body>
<h1>QQ互联集成PHP SDK - 发表说说</h1>
<div class="list-div">

<?php
    if(isset($_POST)&&!empty($_POST)){
        echo "<table>";

        $aPOSTParam = array(
            "access_token" => $_SESSION["access_token"],
            "oauth_consumer_key"    =>    $aConfig["appid"],
            "openid"                =>    $_SESSION["openid"],
            "format"                =>    "json"
        );
        if($_POST["richtype"]==1){
            if(isset($_FILES["album"]["tmp_name"])&&!empty($_FILES["album"]["tmp_name"])){
                //处理相关事件
                $aFileParam["picture"] = $_FILES["album"]["tmp_name"];
                $sUrl = "https://graph.qq.com/photo/upload_pic";
                $sContent =upload($sUrl, $aPOSTParam,$aFileParam);
                if($sContent!==FALSE){
                    $aResult = json_decode($sContent,true);
                    $aPOSTParam["richtype"] = 1;
                    $aPOSTParam["richval"]  = $aResult["albumid"].",".$aResult["lloc"].",".$aResult["sloc"];
                }
            }else{
                $aPOSTParam["richtype"] = 1;
                $aPOSTParam["richval"] = "url=".$_POST["picurl"]."&width=".$_POST["width"]."&height=".$_POST["height"];
            }
        }elseif($_POST["richtype"]==2){
            $aPOSTParam["richtype"] = 2;
            $aPOSTParam["richval"] = $_POST["weburl"];
        }elseif($_POST["richtype"]==3){
            $aPOSTParam["richtype"] = 3;
            $aPOSTParam["richval"] = urlencode($_POST["videourl"]);
        }
        $aPOSTParam["con"]            = (get_magic_quotes_runtime()?stripslashes($_POST["con"]):$_POST["con"]);
        $aPOSTParam["third_source"] = $_POST["third_source"];
        $sUrl = "https://graph.qq.com/shuoshuo/add_topic";
        $sContent = post($sUrl,$aPOSTParam);
        if($sContent!==FALSE){
            $aResult = json_decode($sContent,true);
            if($aResult["data"]["ret"]==0){

            }else{
                echo "<tr><td class='narrow-label'>错误编号：</td><td>".$aResult['data']['ret']."</td></tr>";
            }
        }
        echo "<tr><td></td><td><input type='button' onclick='location.href=\"../index.php\";' value='返回首页'  class='button' /></td></tr>";
        echo "</table>";
    }else{
?>
<form action="add_topic.php" method="POST" enctype="multipart/form-data">
<table>
  <tr>
    <td class="narrow-label">说说类型</td>
    <td><select name="richtype" id="richtype" class="select">
    <option value="0">普通</option>
    <option value="1">图片</option>
    <option value="2">网页</option>
    <option value="3">视频</option>
    </select></td>
  </tr>
  <tr id="piclist" style="display:none;">
    <td class="narrow-label">图片</td>
    <td>普通方式:<input type="input" class="input" name="picurl" />&nbsp;&nbsp;高度:<input type="input" class="shortinput" name="height" />&nbsp;&nbsp;宽度:<input type="input" class="shortinput" name="width" /><br />文件上传:<input type="file" name="album" class="input" /></td>
  </tr>
  <tr id="weblist" style="display:none;">
    <td class="narrow-label">网页</td>
    <td><input type="input" class="input"name="weburl" /></td>
  </tr>
  <tr  id="videolist" style="display:none;">
    <td class="narrow-label">视频</td>
    <td><input type="input" class="input"name="videourl" /></td>
  </tr>
  <tr>
    <td class="narrow-label">说说内容</td>
    <td><textarea name="con" class="text"></textarea></td>
  </tr>
<!--
  <tr>
    <td class="narrow-label">地址：</td>
    <td>明文:<input type="input" class="input" name="lbs_nm" value="腾讯大厦" />&nbsp;&nbsp;经度:<input type="input" class="shortinput" name="lbs_x" value="39.909407" />&nbsp;&nbsp;纬度:<input type="input" class="shortinput" name="lbs_y" value="116.397521" />LBS 系统自动获取</td>
  </tr>
-->
  <tr>
    <td class="narrow-label">三方平台类型</td>
    <td>
        <label for="third_source_1"><input type="radio" name="third_source" id="third_source_1" value="1" />QQ空间</label>
        <label for="third_source_2"><input type="radio" name="third_source" id="third_source_2" value="2" />腾讯朋友</label>
        <label for="third_source_3"><input type="radio" name="third_source" id="third_source_3" value="3" />腾讯微博平台</label>
        <label for="third_source_4"><input type="radio" name="third_source" id="third_source_4" value="4" />腾讯Q+平台</label>
    </td>
  </tr>
  <tr>
    <td class="narrow-label"></td>
    <td><input type="submit" value="添加说说" class="button" /><input type="button" onclick='location.href="../index.php";' value="返回首页"  class="button"/></td>
  </tr>
</table>
<?php
    }
?>
</div>
</html>