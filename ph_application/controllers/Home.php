<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * 主页面控制器
 *
 */
class Home extends PH_Controller
{
	public function index()
	{
		$data = array();
		$this->showView('home', $data);
	}
}