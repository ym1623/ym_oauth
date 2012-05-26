<?php
include 'config.php';
$exec = "ALTER TABLE `king_user` ADD `openid` CHAR(40) NOT NULL AFTER `userid`";
$result = mysql_query($exec);
if($result)
{echo "插入openid字段成功";mysql_error();}
else
{echo "插入openid字段失败";mysql_error();}