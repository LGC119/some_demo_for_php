<?php 
echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /> 
";
echo "<table border='1px' align='center' width='800px'>
	<caption><h1>学生成绩表</h1></caption>";

		$j = 0;
		$k = 0;
		while ($j<10) {
			
			if ($j%2 == 1) {
				$color = "#ccc";
			}
			elseif ($j%2 == 0) {
				$color = "#aaffcc";
			}
			$j++;

			echo "<tr onmouseover='show(this)' onmouseout='unshow(this)' bgcolor=".$color.">";
				$i = 0;
				while ($i<10) {
					echo "<td>".$k++."</td>";
					$i++;
				}
			$i = $i + 10;
			
			echo "</tr>";

		}
	 
	
echo "</table>";
echo " <script>
	 var dj = NULL;
	 function show(obj){
	 	dj = obj.style.backgroundColor;
	 	obj.style.backgroundColor='aaffaa';
	 }
	 function unshow(obj){
	 	obj.style.backgroundColor=dj;
	 }

	 </script>";
?>