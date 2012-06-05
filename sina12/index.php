<?php

session_start();
//if( isset($_SESSION['last_key']) ) header("Location: weibolist.php");
include_once( 'config.php' );
include_once( 'weibooauth.php' );



$o = new WeiboOAuth( WB_AKEY , WB_SKEY  );

$keys = $o->getRequestToken();



if( strpos( "http://localhost/sina12/" , 'index.php' ) === false )
	$callback =  'http://localhost/sina12/callback.php';
else	
	$callback =  str_replace( 'index.php' , 'callback.php' , $_SERVER['SCRIPT_URI'] );


$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $callback );

$_SESSION['keys'] = $keys;


?>
<a href="<?php echo $aurl?>">Use Oauth to login</a>




