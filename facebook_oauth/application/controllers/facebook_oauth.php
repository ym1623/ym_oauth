<?php
class Oauth extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

		$this->load->config('facebook');

		$facebook_config = array(
			'appId' 	=> config_item('facebook_app_id'),
			'secret'	=> config_item('facebook_secret_key'),
			'callback_url'	=> base_url(),
		);
			
		$this->load->library('facebook_oauth', $facebook_config);

	}
	
	function index()
	{
		// If Returning from Facebook with "code" in query string
		if (isset($_GET['code']))
		{
			$this->data['result'] = $this->facebook_oauth->getAccessToken();
		}
		else
		{
			$auth_url = $this->facebook_oauth->getAuthorizeUrl();

			$this->data['result'] =  '<a href="'.$auth_url.'">'.$auth_url.'</a>';	
		}
		
		$this->load->view('facebook_oauth', $this->data);
	
	}

	
}