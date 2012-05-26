<?php
/**
 * 用户访问控制
 */
class user{

	/**
	 * @var array 用户信息
	 */
	public $info;

	public function __construct(){

		$cookie=kc_cookie('userauth');
		$cookiePass=substr($cookie,0,32);

		$ischeck=true;//是否审核cookie

		$GLOBALS['db']=new db;
		global $db;
		
		if(empty($cookie) && !empty($_GET['jsoncallback'])
			&& !empty($_GET['USERID']) && !empty($_GET['SIGN'])){
			
			$get_userid=$_GET['USERID'];
			$get_sign=$_GET['SIGN'];

			
			$sign=md5($get_userid.SITEURL.kc_config('system.salt'));
			$userid = $sign==$get_sign ? $get_userid : 0;

			$ischeck=false;
			//$userid=$get['USERID'];
		}else{
			$userid=substr($cookie,32);
		}


		if(!kc_validate($userid,2)) $userid=0;

		if(empty($userid)){
		$user=array('userpass'=>'x','openid'=>'xx');
		}else{
			$user=$db->getRows_one('%s_user','*','userid='.$userid);
		if(empty($user))  $user=array('userpass'=>'x','openid'=>'xx');
		}
		
		//用户已登录
	if (md5($user['userpass'])==$cookiePass || $ischeck==false || md5($user['openid'])==$cookiePass) {
			//更新在线时间
			$zx=time()-$user['datezx'];
			if($zx<300){
				$array=array(
					'[zaixian]'=>'zaixian+'.$zx,
					'datezx'=>time()
				);
			}else{
				$array=array('datezx'=>time());
			}
			$db->update('%s_user',$array,'userid='.$userid);
			
			unset($user['userpass']);
			$user['islogin']=true;
		}else{
			$user=array(
				'ismanage'=>0,
				'userid'=>0,
				'username'=>'[匿名]',
				'islogin'=>false,

				'name'=>'',
				'tel'=>'',
				'email'=>'',
				'msn'=>'',
				'qq'=>'',
				'userstatu'=>false,
			);
		}

		$this->info=$user;
		unset($user);
		return $this->info;
	}

	public function __destruct(){
		flush();
		if(!is_dir(ROOT.'task')) return false;
		if(AJAX) return false;//ajax操作的时候不做计划任务
		if(defined('CLOSETASK')) return false;

		extract($this->info);
		/** 分时计划任务 **/
		$file=new file;
		$db=new db;
		$str=new str;
		$time=time();
		$today=$str->formatDate($time,'Ymd');
		
		for($i_task=1;$i_task<=6;$i_task++){
			if ($time-intval(kc_config('task.update'.$i_task))>(3600*$i_task)){
				$tasks=$file->getDir('task/'.$i_task.'/','php');
				if(!empty($tasks)){
					foreach ($tasks as $k =>$v) {
						require ROOT.$k;
					}
				}
				unset($tasks);
				$db->update('%s_config',array('value'=>$time),"class='task' and name='update{$i_task}'");
			}
		}
		//开始执行每日计划任务
		if(kc_config('task.day')!=$today){
			$tasks=$file->getDir('task/day/','php');
			if(!empty($tasks)){
				foreach($tasks as $k => $v){
					require ROOT.$k;
				}
			}
			unset($tasks);
			$db->update('%s_config',array('value'=>$today),"class='task' and name='day'");
		}
		//刷新即可执行
		$tasks=$file->getDir('task/0/','php');
		if(!empty($tasks)){
			foreach($tasks as $k=>$v){
				require ROOT.$k;
			}
		}
	}

}

function user_level_number($t){
	$hour=$t/3600;
	$level= $hour>=1 ? ceil(sqrt($hour))+1 :1;
	return $level;
}
function user_level($t){
	$hour=$t/3600;
	$level= $hour>=1 ? ceil(sqrt($hour)) :1;

	$arr=array();
	$arr[1]=floor($level/16);
	$arr[2]=floor(($level-$arr[1]*16)/4);
	$arr[3]=$level-$arr[1]*16-$arr[2]*4;
	$s='<em class="level" title="Level: '.$level.'">';
	foreach($arr as $k=>$r){
		for($i=1;$i<=$r;$i++){
			$s.='<img src="'.DIR.'images/rank/r'.$k.'.gif" alt=""/>';
		}
	}
	$s.='</em>';
	return $s;
}

?>