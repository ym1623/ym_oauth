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
	$("#numok").click(function(){
		html = "";
		for(i=1;i<=$("#num").val();i++){
			html = html + "<tr><td class=\"narrow-label\">照片"+i+"</td><td><input type=\"file\" class=\"input\" name=\"picture["+i+"]\" /></td></tr>";
		}
		$("#filelist").html(html);
	});
});
</script>
</head>
<body>
<h1>QQ互联集成PHP SDK - 添加多张相片</h1>
<div class="list-div">
<?php
	if(isset($_POST)&&!empty($_POST)){
		echo "<table>";
		$num = $_POST["num"];
		$succ = 0;
		for($i=1;$i<=$num;$i++){
			if(isset($_FILES["picture"]["tmp_name"][$i])&&!empty($_FILES["picture"]["tmp_name"][$i])){
				$sUrl = "https://graph.qq.com/photo/upload_pic";
				$aPOSTParam = array(
					"access_token" => $_SESSION["access_token"],
					"oauth_consumer_key"    =>    $aConfig["appid"],
					"openid"                =>    $_SESSION["openid"],
					"format"                =>    "json",
					"photodesc"             =>    "",
					"title"                 =>    $_FILES["picture"]["name"][$i],
					"needfeed"              =>      $_POST["needfeed"],
					"successnum"			=>		$succ,
					"picnum"				=>		$num
				);
				$succ++;
				$aFileParam = array(
					"picture"    =>    $_FILES["picture"]["tmp_name"][$i]
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
			}
		}		
		echo "<tr><td class='narrow-label'></td><td><input type='button' value='返回首页' onclick='location.href=\"../index.php\"' class='button' /></td></tr>";
		echo "</table>";
	}else{
?>
<form action="upload_pic_2.php" method="post" enctype="multipart/form-data">
<table>
  <tr>
	<td class="narrow-label">上传张数</td>
	<td><input type="input" id="num" name="num" class="shortinput" value="0" /><input type="button" id="numok" class="button" value="确定张数" /></td>
  </tr>
  <tbody id="filelist"></tbody>
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