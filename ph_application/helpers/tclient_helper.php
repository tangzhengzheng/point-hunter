<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * 各种Thirft接口调用层
 *
 */

if( ! class_exists('ThriftBase')){
	include_once LIB_DIR.'class/thrift.class.php';
}

class ThriftFactory
{
	private static $instances = array();
	
	/**
	 * 客户端类型     值为引入文件的前缀       <type>.thrift.php   书签同步和标签同步除外
	 */
	const TYPE_PH = 'pointHunter';		//点子猎手
	
	public static function getInstance($type)
	{
		if( ! isset(self::$instances[$type])){
			switch($type){
				case self::TYPE_PH:
					if( ! class_exists('PointHunter')){
						include_once LIB_DIR.'class/'.$type.'.thrift.php';
					}
					self::$instances[$type] = new PointHunter();
					break;
				default:
					throw new Exception('Does not privode such type of client.');
			}
		}
		return self::$instances[$type];
	}
	
	/**
	 * 销毁实例
	 * @param unknown $type
	 */
	public static function destroy($type)
	{
		if(isset(self::$instances[$type])){
			unset(self::$instances[$type]);
		}
	}
}