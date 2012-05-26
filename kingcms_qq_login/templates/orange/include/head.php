<?php !defined('INC') && exit('Load failure!'); ?>
<?php
//调用广告
$cachepath='ads';
$cache=new cache;
$AD=$cache->get($cachepath);
if(empty($AD)){
	$res=$db->getRows('%s_ad','id,type,alt,url,src',"statu=1 and online=1",'id desc');
	$AD=array();
	foreach ($res as $rs) {
		$AD[$rs['type']]=$rs;
	}
	$cache->set($cachepath,$AD,'3600');
	unset($res,$rs);
}
$str=new str;
?>
<div id="top">
	<div class="frame">
		<div class="left">
			<?php if($islogin): ?>
			<div id="login_div">
				<a href="<?=UC.DIR?>"><img alt="<?=kc_config('site.name')?>" src="<?=DIR?>images/mlogo.gif"/></a>
				您好,<strong><?=$username?></strong>
				<a href="javascript:;" onclick="$.kc_ajax({URL:'<?=DIR?>user/manage.php',CMD:'logout'})" title="退出登录">退出</a>
				<?php $count_pm=$db->getCount('%s_pm','userid='.$userid.' and islook=0'); ?>
				<a href="<?=DIR?>user/pm.php" title="短信息">站内信<?=empty($count_pm)?'':'(<em>'.$count_pm.'</em>)'?></a>
				<span>
					人气:<strong><?=$renqi?></strong>
					积分:<strong><?=$jifen?></strong>
					金币:<strong><?=$jinbi?></strong>
				</span>
			</div>
			<?php else: ?>
			<form action="" method="post" id="login">
				<a href="<?=UC.DIR?>"><img alt="<?=kc_config('site.name')?>" src="<?=DIR?>images/mlogo.gif"/></a>
				<label>用户名:</label>
				<input type="text" id="username" name="username" class="w80" size="5" maxlength="12"/>
				<label>密码:</label>
				<input type="password" id="userpass" name="userpass" class="w80" size="5" maxlength="30"/>
				<input type="submit" class="submit_login" value="登录" onclick="$.kc_ajax({URL:'<?=DIR?>user/manage.php',CMD:'login',FORM:'login',METHOD:'POST'});return false;" />
				<input type="submit" class="submit_login" value="注册" onclick="$.kc_ajax({URL:'<?=DIR?>user/manage.php',CMD:'register',FORM:'login'});return false;" />
			<a href="qq/callback.php?qq=login"><img src="qq/63X24.png" border='0' alt='用ＱＱ账号登录' title='用ＱＱ账号登录' /></a>
</form>
			<?php endif ?>
		</div>
		<div class="right">
			<div id="top_menu">
			<?php if($islogin):?>
				<?php if($ismanage):
				$style='';
				$array=array(
					'fangwu','jiaoyi','zhaopin','jianli','jiaoyou'
					,'cuxiao','ado','fuwu','link','feedback','reply','vip'
				);
				foreach($array as $r){
					if($db->getCount('%s_'.$r,'statu=0')>0){
						$style='font-weight:bold;color:#F30;';
						continue;
					}
				}
				?>
				<a href="<?=sign_encode(array('MOD'=>'index'))?>" title="进入到管理页面" style="<?=$style?>">进入管理</a>
				<?php if($ismanage==1):?>
				<a href="javascript:;" title="进入到管理页面" onclick="$.kc_ajax({URL:'<?=DIR?>user/manage.php',CMD:'config'})">参数设置</a>
				<?php endif; ?>
				<?php endif; ?>

				<span class="top_hover">
					<a href="javascript:;" class="sub">我的信息</a>
					<ul>
						<li><a href="<?=DIR?>fangwu/my.php">房产信息</a></li>
						<li><a href="<?=DIR?>zhaopin/my.php">企业招聘</a></li>
						<li><a href="<?=DIR?>jianli/my.php">人才简历</a></li>
						<li><a href="<?=DIR?>jiaoyi/my.php">供求信息</a></li>
						<li><a href="<?=DIR?>jiaoyou/my.php">交友信息</a></li>
						<li><a href="<?=DIR?>cuxiao/my.php">促销信息</a></li>
						<!--li><a href="<?=DIR?>warehouse/my.php">订单信息</a></li-->
						<li><a href="<?=DIR.'u'.$userid?>/">我的话题</a></li>
					</ul>
				</span>

				<a href="/user/avatar_edit.php" title="修改头像">编辑头像</a>
				<a href="/user/edit.php" title="修改我的资料">我的资料</a>
				<a href="javascript:;" onclick="$.kc_ajax({URL:'<?=DIR?>user/manage.php',CMD:'resetpass'})" title="修改密码">修改密码</a>
			<?php else:?>
				<a href="javascript:;" onclick="this.style.behavior='url(#default#homepage)';this.setHomePage('<?=FULLURL?>');">设为首页</a>
				<a href="javascript:;" onclick="window.external.addFavorite('<?=FULLURL?>','<?=kc_config('site.name')?>')" >加入收藏</a>
			<?php endif?>
				<span class="top_hover top_sitemaps">
					<a href="/sitemaps/" class="sub">网站导航</a>
					<ul>
						<li><a href="/">网站首页</a></li>
						<li><a href="<?=DIR?>tianqi/">天气预报</a></li>
						<li><a href="<?=DIR?>prize/">中奖记录</a></li>
						<li><a href="<?=DIR?>prize/exchange.php">兑换奖品</a></li>

					</ul>
				</span>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('.top_hover').hover(function(){$(this).find('ul').show();},
	function(){$(this).find('ul').hide();});
	$('#top_menu').width(80*$('#top_menu span').length+70*$('#top_menu>a').length);
</script>