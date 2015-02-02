<!DOCTYPE html>
<html>
<head>
	<title>手机号码验证</title>
</head>
<body>
<form action="#" method="post">
	<input type="text" name="tel_num" />
	<input type="submit" value="验证" />
</form>
</body>
</html>
<?php 
	$tel_num = $_POST['tel_num'];
	if ($tel_num) {
		if (preg_match_all("/1[358]\d{9}/", $tel_num,$matches)) {
			echo "你输入的手机号码是：".$matches[0][0];
		}
		else{
			echo "你输入的不是手机号码";
		}
	}

 ?>