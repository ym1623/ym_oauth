<?php
session_start();
include_once( 'config.php' );
include_once( 'weibooauth.php' );

$c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']  );

//获得用户信息
$userid= $_SESSION['last_key']['user_id'];
$arr = $c->show_user($userid);
 $username=$arr['name']; //用户名
 $usertou =$arr['profile_image_url']; //用户头像地址

//=============
$im = imagecreatefromjpeg("by.jpg");
$tou = imagecreatefromjpeg($usertou);
$color=imagecolorallocate($im,0,0,255);
imagettftext($im,12,0,270,122,$color,"simhei.ttf",$username);
imagettftext($im,10,0,90,220,$color,"simhei.ttf","NO.".time());
imagecopy($im,$tou,121,131,0,0,50,50);
imagejpeg($im,$userid.".jpg");
//=============
$t="999999999999 http://www.php100.com";
$p="http://localhost/sina12/".$userid.".jpg";

  if(!empty($_POST['sub'])){
     //$t 文字内容
	 //$p 我们上传的图片路径
	 
     $c ->upload( $t, $p );
  }
  
 
  
  


?>
<img src="<?php echo $userid.".jpg"; ?>">
<form action="" method="post">
<input type="submit" name="sub" value="发到微博">
</form>



