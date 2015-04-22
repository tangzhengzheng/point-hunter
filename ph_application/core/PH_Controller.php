<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * 重写CI_Controller
 * 初始化，判断模板等逻辑
 *
 */
class PH_Controller extends CI_Controller
{
	const VIEW_THEME_PHONE = 'phone';     // 手机模板目录
	const VIEW_THEME_PC	   = 'pc';  	  // pc模板目录
	const EXT              = VIEW_EXT;    // 模板后缀名     
	
	protected $theme;                     // 模板类别
	
	/**
	 * Override this behavior
	 * 控制器执行前函数
	 *
	 * @param string $method
	 */
	public function _remap($method, $params = array())
	{
		declare(encoding='UTF-8');
		mb_internal_encoding("UTF-8");
		
		if(method_exists($this, $method)){
			$this->initialize();
			return call_user_func_array(array($this, $method), $params);
		}else{
			show_404();
		}
	}
	
	/**
	 * 初始化各种库
	 */
	protected function initialize()
	{
		//屏蔽缓存
		header('Content-Type: text/html; charset=utf-8');
		header('Expires: 0');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', FALSE);
		header('Pragma: no-cache');
		
		$this->load->library(array('Smarty_ext', 'Mobile_detect'));
		$this->load->helper('language');
		$this->theme = $this->mobile_detect->isMobile() ? self::VIEW_THEME_PHONE : self::VIEW_THEME_PC;
		$this->config->set_item('language', 'ch');
		$this->lang->load('ch');
	}
	
	/**
	 * Render view page
	 *
	 * @param string $template
	 * @param array $data
	 */
	protected function showView($template, $data = array())
	{
		$path = VIEW_DIR.$this->theme.'/'.$template.'.'.self::EXT;
		if(@file_exists($path)){
			$this->smarty_ext->view($path, $this->theme, $data);
		}else{
			log_message('error', 'can not find template file['.$path.']');
			show_error('can not find special template file');
		}
	}
}