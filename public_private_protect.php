<?php
/**
* 
*/
class BaseClass 
{
	
	public $public = 'public';
	private $private = 'private';
	protected $protected = 'protected';
	function _construct(){

	}

	function print_var(){
		print $this->public;
			echo '<br />';
		print $this->private;
			echo '<br />';
		print $this->protected;
			echo '<br />';

	}
}
/**
* 
*/
class Subclass extends BaseClass
{
	
	protected $protected = 'protected2';
	function _construct(){

		echo $this->protected;
		echo '<br />';
		echo $this->private;
	}
}


$test1 = new BaseClass();
$test1->print_var();
echo $test1->public."$nbsp <strong>this by test1</strong>";
echo '<br />';

$test2 = new Subclass();
echo "<br />";
echo $test2->public;
echo "<br />";
$test2->print_var;
echo "<br />";
echo $test2->protected;
echo "<br />";



?>