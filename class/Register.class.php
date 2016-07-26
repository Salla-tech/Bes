<?php

/**
* 
*/
class Register{

	private $data;
	public $errors = array();
	
	function __construct($data)	{
		
		$this->data = $data;
	}


	public function isEmpty($content = [], $msgErrors){

		if (empty($content)) {
			$errors[] = $msgErrors;
		}
	}

	public function defineVariable($variable){

		$variable = htmlspecialchars($variable);
	}

	public function isName($name, $msgErrors){

		if (!isset($name) && !preg_match('/^[a-zA-Z]+$/', $name)) {
			# code...
		}
	}










}