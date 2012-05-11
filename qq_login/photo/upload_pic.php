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
<h1>QQ互联集成PHP SDK - 添加相片</h1>
<div class="list-div">
<?php
echo "<table>";
    if(isset($_FILES["picture"]["tmp_name"])&&!empty($_FILES["picture"]["tmp_name"])){
        $sUrl = "https://graph.qq.com/photo/upload_pic";
        $aPOSTParam = array(
            "access_token" => $_SESSION["access_token"],
            "oauth_consumer_key"    =>    $aConfig["appid"],
            "openid"                =>    $_SESSION["openid"],
            "format"                =>    "json",
            "photodesc"             =>    (get_magic_quotes_runtime()?stripslashes($_POST["photodesc"]):$_POST["photodesc"]),
            "title"                 =>    isset($_POST["title"])&&!empty($_POST["title"]) ? $_POST["title"] : $_FILES["picture"]["name"],
            "albumid"               =>    $_POST["albumid"],
            "needfeed"                =>      $_POST["needfeed"]
        );
        $aFileParam = array(
            "picture"    =>    $_FILES["picture"]["tmp_name"]
        );
        $sContent = upload($sUrl,$aPOSTParam,$aFileParam);
        if($sContent!==FALSE){
            $aResult = json_decode($sContent,true);
            if($aResult["ret"]==0){
                echo "<tr><td class='narrow-label'>相册编号</td><td>".$aResult["albumid"]."</td></tr>";
                echo "<tr><td class='narrow-label'>相片高度</td><td>".$aResult["height"]."</td></tr>";
                echo "<tr><td class='narrow-label'>大图地址</td><td><img src='".$aResult["large_url"]."' border='0' /></td></tr>";
                echo "<tr><td class='narrow-label'>大图编号</td><td>".$aResult["lloc"]."</td></tr>";
                echo "<tr><td class='narrow-label'>小图编号</td><td>".$aResult["sloc"]."</td></tr>";
                echo "<tr><td class='narrow-label'>小图地址</td><td><img src='".$aResult["small_url"]."' border='0' /></td></tr>";
                echo "<tr><td class='narrow-label'>相片宽度</td><td>".$aResult['width']."</td></tr>";
            }
        }else{
            echo "<tr><td class='narrow-label'>反馈提示</td><td>".$aResult["msg"]."</td></tr>";
        }
echo "<tr><td class='narrow-label'></td><td><input type='button' value='返回首页' onclick='location.href=\"../index.php\"' class='button' /></td></tr>";
echo "</table>";
    }else{
?>
<form action="upload_pic.php" method="post" enctype="multipart/form-data">
<table>
  <tr>
    <td class="narrow-label">上传照片</td>
    <td><input type="file" class="input" name="picture" /></td>
  </tr>
  <tr>
    <td class="narrow-label">照片名称</td>
    <td><input type="input" name="title" class="input" /></td>
  </tr>
  <tr>
    <td class="narrow-label">照片描述</td>
    <td><textarea name="photodesc" class="text"></textarea></td>
  </tr>
<?php
    $sUrl = "https://graph.qq.com/photo/list_album";
    $aGetParam = array(
        "access_token" => $_SESSION["access_token"],
        "oauth_consumer_key"    =>    $aConfig["appid"],
        "openid"                =>    $_SESSION["openid"],
        "format"                =>    "json"
    );
    $aConfig["debug"] = 0;
    $sContent = get($sUrl,$aGetParam);
    if($sContent!==FALSE){
        $aResult = json_decode($sContent,true);
?>
  <tr>
    <td class="narrow-label">相片分组</td>
    <td><select name="albumid" class="select">
    <?php foreach($aResult["album"] as $key=>$v){
    ?><option value="<?php echo $v["albumid"];?>"><?php echo $v["name"];?></option>
    <?php
    }
    ?>
    </select></td>
  </tr>
<?php
    }
?>
<tr>
  <td class="narrow-label">发送Feeds</td>
  <td><label for="needfeed_0"><input type="radio" name="needfeed" id="needfeed_0" value="0" />不发送Feeds</label>
  <label for="needfeed_1"><input type="radio" name="needfeed" id="needfeed_1" value="1" checked />发送Feeds</label></td>
</tr>
<tr>
  <td class="narrow-label"></td>
  <td><input type="submit" class="button" value="添加照片" /><input type="button" class="button" value="返回首页" onclick="location.href='../index.php';" /></td>
</tr>
</table>
</form>
<?php
    }
?>
</div>
</body>
</html>