<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * 电子控制器
 *
 */
class Idea extends PH_Controller
{
	public function create()
	{
		if($this->userId == ''){
			redirect(site_url('home/index'));
		}
		print_r($_POST);
	}

    public function detail()
    {
        $data = array();
        $data['uid'] = $this->userId;
        $this->showView('detail', $data);
    }
}