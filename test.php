<?php 
	$uid = $_GET("uid");
	$url = "http://xxx/space-uid-001.html?uid='{$uid}'";
	$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);				// 设置抓取的数据的输出方式 1.文件流 0.直接输出
		$output = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($output,true);
 ?>