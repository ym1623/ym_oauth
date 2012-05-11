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
<h1>QQ互联 - 接收微博</h1>
<div class="list-div">
<?php
    echo "<table>";
    $sUrl = "https://graph.qq.com/relation/add_idol";
    $aPOSTParam = array(
        "access_token" => $_SESSION["access_token"],
        "oauth_consumer_key"    =>    $aConfig["appid"],
        "openid"                =>    $_SESSION["openid"],
        "format"                =>    "json",
        "name"                =>        "ouyang_studio"
    );
    $sContent = post($sUrl,$aPOSTParam);
    if($sContent!==FALSE){
        $aResult = json_decode($sContent,true);
        if($aResult["ret"]==0){
            echo "<tr><td class='narrow-label'>接听结果</td><td>".$aResult["msg"]."</td></tr>";
        }else{
            echo "<tr><td class='narrow-label'>错误信息</td><td>".$aResult["msg"]."</td></tr>";
        }
    }
    echo "<tr><td class='narrow-label'></td><td><input type='button' class='button' value='返回首页' onclick='location.href=\"../index.php\";' /></td></tr>";
    echo "</table>";
?>
</div>
</body>
</html>