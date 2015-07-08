<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 返回码
 *
 */
class PointHunterCode
{
	const SUCCESS     = 0;        //  Success	操作成功
}

/**
 * thrift客户端
 *
 */
class PointHunter extends ThriftBase
{
	private $client;

	public function __construct()
	{
		$config = config_item('pointHunter');
		if(!$config){
			log_message('error', 'Miss configuration item `pointHunter`');
			throw new PhException('Miss required configuration', ThriftCode::ERR_INTERNAL);
		}

		$manager = new ServerManager();
		if(!empty($config['skip_zookeeper'])){
			$manager->setMode(ServerManager::DIRECT_MODE);
			$manager->fetchHosts($config['bare_servers']);
		}else{
			// @codeCoverageIgnoreStart
			$manager->setMode(ServerManager::ZOOKEEPER_MODE);
			$manager->fetchHosts($config['zookeeper_servers'], $config['zookeeper_path']);
			// @codeCoverageIgnoreEnd
		}
		
		parent::__construct($manager);
		$this->setTransport(self::BUFFER_TRANSPORT);
		$this->run();

		require_once(LIB_DIR.'object/pointHunter.php');
		$this->client = new pointhunter_PointHunterClient($this->protocol);
	}

	/**
	 * @codeCoverageIgnore
	 * 析构函数
	 *
	 */
	public function __destruct()
	{
		parent::__destruct();
		$this->client = NULL;
	}
	
	public function listPoints($uid, $pDay, $dayNum)
	{
		$param = new pointhunter_ListPointsReqStruct();
		$param->uid = $uid;
		$param->previous_day = $pDay;
		$param->day_num = $dayNum;
		$resultInfo = $this->client->listPoints($param);
		
		switch($resultInfo->result){
			case PointHunterCode::SUCCESS:
				return $resultInfo->points;
			default:
				log_message('error', __FUNCTION__.' return wrong response:'.$resultInfo->result);
				return array();
				
		}
		
	}
	
	public function listNewPoints($uid, $offset, $num)
	{
		$param = new pointhunter_ListNewPointsReqStruct();
		$param->uid = $uid;
		$param->offset = $offset;
		$param->length = $num;
		
		$resultInfo = $this->client->listNewPoints($param);
		
		switch($resultInfo->result){
			case PointHunterCode::SUCCESS:
				return $resultInfo->points;
			default:
				log_message('error', __FUNCTION__.' return wrong response:'.$resultInfo->result);
				return array();
				
		}
	}
	
	public function getPointsCount($uid)
	{
		$param = new pointhunter_getPointsCountReqStruct();
		$param->uid = $uid;
		
		$resultInfo = $this->client->getPointsCount($param);
		
		switch($resultInfo->result){
			case PointHunterCode::SUCCESS:
				return $resultInfo->count;
			default:
				log_message('error', __FUNCTION__.' return wrong response:'.$resultInfo->result);
				return 0;
				
		}
	}
	
	public function recommentPoint($uid, $pointId)
	{
		$param = new pointhunter_RecommentPointReqStruct();
		$param->uid = $uid;
		$param->point_id = $pointId;
		
		$resultInfo = $this->client->recommentPoint($param);
		
		switch($resultInfo->result){
			case PointHunterCode::SUCCESS:
				return TRUE;
			default:
				log_message('error', __FUNCTION__.' return wrong response:'.$resultInfo->result);
				return FALSE;
				
		}
	}


}