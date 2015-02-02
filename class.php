<?php 
	/**
	* 
	*/
	class person
	{
		private $name;
		private $age;

		function __construct($name,$age)
		{
			$this->name = $name;
			$this->age = $age;
		}

		function say()
		{
			echo "My name is ".$this->name." and I'm ".$this->age."<br>";
			echo $this->eat()."<br>";
		}

		private function eat(){
			echo "Today egg is my lunch";
		}
		function __destruct(){
			echo "GoodBye ".$this->name."~<br>";
		}
	}

	class mobilePhone
	{
		var $brand;
		var $number;
		var $time;

		function __construct($brand,$number,$time){
			$this->brand = $brand;
			$this->number = $number;
			$this->time = $time;
		}

		
		function speak(){
			echo $this->time." my mobilePhone is ".$this->brand.",and the number is ".$this->number."<br>";
		}
		
		function __destruct(){
			echo "GoodBye ".$this->brand."<br>";
		}
			
		

	}

	$p1 = new person("Sean Malto","23");
	$p2 = new person("Rayn Sheckler","24");
	$p1->say();
	$p2->say();

	$m1 = new mobilePhone("Nokia","13260360119","Now");
	$m2 = new mobilePhone("Motorola","15941443949","One year ago");
	$m1->speak();
	$m2->speak();

	
 ?>		
