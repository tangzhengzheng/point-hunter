<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function site_url($uri = '', $flag = NULL)
{
	$CI =& get_instance();
	return $CI->config->site_url($uri, $flag);
}

function base_url($uri = '')
{
	$CI =& get_instance();
	return $CI->config->base_url($uri);
}