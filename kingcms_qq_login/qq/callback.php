<?php
session_start();
///麻花藤开始
//设置include_path 到 OpenSDK目录
set_include_path(dirname(__FILE__).'/lib/');
require_once 'OpenSDK/Tencent/SNS2.php';
//require_once 'OpenSDK/Tencent/Weibo.php';
include 'config.php';
OpenSDK_Tencent_SNS2::init($appid, $appkey);
////
if(isset($_GET['qq']))  //登录扣扣
{
    $callback ='http://'.$_SERVER['HTTP_HOST'].'/qq/callback.php';//回调地址
    $url = OpenSDK_Tencent_SNS2::getAuthorizeURL($callback, 'code', 'state','default','get_user_info');//用户授权的权限
    header('Location: '. $url);
}
///////////////code表示qq callback回来了
 if( isset($_GET['code']))
{     
if($k=OpenSDK_Tencent_SNS2::getAccessToken('code',array('code'=>$_GET['code'],'redirect_uri'=>'http://'.$_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'])))
      {   //////////////////处理开始
$url="https://graph.qq.com/oauth2.0/me?access_token=".$k["access_token"];
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  
curl_setopt($ch, CURLOPT_URL, $url);
$mo =  curl_exec($ch);
curl_close($ch);
$json = substr($mo,10,-3);
$user = json_decode($json);
$openid=$user->openid;
require '../global.php';
//获取用户资料.
$uinfo = OpenSDK_Tencent_SNS2::call('user/get_user_info',array());
$nick=$uinfo['nickname'].'_'.rand(10000,99999);//QQ昵称
$photo=$uinfo['figureurl_1'];//QQ头像
//$sex=$uinfo['gender'];//性别
//$tqq=OpenSDK_Tencent_SNS2::call('user/get_info',array(),'GET');
//$tqqemail=$tqq['data']['email'];
//$emailarr = explode("@",$tqqemail);
//$tqqunm=$emailarr[0];//获取QQ号码,貌似没权限获取
//$openid=$tqq['data']['openid'];//qq用户唯一识别码openid
//$tqqname=$tqq['data']['name'].'_'.rand(10000,99999);//qq微博名称,英文或数字,为防止重名,
//var_dump($tqq);
//echo "<br>昵称是<br>".$nick;
//echo "<br>图像是<br>".$photo;
//echo "<br>openid是<br>".$openid;
//echo "<br>用户名是<br>".$tqqname;

$exec = "SELECT openid,userid FROM king_user WHERE openid='$openid'";
$result = mysql_query($exec);
$rows = mysql_num_rows($result); //这边是检测用户是否存在

if($rows>0 && $openid<>'')
   {
    $id=mysql_fetch_array($result);
		kc_setCookie('userauth',md5($openid).$id['userid'],8640000);
	$referer=empty($_POST['HTTP_REFERER'])?'/':$_POST['HTTP_REFERER'];
	header("location:$referer");
    //echo '当前id:'.$id['userid'].'<br>cookie是<br>'.$_COOKIE['userauth'];
    }
else if($openid<>'')
{
  $exec = "INSERT INTO king_user(openid,username,name)"."VALUES('$openid','$nick','$nick')";
  $result = mysql_query($exec);
  $id = mysql_insert_id();  //读取用户ID
  if($id>0)
    {
   // echo "新用户注册成功:".$nick."<p>id是".$id;
		kc_setCookie('userauth',md5($openid).$id,8640000);
	$referer=empty($_POST['HTTP_REFERER'])?'/':$_POST['HTTP_REFERER'];
		header("location:$referer");
   //echo '<br>cookie是<br>'.$_COOKIE['userauth'];
    }
  else
     {echo "用户【".$nick."】注册失败!";}
}
       
      }    //////////////////处理结束
//从Callback返回空时
else
    {echo '登录ＱＱ失败';}
}
////麻花藤结束