

<?php 

if (!function_exists('e')) {
	function e($string){ 
		if ($string) {
			return htmlspecialchars($string); // return strip_tags($string);  permet d'enlever les balises html saisi par l'utilisateur pour eviter les failles xss
		}
		
	}
	
}


if (!function_exists('get_session')) {
	function get_session($key){ 
		if ($key) {
			return !empty($_SESSION[$key])
				? e($_SESSION[$key])
				: null;
		}
		
	}
	
}


// vas crypter le mot de passe avec blowfish alghorithm
/* if (!function_exists('bcrypt_hash_password')) {
	function bcrypt_hash_password($value, $options = array()){ 
		$cost = isset($options['rounds']) ? $options['rounds'] : 10;

		$hash = password_hash($value, PASSWORD_BCRYPT, array('cost' => $cost ));

		if ($hash === false)
		 {
			throw new Exception("Bcryt hashing n'est pas supporter", '');
			
		}
		return $hash;
	}
	
}

// Verifie le mot de passe hashé
if (!function_exists('bcrypt_verify_password')) {
	function bcrypt_verify_password($value, $hashedValue){ 
		return password_verify($value, $hashedValue);
	}
	
}

*/


// Verifie si l'utilisateur est connecté
if (!function_exists('is_logged_in')) {
	function is_logged_in(){ 
		return isset($_SESSION['user_id']) || isset($_SESSION['pseudo']);
	}
	
}



// redirige vers la page ou il voulait acceder
if (!function_exists('redirect_intent_or')) {
	function redirect_intent_or($default_url){ 
		if ($_SESSION['forwarding_url']) {
			$url = $_SESSION['forwarding_url'];
		}else {
			$url = $default_url;
		}
		$_SESSION['forwarding_url'] = null;
		redirect($url);
	}
	
}



if (!function_exists('get_current_locale')) {
	function get_current_locale(){ 
		return $_SESSION['locale'];
	}
	
}




if (!function_exists('find_user_by_id')) {
	function find_user_by_id($id){ 
		global $db;

		$q = $db->prepare("SELECT name, pseudo, email, city, country, twitter, facebook, sex, available_for_hiring, bio FROM users WHERE id = ?");
		$q->execute([$id]);

			$data =$q->fetch(PDO::FETCH_OBJ); 
			$q->closeCursor();
			return $data;

	}
	
}





if (!function_exists('not_empty')) {
	function not_empty($fields = array()) {
		if (count($fields) != 0) {
			foreach ($fields as $field) {
				if(empty('$_POST[$field') || trim('$_POST[$field') == "") {
					return false;
				}
			}

			return true;
		}
	}
}


if (!function_exists('set_flash')) {
	function set_flash($message, $type = 'info'){
		$_SESSION['notification']['message'] = $message;
		$_SESSION['notification']['type'] = $type;
	}
}


if (!function_exists('redirect')) {
	function redirect($page){
		header('location: ' .$page);
		exit();
}
}


if (!function_exists('save_input_data')) {
	function save_input_data(){
		foreach ($_POST as $key => $value) {
			if (strpos($key, 'password') === false) {
				$_SESSION['input'][$key] = $value;
			}
			
		}
	}

}


if (!function_exists('get_input')) {
	function get_input($key){	
			return !empty($_SESSION['input'][$key])
				? e($_SESSION['input'][$key])
				: null;
		}
	}


if (!function_exists('clear_input_data')) {
	function clear_input_data(){
	if (isset($_SESSION['input'])) {
		$_SESSION['input'] = array();
		}
	}
	
}

if (!function_exists('clear_input_data')) {
	function clear_input_data(){
	if (isset($_SESSION['input'])) {
		$_SESSION['input'] = array();
		}
	}
	
}



if (!function_exists('set_active')) {
	function set_active($file, $class = 'active'){ 
	$page= array_pop(explode('/', $_SERVER['SCRIPT_NAME']));
		if ($page == $file. '.php'){
			return $class;
		}else{
			return "";
		}
	}
	
}
// cette fonction permet de générer un code
function str_random($lenght){
	$numerique ="0123456789";
	return substr(str_shuffle(str_repeat($numerique, $lenght)), 0, $lenght);
}


//tester lexistence d'une session
if (!function_exists('sessionStart')) {
	function sessionStart(){
	if (session_status() == PHP_SESSION_NONE) {
 session_start();
 }
}
}


?>