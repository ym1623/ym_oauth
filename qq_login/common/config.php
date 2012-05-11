<?php
	$aConfig = array(
		'appid'  => '', 
		'appkey' => '', 
		'api' => array ( 
			'get_user_info' => '1', 
			'add_topic' => '1', 
			'list_album' => '1', 
			'add_share' => '1', 
			'del_t' => '1', 
			'get_repost_list' => '1', 
			'get_idollist' => '1', 
			'add_idol' => '1', 
		), 
		'session' => '1', 
		'db' => array ( 
			'host' => SAE_MYSQL_HOST_S.':'.SAE_MYSQL_PORT, 
			'user' => SAE_MYSQL_USER, 
			'pass' => SAE_MYSQL_PASS, 
			'name' => SAE_MYSQL_DB, 
		),  'debug' => '0');