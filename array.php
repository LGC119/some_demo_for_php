<?php 
/*
		list 把数组转化成变量。
*/
	// $arr = array(123=>array(1,2,34),"abc"=>array("a","b","c"),array(4=>"d",5=>"e",6=>"f"));

	// foreach ($arr as $key => $value) {
	// 	foreach ($value as $k => $v) {
	// 		echo "\$arr[".$key."][".$k."]=".$v."<br>";
	// 	}
	// }
	$arr = array(1,2,3,4,5,);

	while ($a = each($arr)) {
			
			echo "\$arr[".$a["key"]."]=".$a["value"]."<br>";
		
	}

 ?>