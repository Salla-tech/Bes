<?php

/**
* Permet de valider les données
*/
class Validator{

	public $solde = 0;
	private $data;
	public $errors = [];

	public function __construct($data){
		$this->data = $data;
	}
	

	public function isEmpty($content = [], $errorMsg){

		if (empty($content)) {
			$this->errors[$field] = $errorMsg;
		}
	}

	private function getField($field){

		if (!isset($this->data[$field])) {
			return null;
		}

		return $this->data[$field];
	}


	public function isAlphabetique($field, $errorMsg){

		if (!isset($this->data[$field]) || !preg_match('/^[a-zA-Z ]+$/', $this->getField($field))) {
    		
    		$this->errors[$field] = $errorMsg;
  		}
	}


	public function isUniq($field, $db, $table, $errorMsg){

		$record = $db->query("SELECT id FROM $table WHERE $field = ?", [$this->getField($field)])->fetch();
   		 if ($record) {
     	 $this->$errors[$field] = $errorMsg;
    	}
	}

	public function isEmail($field, $errorMsg){
		if (!filter_var($this->getField($field), FILTER_VALIDATE_EMAIL)) {
    
    	$this->$errors[$field] = $errorMsg;
  		}
	}



	public function isConfirmed($field, $errorMsg){

		$value = $this->getField($field);

		 if (empty($value) || $value != $this->getField('repeat-'.$field)) {
    
   		 $this->errors[$field] = $errorMsg; 
  		}
	}

	

	public function isValid(){

		return empty($this->errors);
	}

	public function getErrors(){

		return $this->errors;
	}


	
}