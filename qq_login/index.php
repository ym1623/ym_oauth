<?php
header("Content-type:text/html;charset=UTF-8;");
if(!file_exists('common/config.php')){
    header("Location:install/index.php");
    exit;
}
include_once('common/function.php');
?>
<html>
<head>
<link href="style/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
</head>
<body>
<h1>QQ互联集成PHP SDK - 首页</h1>
<div class="list-div">
<table>
<?php if(!isset($_SESSION["openid"])||empty($_SESSION["openid"])){?>
<tr>
  <td>点击此处进行QQ登录：<a href="oauth/login.php"><img src="images/qqlogin.png" border="0" /></a></td>
</tr>
<?php 
}else{
?>
<tr>
  <td>QQ用户已经登录,<a href="oauth/logout.php">点此退出</a></td>
</tr>
<?php 
}
if($aConfig["api"]["get_user_info"]==1){
?>
<tr>
  <td><a href="user/get_user_info.php">获取用户在QQ空间的个人资料[OK]</a></td>
</tr>
<?php
}
if($aConfig["api"]["add_topic"]==1){
?>
<tr>
  <td><a href="shuoshuo/add_topic.php">发表一条说说到QQ空间[OK]</a></td>
</tr>
<?php
}
if($aConfig["api"]["add_one_blog"]==1){
?>
<tr>
  <td><a href="blog/add_one_blog.php">发表一篇日志到QQ空间[OK]</a></td>
</tr>
<?php
}
if($aConfig["api"]["add_album"]==1){
?>
<tr>
  <td><a href="photo/add_album.php">创建一个QQ空间相册[OK]</a></td>
</tr>
<?php
}
if($aConfig["api"]["upload_pic"]==1){
?>
<tr>
  <td><a href="photo/upload_pic.php">上传一张照片到QQ空间相册[OK]</a></td>
</tr>
<tr>
  <td><a href="photo/upload_pic_2.php">上传多张照片到QQ空间相册[OK]</a></td>
</tr>
<?php
}
if($aConfig["api"]["list_album"]==1){
?>
<tr>
  <td><a href="photo/list_album.php">获取用户QQ空间相册列表[OK]</a></td>
</tr>
<?php
}
if($aConfig["api"]["add_share"]==1){
?>
<tr>
  <td><a href="share/add_share.php">同步分享到QQ空间、朋友网、腾讯微博[OK]</a></td>
</tr>
<?php
}
if($aConfig["api"]["check_page_fans"]==1){
?>
<tr>
  <td><a href="user/check_page_fans.php">验证是否认证空间粉丝[OK]</a></td>
</tr>
<?php
}
if($aConfig["api"]["add_t"]==1){
?>
<tr>
  <td><a href='t/add_t.php'>发表一条微博信息到腾讯微博[OK]</a>&nbsp;&nbsp;<?php if($aConfig["api"]["del_t"]==1){
?>删除微博<?php
}?></td>
</tr>
<?php
}
if($aConfig["api"]["add_pic_t"]==1){
?>
<tr>
  <td><a href="t/add_pic_t.php">发表一条图片微博到腾讯微博[OK]</a>&nbsp;&nbsp;<?php if($aConfig["api"]["del_t"]==1){
?>删除微博<?php
}?></td>
</tr>
<tr>
  <td><a href="t/add_pic_t_by_url.php">发表一条外网图片微博到腾讯微博[OK]</a>&nbsp;&nbsp;<?php if($aConfig["api"]["del_t"]==1){
?>删除微博<?php
}?></td>
</tr>
<?php
}
if($aConfig["api"]["get_info"]==1){
?>
<tr>
  <td><a href="user/get_info.php">获取登录用户自己的详细信息[OK]</a></td>
</tr>
<?php
}

if($aConfig["api"]["get_fanslist"]==1){
?>
<tr>
  <td><a href="relation/get_fanslist.php">获取登录用户的听众列表[OK]</a>&nbsp;&nbsp;<?php if($aConfig["api"]["get_other_info"]==1){
?>获取其他用户的详细信息<?php
}?></td>
</tr>
<?
}
if($aConfig["api"]["get_idollist"]==1){
?>
<tr>
  <td><a href="relation/get_idollist.php">获取登录用户的收听列表[OK]</a>&nbsp;&nbsp;<?php if($aConfig["api"]["get_other_info"]==1){
?>获取其他用户的详细信息<?php
}?></td>
</tr>
<?php
}
if($aConfig["api"]["add_idol"]==1){
?>
<tr>
  <td><a href="relation/add_idol.php">收听腾讯微博上的用户[OK]</a></td>
</tr>
<?php 
}
if($aConfig["api"]["del_idol"]==1){
?>
<tr>
  <td><a href="relation/del_idol.php">取消收听腾讯微博上的用户[OK]</a></td>
</tr>
<?php
}
if($aConfig["api"]["get_tenpay_addr"]==1){
?>
<tr>
  <td><a href="cft_info/get_tenpay_addr.php">获取用户在财付通的收货地址[OK]</a></td>
</tr>
<?php 
}
?>
</table>
</div>
</body>
</html>
