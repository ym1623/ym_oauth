kingcms qq登录 by helong

2012年5月13日22:11:45
----------------

这是为qq群里一朋友写的kingcms qq登录插件,完成的很匆忙,如有错误,请见谅,官方版本也该很快出来,

http://t.qq.com/tohelong

tohelong@gmail.com

http://helong.org
----------------

使用前,请先备份你的文件,出了问题没备份就后悔莫及....

第一步  配置qq/config.php 文件 ,填写好你的appid,appkey 申请地址:http://opensns.qq.com

        填写好你的数据库 地址 用户名 密码 数据表名等信息

第二步  如果你用官方的css文件,上传html_public里的文件覆盖上传到空间
         
        如果你修该过css ,请参考下面说明


第三步  运行http//域名/qq/sql.php ,显示插入openid字段成功,如不成功,请用phpmyadmin等方式在在数据库表king_user字段中插入openid字段,类型为char(40) 


----------------

第二步中,你也可以手动修改信息,如下:

templates/orange/include/head.php

43行  <a href="qq/callback.php?qq=login"><img src="qq/63X24.png" border='0' alt='用ＱＱ账号登录' title='用ＱＱ账号登录' /></a>

templates/orange/images/style.css

204行  #top .left{width:500px;text-align:left;line-height:25px;}
219行  #top .right{text-align:right;width:470px;}

library/user.class.php

42行			$user=array('userpass'=>'x','openid'=>'xx');
45行		if(empty($user))  $user=array('userpass'=>'x','openid'=>'xx');
49行	  if (md5($user['userpass'])==$cookiePass || $ischeck==false || md5($user['openid'])==$cookiePass) {


在数据库表king_user字段中插入openid字段,类型为char(40) 
$sql = "ALTER TABLE 'king_user' ADD 'openid' CHAR(40) NOT NULL AFTER 'userid'";


--------------
为防止重名,在账号后面添加了一个rand值,可以自行修改
