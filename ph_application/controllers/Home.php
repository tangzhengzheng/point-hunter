<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * 主页面控制器
 *
 */
class Home extends PH_Controller
{
	//首页
	public function index()
	{
		$data = array();
		$data['uid'] = $this->userId;
		$this->showView('home', $data);
	}
	
	//创建
	public function create()
	{
		$data = array();
		$data['uid'] = $this->userId;
		if($this->userId == ''){
			redirect(site_url('home/index'));
		}
		$this->showView('create', $data);
	}
	
	//登录
	public function login()
	{
		$data = array();
		$data['uid'] = $this->userId;
		$this->showView('login', $data);
	}
	
	public function doLogin()
	{
		if($this->input->post('uid')){
			$uid = (int)$this->input->post('uid');
			$this->load->library('session');
			$this->session->set_userdata('uid', $uid);
		}
		redirect(site_url('home/index'));
	}
	
	public function doLogout()
	{
		$this->load->library('session');
		$this->session->sess_destroy();
		redirect(site_url('home/index'));
	}
}