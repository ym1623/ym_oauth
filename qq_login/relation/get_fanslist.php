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
<h1>QQ互联 - 获取登录用户的听众列表</h1>
<div class="list-div">
<?php
    echo "<table>";
    $sUrl = "https://graph.qq.com/relation/get_fanslist";
    $aGETParam = array(
        "access_token" => $_SESSION["access_token"],
        "oauth_consumer_key"    =>    $aConfig["appid"],
        "openid"                =>    $_SESSION["openid"],
        "format"                =>    "json",
        "reqnum"                =>        30,
        "startindex"            =>        (isset($_GET["p"])&&is_numeric($_GET["p"])?intval($_GET["p"])*30:0),
        "mode"                    =>        1,
        "install"                =>        0
    );
    $sContent = get($sUrl,$aGETParam);
    if($sContent!==FALSE){
        $aResult = json_decode($sContent,true);
        if($aResult["ret"]==0){
            foreach($aResult["data"]["info"] as $i=>$v){
                echo "<tr><td class='narrow-label'><a href='../user/get_other_info.php?fopenid=".$v["openid"]."'>".$v["nick"]."</a></td><td>省份编号：".$v["province_code"]."<br />城市编号：".$v["city_code"]."<br />国家标号：".$v["country_code"]."<br />听众个数：".$v["fansnum"]."<br />用户头像：".($v["head"]?"<img src='".$v["head"]."/100' />":"无头像")."<br />收听个数：".$v["idolnum"]."<br />我的粉丝：".($v["isfans"]?"是":"否")."<br />我是听众：".($v["isidol"]?"是":"否")."<br />是否实名：".($v["isrealname"]?"是":"否")."<br />是否VIP：".($v["isvip"]?"是":"否")."<br />位置：".$v["location"]."<br />微博帐号：".$v["name"]."<br />微博昵称：".$v["nick"]."<br />用户OPENID：".$v["openid"]."<br />用户性别：".(($v["sex"]==0)?"未知":(($v["sex"]==1)?"男":"女"))."<br />用户标签：";
                if(isset($v["tag"])&&!empty($v["tag"])){
                    foreach($v["tag"] as $k=>$t){
                        echo "【<a href='http://search.t.qq.com/tag.php?k=".$t["name"]."' target='_blank'>".$t["name"]."</a>】";
                    }
                }else{
                    echo "无标签";
                }
                echo "<br />";
                if($v["tweet"][0]["timestamp"]==0){ echo "暂无微博";}else{ echo "最新微博：".$v["tweet"][0]["text"]."&nbsp;&nbsp; 微博编号：".$v["tweet"][0]["id"]."&nbsp;&nbsp;微博来源：".$v["tweet"][0]["from"]."&nbsp;&nbsp;发表时间：".date("Y-m-d H:i:s",$v["tweet"][0]["timestamp"]);}
                echo"</td></tr>";
            }
            if(intval($aResult["data"]["hasnext"])===0){
                echo "<tr><td class='narrow-label'></td><td><input type='button' value='下一页' onclick='location.href=\"get_fanslist.php?p=".(isset($_GET["p"])&&is_numeric($_GET["p"])?(intval($_GET["p"])+1):"1")."\";' class='button' /></td></tr>";
            }
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