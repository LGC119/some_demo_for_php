<?php
	/**
	* 
	*/
	class insert_data
	{
		function __construct(){
			$con = mysql_connect("localhost","root","314159265358") or die('database connect failed!');
			$db = mysql_select_db('test');
		}

	}
	
	$mysql_connect = new insert_data();
	// $result = mysql_query('select * from big_data where 1<study_num<1000000');
	// while ($row = mysql_fetch_array($result)) {
	// 	// var_dump($row);
	// 	echo '<br/>'.$row['id'].'<br />'.$row['study_num'];
	// }
	// for ($i=0; $i < 500000; $i++) { 
	// 	$user_id = rand();
	// 	$result = mysql_query("insert into big_data(study_num) values('$user_id')");
	// }
	fwrite(STDOUT, 'please input user_id:');
		$c1 = trim(fgets(STDIN));
	fwrite(STDOUT, 'please input course_name:');
		$c2 = trim(fgets(STDIN));
	$result = mysql_query("insert into course(user_id,course_name) values('$c1','$c2')");
	if ($result) {
		echo "input successed";
	}
	mysql_close();
?>