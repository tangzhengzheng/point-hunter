<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * 异步控制器
 *
 */
class Ajax extends PH_Controller
{
	public function getIndexData()
	{
		$curDay = 0;
		$perDayNum = DAY_NUM;
		$curNum = 0;
		if($this->input->get('d')){
			$curDay = (int)$this->input->get('d');
		}
		if($this->input->get('curNum')){
			$curNum = (int)$this->input->get('curNum');
		}
		$dataList = array();	
		try{
    		$pointHunter = ThriftFactory::getInstance(ThriftFactory::TYPE_PH);
		}catch(Exception $e){
    		log_message('error', __METHOD__.' catch an error, '.$e->getMessage());
    		$response = array(
				'loadAll' => 0,
				'dataList' => $dataList,
				'curDay' => $curDay
			);
			$this->send_json($response);
			exit;
		}
		$pointCount = $pointHunter->getPointsCount($this->userId);
		//循环10次获取数据
		for($i=0; $i<10; $i++){
			$result = $pointHunter->listPoints($this->userId, $curDay, $perDayNum); 
			if(count($result) == 0){
				$curDay += $perDayNum;
				continue;
			}
			foreach($result as $item){
				$curNum ++;
				$day = floor((strtotime(date('Y-m-d 23:59:59', time())) - $item->last_recomment_time) / (60 * 60 * 24)); 
				if(!isset($dataList[$day]->date))
				{
					$dataList[$day] = new stdClass();
					$dataList[$day]->date = $day == 0 ? '今天' : date("n月d日",strtotime("-".$day." day"));
				}
				$this->_formatPoint($item);
				$dataList[$day]->pointList[] = $item;
			}
			$curDay = max(array_keys($dataList));
			rsort($dataList);
			break;
		}
		
		$response = array(
			'loadAll' => $curNum >= $pointCount ? 1 : 0,
			'dataList' => $dataList,
			'curDay' => $curDay+$perDayNum
		);
		$this->send_json($response);
	}
	
	public function getNewData()
	{
		$offset = 0;
		$perNum = NEW_POINT_NUM;
		if($this->input->get('offset')){
			$offset = (int)$this->input->get('offset');
		}
		$dataList = array();	
		try{
    		$pointHunter = ThriftFactory::getInstance(ThriftFactory::TYPE_PH);
		}catch(Exception $e){
    		log_message('error', __METHOD__.' catch an error, '.$e->getMessage());
    		$response = array(
				'loadAll' => 0,
				'dataList' => $dataList
			);
			$this->send_json($response);
			exit;
		}
		$result = $pointHunter->listNewPoints($this->userId, $offset, $perNum); 
		foreach($result as $item){
			$this->_formatPoint($item);
			$dataList[0]->pointList[] = $item;
		}
		$response = array(
			'loadAll' => count($dataList) == 0 ? 1 : 0,
			'dataList' => $dataList
		);
		$this->send_json($response);
	}
	
	private function _formatPoint(&$item)
	{
		$item->isNew = date('Y-m-d') == date('Y-m-d', $item->create_time) ? 1 : 0;
		$item->star = array_pad(array(), $item->level, 1);
		foreach($item->platform_list as $platform){
			switch($platform->type){
				case 1:
					$item->androidUrl = $platform->url;
					break;
				case 2:
					$item->iosUrl = $platform->url;
					break;
				case 3:
					$item->pcUrl = $platform->url;
					break;
				case 4:
					$item->homeUrl = $platform->url;
					break;
			}
		}
		$item->voted = 0;
		if($this->userId == ''){
			$item->voted = 1;
		}else if($item->creator->id == $this->userId || $item->hunter->id == $this->userId){
			$item->voted = 1;
		}else{
			foreach($item->upvoters as $upvoters){
				if($upvoters->id == $this->userId){
					$item->voted = 1;
					break;
				}
			}
		}
		if($item->creator->id == 0){
			unset($item->creator);
		}
		if($item->hunter->id == 0){
			unset($item->hunter);
		}
	}
	
	public function recomment()
	{
		$result = FALSE;
		if($this->input->get('p') && $this->userId != ''){
			$pointId = (int)$this->input->get('p');
			try{
    			$pointHunter = ThriftFactory::getInstance(ThriftFactory::TYPE_PH);
    			$result = $pointHunter->recommentPoint($this->userId, $pointId);
			}catch(Exception $e){
    			log_message('error', __METHOD__.' catch an error, '.$e->getMessage());
			}
		}
		$response = array(
			'result' => $result,
		);
		$this->send_json($response);
	}
}