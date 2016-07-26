<?php


/**
* 
*/
class Session{
	
	function __construct()	{
		session_start();
	}

	public function setFlash($key, $message){

		$_SESSION['flash'][$key] = $message;
	}
}