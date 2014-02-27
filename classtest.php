<?php
	class TestClass
	{
		public $prop = "Class property";

		public function __construct($arg1)
		{
			$this->prop = $arg1;
		}

		public function echoProp()
		{
			echo $this->prop;
		}
	}

	$instance = new TestClass("NEW CLASS PROP 2");
	$instance->echoProp();
?>