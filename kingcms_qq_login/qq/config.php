<?php
//$appid = '100266408';
$appid = '100270662';//填写appid
//$appkey = '682afb633bec5b7322f1c507e15af64b';
$appkey = 'c18cbb62664a5900cd4f2e3c8c5334bf';//填写appkey

$db_host='localhost';  //数据库地址
$db_user='root';//数据库用户名
$db_pass='root';//数据库密码
$db_name='hunchun123'; //数据库名称

$link=mysql_connect($db_host,$db_user,$db_pass);//连接数据库
if(!$link){echo "数据库连接错误！";mysql_error();}
mysql_select_db($db_name,$link);//选择数据库
mysql_query("SET NAMES UTF8");