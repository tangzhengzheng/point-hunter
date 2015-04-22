<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('LOG4PHP_VER', '2.1.0');
define('LOG4PHP_PATH', THIRD_PATH.'Log4php-'.LOG4PHP_VER.'/');

/**
 * @codeCoverageIgnore
 *
 * 重写系统Log类的write_log方法
 * 使用Log4php框架，分进程打印日志
 *
 */
class PH_Log extends CI_Log 
{
	protected $_loggers = array();
	
	/**
	 * 初始化Log4php
	 */
	public function __construct()
	{
		require_once LOG4PHP_PATH.'Logger.php';
		Logger::configure(APPPATH.'config/log_dailyfile.xml');
	}

	// --------------------------------------------------------------------

	/**
	 * Write Log File
	 *
	 * Generally this function will be called using the global log_message() function
	 *
	 * @param	string	the error level
	 * @param	string	the error message
	 * @param	bool	whether the error is a native PHP error
	 * @return	bool
	 */
	public function write_log($level = 'error', $msg, $php_error = FALSE)
	{
		if(strlen($msg) == 0) {
			return FALSE;
		}

		$level = strtoupper($level);
		$logger_name = array('ERROR' => 'err', 'TRACE' => 'inf');

		if(!isset($this->_loggers[$level])){
			$this->_loggers[$level] = @Logger::getLogger($logger_name[$level]);
		}

		switch($level){
			case 'ERROR':
				$this->_loggers[$level]->error($msg);
				break;
			case 'TRACE':
				$this->_loggers[$level]->info($msg);
				break;
		}
		
		return TRUE;
	}
}
/* End of file ND_Log.php */
/* Location: ./libraries/ND_Log.php */