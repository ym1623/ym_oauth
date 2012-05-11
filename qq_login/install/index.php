<?php
    header("Content-type:text/html; charset=UTF-8;");
    if(!function_exists("curl_init")){
        die("PHP SDK 需要 CURL支持。");
    }
    $MemType = function_exists("memcache_connect");
    $aQQApi = array(
    	"get_user_info"=>"获取用户在QQ空间的个人资料",
    	"add_topic"=>"发表一条说说到QQ空间",
    	"add_one_blog"=>"发表一篇日志到QQ空间",
    	"add_album"=>"创建一个QQ空间相册",
    	"upload_pic"=>"上传一张照片到QQ空间相册",
    	"list_album"=>"获取用户QQ空间相册列表",
    	"add_share"=>"同步分享到QQ空间、朋友网、腾讯微博",
    	"check_page_fans"=>"验证是否认证空间粉丝",
    	"add_t"=>"发表一条微博信息到腾讯微博",
    	"add_pic_t"=>"上传图片并发表消息到腾讯微博",
    	"del_t"=>"删除一条微博信息",
    	"get_repost_list"=>"获取一条微博的转播或评论信息列表",
    	"get_info"=>"获取登录用户自己的详细信息",
    	"get_other_info"=>"获取其他用户的详细信息",
    	"get_fanslist"=>"获取登录用户的听众列表",
    	"get_idollist"=>"获取登录用户的收听列表",
    	"add_idol"=>"收听腾讯微博上的用户",
    	"del_idol"=>"取消收听腾讯微博上的用户",
    	"get_tenpay_addr"=>"获取用户在财付通的收货地址"
    );
    if(isset($_POST)&&!empty($_POST)){
        if($_POST["session"]==0){
            unset($_POST["db"]);
            unset($_POST["mem"]);
        }elseif($_POST["session"]==1){
            mysql_connect(SAE_MYSQL_HOST_S.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER ,SAE_MYSQL_PASS) or die("<script>alert('连接数据库失败，请重新配置。');location.href='index.php';</script>");
            mysql_select_db(SAE_MYSQL_DB) or die("<script>alert('数据库不存在,请重新配置。');location.href='index.php';</script>");
            //执行建表任务
            mysql_query("SET NAMES UTF8;");
            mysql_query("CREATE TABLE if not exists `sessions` (`sessionkey` varchar(32) NOT NULL,`sessionvalue` text NOT NULL,`sessionexpiry` datetime NOT NULL,`sessionip` varchar(15) DEFAULT NULL,PRIMARY KEY (`sessionkey`)) DEFAULT CHARSET=utf8;");
            mysql_close();
            unset($_POST["mem"]);
        }elseif($_POST["session"]==2){
            if(!$MemType){
                die("<script>alert('系统不支持MemCache,请重新配置.');location.href='./index.php';</script>");
            }
            @memcache_connect($_POST["mem"]["host"],$_POST["mem"]["port"]) or die("<script>alert('连接MemCache失败。请重新配置。');location.href='./index.php';</script>");
            unset($_POST["db"]);
        }
        $sContent = "<?php\r\n\$aConfig = ".var_export($_POST,true).";";
        print_r($sContent);
        file_put_contents("../common/config.php", $sContent);
        echo "<script>alert('设置成功。');location.href='../index.php';</script>";
    }else{
?>
<html>
<head>
<link href="../style/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script>
$("document").ready(function(){
    $("#sessiontype_0").click(function(){
        $("#session_mysql_type").hide();<?php if($MemType>0){?>$("#session_memcache_type").hide();<?php }?>
    });
    $("#sessiontype_1").click(function(){
        $("#session_mysql_type").show();<?php if($MemType>0){?>$("#session_memcache_type").hide();<?php }?>
    });
<?php if($MemType>0){?>
    $("#sessiontype_2").click(function(){
        $("#session_mysql_type").hide();
        $("#session_memcache_type").show();
    });
<?php }?>
});
</script>
</head>
<body>
<h1>QQ互联集成PHP SDK - 系统配置</h1>
<div class="list-div">
<form id="ConfigForm" action="<?php echo $_SERVER["PHP_SELF"];?>" method="POST">
<table>
  <tr>
    <th colspan="2">QQ互联基本参数</th>
  </tr>
  <tr>
    <td class="narrow-label">APPID:</td>
    <td><input name='appid' class="input" /></td>
  </tr>
  <tr>
    <td class="narrow-label">APPKEY:</td>
    <td><input name='appkey' class="input" /></td>
  </tr>
  <tr>
    <th colspan="2">QQ互联配置参数</th>
  </tr>
  <tr>
    <td class="narrow-label">开放API:</td>
    <td><?php
    foreach($aQQApi as $key=>$val){
        ?><label for="API_<?php echo $key;?>" title="<?php echo $val;?>" style="width:200px; float:left;"><input type='checkbox' name='api[<?php echo $key;?>]' id="API_<?php echo $key;?>" value="1" /><?php echo $key;?></label><?php
    }
    ?></td>
  </tr>
  <tr>
    <th colspan="2">QQ互联其他参数</th>
  </tr>
  <tr>
    <td class="narrow-label">SESSION:</td>
    <td>
      <label for='sessiontype_0'><input type='radio' name='session' id='sessiontype_0' value='0' checked>普通方式</label>
      <label for='sessiontype_1'><input type='radio' name='session' id='sessiontype_1' value='1'>数据库</label>
      <?php if($MemType>0){?><label for='sessiontype_2'><input type='radio' name='session' id='sessiontype_2' value='2'>MemCache</label><?php }?>
    </td>
  </tr>
  <tr id="session_mysql_type" style="display:none;">
    <td class="narrow-label">数据库配置:</td>
    <td>
        &nbsp;数据库地址：<input type="input" name="db[host]" class="input" value="localhost" /><br />
        &nbsp;数据库帐号：<input type="input" name="db[user]" class="input" value="root" /><br />
        &nbsp;数据库密码：<input type="input" name="db[pass]" class="input" value="" /><br />
        &nbsp;数据库名称：<input type="input" name="db[name]" class="input" value="" />
    </td>
  </tr><?php if($MemType>0){?>
  <tr id="session_memcache_type" style="display:none;">
    <td class="narrow-label">MemCache配置:</td>
    <td>
        &nbsp;MemCache地址：<input type="input" name="mem[host]" class="input" value="127.0.0.1" /><br />
        &nbsp;MemCache端口：<input type="input" name="mem[port]" class="input" value="11211" />
    </td>
  </tr><?php }?>
  <tr>
    <td class="narrow-label">调试模式:</td>
	<td><label for='debug_0'><input type='radio' name='debug' id='debug_0' value='0' checked>关闭</label>
      <label for='debug_1'><input type='radio' name='debug' id='debug_1' value='1'>开启</label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
	<td><input type='submit' id="submit" value='生成配置' class="button" /><span id="submitResult"></span></td>
  </tr>
</table>
</form>
</div>
</body>
</html>
<?php
    }
?>