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
<h1>QQ互联 - 获取一条微博的转播或评论信息列表</h1>
<div class="list-div">
<?php
    echo "<table>";
    $sUrl = "https://graph.qq.com/t/get_repost_list";
    $aGETParam = array(
        "access_token" => $_SESSION["access_token"],
        "oauth_consumer_key"    =>    $aConfig["appid"],
        "openid"                =>    $_SESSION["openid"],
        "format"                =>    "json",
        "flag"                    =>        2,
        "rootid"                =>        $_GET["id"],
        "pageflag"                =>        0,
        "pagetime"                =>        isset($_GET["pagetime"])&&!empty($_GET["pagetime"])?$_GET["pagetime"]:0,
        "reqnum"                =>        100,
        "twitterid"                =>        isset($_GET["twitterid"])&&!empty($_GET["twitterid"])?$_GET["twitterid"]:0
    );
    $sContent = get($sUrl,$aGETParam);
    if($sContent!==FALSE){
        $aResult = json_decode($sContent,true);
        if($aResult["ret"]==0){
            $sUrl = "";
            foreach($aResult["data"]["info"] as $i=>$v){
                echo "<tr><td class='narrow-label'></td><td>";
                echo "城市代码：".$v["city_code"];
                echo "<br />转播次数：".$v["count"];
                echo "<br />国家代码：".$v["country_code"];
                echo "<br />心情类型:".$v["emotiontype"];
                echo "<br />心情地址:".$v["emotionurl"];
                echo "<br />微博来源：".$v["from"];
                echo "<br />来源地址：".$v["fromurl"];
                echo "<br />地址位置:".$v["geo"];
                echo "<br />用户头像:".(!empty($v["head"])?"<img src='".$v["head"]."/100' border='0'/>":"无头像");
                echo "<br />微博帖子编号：".$v["id"];
                echo "<br />微博图片：".($v["image"]==NULL?"无图片":"<img src='".$v["image"][0]."/460' />");
                echo "<br />用户实名：".$v["isrealname"];
                echo "<br />VIP：".$v["isvip"];
                echo "<br />latitude：".$v["latitude"];
                echo "<br />位置：".$v["location"];
                echo "<br />longitude：".$v["longitude"];
                echo "<br />评论数：".$v["mcount"];
                echo "<br />音乐：".$v["music"];
                echo "<br />用户名：".$v["name"];
                echo "<br />用户昵称：".$v["nick"];
                echo "<br />OpenId:".$v["openid"];
                echo "<br />原始文字：".$v["origtext"];
                echo "<br />省份编号：".$v["province_code"];
                echo "<br />是否是自己：".$v["self"];
                echo "<br />状态：".$v["status"];
                echo "<br />微博内容：".$v["text"];
                echo "<br />时间戳：".$v["timestamp"]." 时间:".date("Y-m-d H:i:s",$v["timestamp"]);
                echo "<br />类型：".$v["type"];
                echo "<br />视频：".$v["video"];
                echo "<br /><fieldset><legend>微博来源</legend>";
                echo "城市编号：".$v["source"]["city_code"];
                echo "<br />转播次数：".$v["source"]["count"];
                echo "<br />国家编号：".$v["source"]["country_code"];
                echo "<br />微博来源：".$v["source"]["from"];
                echo "<br />微博来源网址:".$v["source"]["fromurl"];
                echo "<br />GEO：".$v["source"]["geo"];
                echo "<br />头像：".(!empty($v["source"]["head"])?"<img src='".$v["source"]["head"]."/100' border='0' />":"无头像");
                echo "<br />微博图片:";
                foreach($v["source"]["image"] as $k=>$t){
                    echo "<img src='".$t."/460' border='0' />";
                }
                echo "<br />VIP：".$v["source"]["isvip"];
                echo "<br />位置:".$v["source"]["location"];
                echo "评论次数：".$v["source"]["mcount"];
                echo "<br />音乐:".$v["source"]["music"];
                echo "<br />用户名：".$v["source"]["name"];
                echo "<br />用户昵称:".$v["source"]["nick"];
                echo "OPENID：".$v["source"]["openid"];
                echo "<br />原始内容：".$v["source"]["origtext"];
                echo "<br />省份代码：".$v["source"]["province_code"];
                echo "<br />是否是自己：".$v["source"]["self"];
                echo "<br />微博状态：".$v["source"]["status"];
                echo "<br />微博文字:".$v["source"]["text"];
                echo "<br />时间戳：".$v["source"]["timestamp"]." 时间：".date("Y-m-d H:i:s",$v["source"]["timestamp"]);
                echo "<br />类型:".$v["source"]["type"];
                echo "<br />视频:".$v["source"]["video"];
                echo "</fieldset></td></tr>";
                $sUrl  ="&pagetime=".$v["timestamp"]."&twitterid=".$v["id"];
            }
            echo "<tr><td class='narrow-label'>记录总数</td><td>".$aResult["data"]["totalnum"]."</td>";
            echo "<tr><td class='narrow-label'>服务器时间戳</td><td>".$aResult["data"]["timestamp"]."</td>";
            if($aResult["data"]["hasnext"]==0){
                echo "<tr><td class='narrow-label'></td><td><input type='button' value='下一页' class='button' onclick='location.href=\"get_repost_list.php?id=".$_GET["id"].$sUrl."\";' /></td></tr>";
            }
        }else{
            echo "<tr><td class='narrow-label'>错误信息</td><td>".$aResult['msg']."</td></tr>";
        }
    }
    echo "</table>";
?>
</div>
</body>
</html>