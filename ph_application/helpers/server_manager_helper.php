<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 错误码
 *
 */
class ThriftCode
{
	const ERR_INTERNAL 		= 10500; 	//内部错误
	const ERR_CONFIG 		= 10501;	//配置错误
	const ERR_UNKNOWN_TYPE 	= 10502;	//未知类型
	const ERR_WRONG_PARAM 	= 10503;	//参数错误
}

/**
 * 
 * Exception 类
 *
 */
class PhException extends Exception 
{
	public function __construct($message, $code = 0)
	{
		parent::__construct($message, $code);
	}

	public function __toString()
	{
		return __CLASS__ . ": [{$this->code}]: {$this->message}";
	}
}

/**
 *
 * Thirft服务器获取类
 *
 */
class ServerManager
{
	const ZOOKEEPER_MODE = 1;	//通过zookeeper获取服务器列表
	const DIRECT_MODE = 2; 		//直接配置服务器列表
	
	private $servers = array(); //服务器寄存器
	private $mode;  //通过哪种方式获取服务器列表
	private $_cache_interval = 1800; //服务器列表缓存半小时
	private $_cache_prefix; //缓存key前缀，针对不同的服务
	private $zk_path; //zookeeper对应的path路径

	public function __construct()
	{
	}
	
	public function __destruct()
	{
		$this->servers = array();	
	}
	
	/**
	 * 设置获取服务器列表方式
	 *
	 * @param int $mode
	 */
	public function setMode($mode)
	{
		$this->mode = $mode;
	}

	/**
	 * 添加服务器
	 *
	 * @param string $host
	 * @param int $port
	 * @param int $weight	权重
	 * @param int $timeout	连接持续(超时)时间(单位秒)
	 * @param int $retry_interval	服务器连接失败时重试的间隔时间(默认值15秒)。如果此参数设置为-1表示不重试。
	 */
	public function addServer($host, $port, $weight = 1, $timeout = 1, $retry_interval = 15)
	{
		$this->servers[] = array(
			'host' => $host,
			'port' => $port,
			'weight' => $weight,
			'timeout' => (int)$timeout,
			'retry_interval' => (int)$retry_interval,
		);
	}
	
	/**
	 * 移除服务器
	 *
	 * @param mixed $key
	 */
	public function removeServer($key)
	{
		if(isset($this->servers[$key])){
			unset($this->servers[$key]);
		}
	}

	/**
	 * 获取服务器列表
	 *
	 * @param bool $depart 是否按host和port返回两个数组
	 * @param string $uid 不为空时用uid取模
	 * @return array
	 */
	public function servers($depart = TRUE, $uid = '')
	{
		$index = 0;
		$num_servers = count($this->servers);
		if($uid){
			$index = (int)$uid % $num_servers;
		}

		if(!empty($this->servers) && $depart){
			$hosts = $ports = array();
			$count = 0;			
			foreach($this->servers as $serv){
				if($index && $index == $count){
					array_unshift($hosts, $serv['host']);
					array_unshift($ports, $serv['port']);
				}else{
					$hosts[] = $serv['host'];
					$ports[] = $serv['port'];
				}
				$count ++;
			}
			return array($hosts, $ports);
		}else{
			//将uid对应的server排到最前
			if($index){
				$tmp = $this->servers[0];
				$this->servers[0] = $this->servers[$index];
				$this->servers[$index] = $tmp;
			}
			return $this->servers;
		}
	}

	/**
	 * 解析地址
	 *
	 * @param string $address (host:port)
	 * @return mixed
	 */
	public function parseHost($address)
	{
		$parts = @parse_url(trim($address));
		if(isset($parts['host']) && isset($parts['port'])){
			return array('host' => $parts['host'], 'port' => $parts['port']);
		}else{
			return FALSE;
		}
	}
	
	/**
	 * 使用zookeeper逻辑获取服务器列表
	 *	servlist结构  array('host1:port1', 'host2:port2', ...)
	 * 
	 * @param array $servlist zookeeper服务器
	 * @param string $path 服务路径
	 * @return mixed
	 */
	private function getFromZKeeper($servlist = array(), $path = '')
	{
		$this->zk_path = $path;
		
		//检查zookeeper插件是否已安装
		if(!function_exists('phpZookeeperGetNodesValue')){
	    	log_message('error', 'Function `phpZookeeperGetNodesValue` is not defined. Please check zookeeperclient.so is loaded.');
			throw new PhException('Function `phpZookeeperGetNodesValue` is not defined.', ThriftCode::ERR_INTERNAL);
	    }

	    $zookeeper_list = array();
		foreach($servlist as $serv){
			if(FALSE !== ($ret = $this->parseHost($serv))){
				$zookeeper_list[] = $ret;
			}else{
				log_message('error', '['.__METHOD__.'] Warning: invalid zookeeper address: '.$serv);
				continue;
			}
		}

		if(empty($zookeeper_list)){
			log_message('error', '['.__METHOD__.'] Fatal error: zookeeper server list is invalid(check configuration item `tserver_set`).');
			throw new PhException('Miss required parameter: servlist', ThriftCode::ERR_WRONG_PARAM);
		}

	    if(empty($path)){
	    	log_message('error', '['.__METHOD__.'] Path parameter is empty');
	    	throw new PhException('Miss required parameter: path', ThriftCode::ERR_WRONG_PARAM);
	    }

	    $host_str = '';
	    foreach($zookeeper_list as $serv){
	    	$host_str .= join(':', $serv) . ',';
	    }
	    $host_str = substr($host_str, 0, -1);
	    
	    //记录访问时间到apc中
	    if(!$this->_cache_prefix){
	    	$this->_cache_prefix = md5(join('|', $servlist).'|'.$path, TRUE);
	    }
	    apc_store($this->_cache_prefix.'zkflag', time());
	    $ret = phpZookeeperGetNodesValue($host_str, $path);
	    //删除访问时间
	    apc_delete($this->_cache_prefix.'zkflag');
	    
	    $jsonRet = json_decode($ret, TRUE);
	    if(is_array($jsonRet) && $jsonRet['retCode'] === '0' && isset($jsonRet['nodes']) && count($jsonRet['nodes']) > 0){
	    	
	    	$cache_info = '';
	    	
	    	foreach($jsonRet['nodes'] as $node){
	    		$parts = array();
	    		if(strpos($node,'#') != FALSE){
	    			$parts = explode('#', $node, 2);
	    		}elseif(strpos($node,'`') != FALSE){
	    			$parts = explode('`', $node, 3);
	    		}
	    		if(count($parts) != 2 && count($parts) != 3){
	    			log_message('error', 'Zookeeper return an illegal node: ' . $node);
	    			continue;
	    		}
	    		$parts[0] = str_replace('host=', '', $parts[0]);
	    		$parts[1] = str_replace('port=', '', $parts[1]);
	    		//添加服务器
	    		$this->addServer($parts[0], $parts[1]);
	    		$cache_info .= $parts[0].':'.$parts[1].'|';
	    	}
	    	
	    	//保存服务器列表信息到cache
	    	if($cache_info){
	    		apc_store($this->_cache_prefix.'servlist', $cache_info.time());
	    	}
 	
	    }else{
	    	log_message('error', 'zk error['.$jsonRet['retCode'].']: '.$jsonRet['retMsg'].', nodes: '.(isset($jsonRet['nodes']) ? (string)$jsonRet['nodes'] : 'null').', path: '.$path);
	    	throw new PhException('Zookeeper causes an error', ThriftCode::ERR_INTERNAL);
	    }

	    if(empty($this->servers)){
	    	log_message('error', 'Zookeeper return wrong response: ' . (string)$ret);
			throw new PhException('Zookeeper causes an error', ThriftCode::ERR_INTERNAL);
	    }
	}

	/**
	 * 获取服务器列表
	 *
	 * @param array $servlist
	 */
	public function fetchHosts($servlist, $path = '')
	{
		try{
			if($this->mode == self::ZOOKEEPER_MODE){
				
				//计算key前缀
				$this->_cache_prefix = md5(join('|', $servlist).'|'.$path, TRUE);
				
				$cache_string = apc_fetch($this->_cache_prefix.'servlist');
				$cache_list = explode('|', $cache_string);
				$cache_time = 0;
				if(count($cache_list) > 1){
					$cache_time = (int)array_pop($cache_list);
				}
				$req_zk_time = apc_fetch($this->_cache_prefix.'zkflag');
				$expired = (time() - $cache_time > $this->_cache_interval);
				
				if($expired && !$req_zk_time){
					//从zookeeper获取服务器列表
					log_message('info', 'Get machine list from zookeeper, path:'.$path);
					$this->getFromZKeeper($servlist, $path);
					
				}else{
					//从缓存中获取服务器列表
					log_message('info', 'Get machine list from cache, path:'.$path.', expired:'.($expired ? 'yes':'no')
												.', request zk flag:'.($req_zk_time ? date('Y-m-d H:i:s', $req_zk_time) : 'none'));
					
					foreach($cache_list as $_serv){
						$info = explode(':', $_serv);
						if(count($info) != 2){
			    			continue;
			    		}
			    		$this->addServer($info[0], (int)$info[1]);
					}

					if(empty($this->servers)){
				    	log_message('error', 'Zookeeper cache is wrong: ' . (string)$cache_string . ', path:'.$path);
						throw new PhException('Zookeeper causes an error', ThriftCode::ERR_INTERNAL);
				    }
				}

			}elseif($this->mode == self::DIRECT_MODE){
				foreach($servlist as $serv){
					if(FALSE !== ($ret = $this->parseHost($serv))){
						$this->addServer($ret['host'], $ret['port']);
					}else{
						log_message('error', '['.__METHOD__.'] Warning: invalid zookeeper address: '.$serv.', path: '.$path);
						continue;
					}					
				}
			}else{
				log_message('error', __METHOD__.' type error: '.$this->mode);
				throw new PhException('Get servers by error type', ThriftCode::ERR_UNKNOWN_TYPE);
			}
		}catch(Exception $e){
		    log_message('error', __METHOD__.' error: '.$e->getMessage());
			throw $e;
		}
	}

	/**
	 * 通过uid选择一个服务器
	 *
	 * @param int $uid
	 * @return array
	 */
	public function selectByUid($uid)
	{
		$total = count($this->servers);
		$index = (int)$uid % $total;
		return $this->servers[$index];
	}

	/**
	 * 随机选择一个服务器
	 *
	 * @return array
	 */
	public function selectByRand()
	{
		if(empty($this->servers))
			return NULL;
		
		$key = array_rand($this->servers);
		return $this->servers[$key];
	}
}