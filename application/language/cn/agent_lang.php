<?php
$ci = get_instance(); // CI_Loader instance
$ci->load->config('config');
$ci->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file','key_prefix' => 'lang_'));
$lng = 'cn';

if (!$result = $ci->cache->get($lng))
{
  	$result   = cUrl($ci->config->config['api_url'].'/lang',"post","lng=".$lng);
  	$ci->cache->save($lng, $result, 1 * 1440 * 365); // 1 year
}
$decode = json_decode($result);
$lang   = array();
if(!empty($decode)){
	foreach ($decode as $k => $v) {
		$lang[$v->word] = $v->translate;			
	}
}
