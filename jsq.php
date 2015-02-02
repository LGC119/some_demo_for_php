<html>
 <head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
 	<title>计算器</title>
 </head>
 <body>
 <form action="jsq.php">
 	<h1>简单计算器</h1>
 	<input type="text" placeholder='num1' name="num1">
 	<select name="fh">
 		<option value="+">+</option>
 		<option value="-">-</option>
 		<option value="x">x</option>
 		<option value="/">/</option>
 		<option value="%">%</option>
 	</select>
 	<input type="text" placeholder='num2' name="num2">
 	<input type="submit" value="=">
 </form>
 </body>
 </html>
 <?php 
	$num1 = $_REQUEST['num1'];
	$num2 = $_REQUEST['num2'];
	$fh   = $_REQUEST['fh'];

	function jsq($num1,$num2,$fh){
		$sum = '';
		switch ($fh) {
			case '+':
				$sum = $num1+$num2;
				break;
			case '-':
				$sum = $num1-$num2;
				break;
			case 'x':
				$sum = $num1*$num2;
				break;
			case '/':
				$sum = $num1/$num2;
				break;
			case '%':
				$sum = $num1%$num2;
				break;
		}
			return $sum;
	}

	echo jsq($num1,$num2,$fh);

 ?>
 
