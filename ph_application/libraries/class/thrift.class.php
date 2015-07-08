<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$ci =& get_instance();
$ci->load->helper('server_manager');

/**
 *
 * Thrift基类
 *
 */
abstract class ThriftBase
{
	protected $manager;
	protected $hosts;
	protected $ports;
	protected $retry = 1;
	protected $socket;
	protected $transport;
	protected $protocol;
	protected $sendTimeout = 15000; //单位毫秒
	protected $recvTimeout = 15000; //单位毫秒
	protected $_ci;

	const FRAME_TRANSPORT = 1;  	// TFramedTransport
	const BUFFER_TRANSPORT = 2;	 	// TBufferedTransport

	protected function __construct(ServerManager $manager)
	{
		require_once(THRIFT_ROOT.'Thrift.php');
		require_once(THRIFT_ROOT.'transport/TSocket.php');
		require_once(THRIFT_ROOT.'transport/TSocketPool.php');
		require_once(THRIFT_ROOT.'protocol/TBinaryProtocol.php');

		$this->_ci =& get_instance();
		$this->manager = $manager;
		list($this->hosts, $this->ports) = $this->manager->servers(TRUE, '');
	}

	/**
	 * 设置传输类型
	 *
	 * @param int $type
	 */
	public function setTransport($type)
	{
		$this->transport = $type;
	}

	/**
	 * 设置发送超时时间
	 *
	 * @param int $ms 单位毫秒
	 */
	public function setSendTimeout($ms)
	{
		$this->sendTimeout = $ms;
	}

	/**
	 * 设置响应超时时间
	 *
	 * @param int $ms 单位毫秒
	 */
	public function setRecvTimeout($ms)
	{
		$this->recvTimeout = $ms;
	}

	/**
	 * 初始化thrift
	 *
	 * @param bool $randomize 是否随机选择服务器，默认不随机选择
	 */
	public function run($randomize = TRUE)
	{
		try{
			if(count($this->hosts) == 1){
				$this->socket = new TSocket($this->hosts[0], $this->ports[0]);
			}else{
				$this->socket = new TSocketPool($this->hosts, $this->ports);
				$this->socket->setRandomize((bool)$randomize); //是否随机选择服务器
			}
			$this->socket->setSendTimeout($this->sendTimeout);
			$this->socket->setRecvTimeout($this->recvTimeout);
			$this->transport = $this->createTransport($this->transport, $this->socket);
			$this->protocol = new TBinaryProtocol($this->transport);
			$this->transport->open();
		}
		// @codeCoverageIgnoreStart
		catch(Exception $e){
			log_message('error','Thrift error['.$e->getCode().']: '.$e->getMessage());
			throw new PhException('Thrift connects failed', ThriftCode::ERR_INTERNAL);
		}
		// @codeCoverageIgnoreEnd
	}

	/**
	 * 创建transport类
	 *
	 * @param int $type
	 * @return mixed
	 */
	private function createTransport($type, &$socket)
	{
		$transport = NULL;
		switch($type){
			case self::FRAME_TRANSPORT:
				if(!class_exists('TFramedTransport')){
					require_once(THRIFT_ROOT.'transport/TFramedTransport.php');
				}
				$transport = new TFramedTransport($socket);
				break;
			case self::BUFFER_TRANSPORT:
				if(!class_exists('TBufferedTransport')){
					require_once(THRIFT_ROOT.'transport/TBufferedTransport.php');
				}
				$transport = new TBufferedTransport($socket);
				break;
		}
		if(is_null($transport)){
			log_message('error', '['.__METHOD__.'] Warning: never create any transport.');
		}
		return $transport;
	}

	/**
	 * @codeCoverageIgnore
	 * 析构函数
	 *
	 */
	public function __destruct()
	{
		if($this->transport instanceof TTransport){
			$this->transport->close();
		}
		$this->transport = NULL;
		$this->protocol = NULL;
	}
}