<?php 
	function demo($a){
		if ($a>1) {
			$r = $a*demo($a-1);
		}
		else{
			$r = $a;
		}
		return $r;
	}
	echo demo(10);
 ?>