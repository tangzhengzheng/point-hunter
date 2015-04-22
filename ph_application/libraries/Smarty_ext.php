<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('SMARTY_VER', '3.1.11');
define('SMARTY_DIR', THIRD_PATH.'Smarty-'.SMARTY_VER.'/');
require SMARTY_DIR.'Smarty.class.php';

/**
 *
 * Smarty模板类，替代ci自身的模板功能
 *
 */
class Smarty_ext extends Smarty
{
	/**
	 * 为模板文件添加strip过滤模版空格，回车
	 *
	 * @param string $source
	 * @param object $smarty
	 * @return string
	 */
	public function addStrip($source, $smarty){
		return '{strip}'.$source.'{/strip}';
	}
	
	/**
	 * __construct
	 * smarty的参数初始化
	 */
	public function __construct()
	{
		parent::__construct();
		if(!file_exists(VIEW_DIR.'cache')){
			mkdir(VIEW_DIR.'cache', DIR_WRITE_MODE);
		}

		$this->template_dir = VIEW_DIR;
		$this->compile_dir = VIEW_DIR.'templates_c/';
		$this->config_dir = SMARTY_DIR.'configs/';
		$this->cache_dir = VIEW_DIR.'cache/';
		if(DEBUG_MODE){
			$this->force_compile = TRUE;
		}else{
			$this->compile_check = FALSE;
		}
		$this->debugging = FALSE;
		$this->caching = FALSE;
		$this->cache_lifetime = 6000;
	}
	
	/**
	 * 渲染模板核心逻辑
	 *
	 * @param string $template 模板路径
	 * @param string $layout 布局名称
	 * @param array $data 模板数据
	 */
	private function _renderLayout($template, $theme, &$data = array())
	{
		/*
		 * ----------------------------------
		 * 设置js,css,img等的路径变量
		 * ----------------------------------
		 */
		$this->assign('JS_PATH', 'public/'.$theme.'/js/');
		$this->assign('CSS_PATH', 'public/'.$theme.'/css/');
		$this->assign('IMG_PATH', 'public/'.$theme.'/images/');
		$this->assign('COMMON_JS_PATH', 'public/common/js/');
		$this->assign('COMMON_CSS_PATH', 'public/common/css/');
		$this->assign('COMMON_IMG_PATH', 'public/common/images/');

		log_message('trace', '>>> start to render view');

		$this->registerFilter('pre', array($this, 'addStrip'));
		$this->loadFilter('output', 'trimwhitespace'); // 去掉空格

		$this->display($template);
	}	
	
	/**
	 * 渲染模板
	 *
	 * @param string $template 模板路径
	 * @param string $theme 平台(phone/pc)
	 * @param array $data 模板数据
	 * 
	 */
	public function view($template, $theme, &$data='')
	{
		if(is_array($data)){
			foreach($data as $key=>$val){
				$this->assign($key, $val, TRUE);
			}
		}
		$this->_renderLayout($template, $theme, $data);
	}
	
}
/* End of file Smarty_ext.php */
/* Location: ./application/libraries/Smarty_ext.php */