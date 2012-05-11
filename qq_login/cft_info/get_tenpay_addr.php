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
<h1>QQ互联集成PHP SDK - 获取财付通信息</h1>
<div class="list-div">
<table>
<?php
    $sUrl = "https://graph.qq.com/cft_info/get_tenpay_addr";
    $aPOSTParam = array(
    	"access_token" => $_SESSION["access_token"],
    	"oauth_consumer_key"    =>    $aConfig["appid"],
    	"openid"                =>    $_SESSION["openid"],
        "offset"  => 0,
    	"limit"   => 5,
    	"ver"     => 1,
    	"res_fmt" => "json"
    );
    $sContent = post($sUrl,$aPOSTParam);
    if($sContent!==FALSE){
    	$aResult = json_decode(preg_replace_callback(array('/\\\x([\d|a..g]{2})/i'),create_function('$matches','return chr(intval(base_convert($matches[1],16,10)));'),$sContent),true);
		echo "<tr><td class='narrow-label'>JSON修正解析串</td><td><pre>".var_export($aResult,true)."</pre></td></tr>";
		echo "<tr><td class='narrow-label'>地址条数</td><td>".$aResult["ret_num"]."</td></tr>";
		for($k=0;$k<$aResult["ret_num"];$k++){
			echo "<tr><td class='narrow-label'>收货人姓名</td><td>".$aResult["Fname_".$k]."</td></tr>";
			echo "<tr><td class='narrow-label'>收货人地址</td><td>".$aResult["Faddrstreet_".$k]."</td></tr>";
			echo "<tr><td class='narrow-label'>收货人电话</td><td>".$aResult["Ftel_".$k]."</td></tr>";
			echo "<tr><td class='narrow-label'>收货人手机</td><td>".$aResult["Fmobile_".$k]."</td></tr>";
			echo "<tr><td class='narrow-label'>收货人邮编</td><td>".$aResult["Fzipcode_".$k]."</td></tr>";
			echo "<tr><td class='narrow-label'>使用次数</td><td>".$aResult["FUsedCount_".$k]."</td></tr>";
			echo "<tr><td class='narrow-label'>上次使用</td><td>".$aResult["Flastuse_time_".$k]."</td></tr>";
			echo "<tr><td class='narrow-label'>上次修改</td><td>".$aResult["Fmod_time_".$k]."</td></tr>";
			echo "<tr><td class='narrow-label'>创建时间</td><td>".$aResult["Fcreate_time_".$k]."</td></tr>";
			echo "<tr><td class='narrow-label'>地区编号</td><td>".$aResult["FRegionId_".$k]."</td></tr>";
			echo "<tr><td class='narrow-label'>索引编号</td><td>".$aResult["Findex_".$k]."</td></tr>";
		}
    }
	echo "<tr><td class='narrow-label'></td><td><input type='button' value='返回首页' class='button' onclick='location.href=\"../index.php\";' /></td></tr>";
?>
</table>
</div>
</body>
</html>