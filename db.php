<?php 

/*
Je me connecte à la base de donnée ensuite j'utilise define pour definir le nom de l'hote, de la base de l'utilisateur et le mot de passe
je dit a php avec try essaie de te connecter a la base de donnée et créer un nouvel instance de l'objet PDO donc j'instancie l'objet avec $db=new PDO
Si il n'arrive pas a se connecter il va  capturer l'erreur ensuite avec die il va retourner le message d'erreur.
*/
//Information de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'bes');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
//essaie de se connecter
try {
	$db= new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD);
//on modifie l'attribut de gestion des erreurs pour le débogage avec PDO::ERRMODE_WARNING ou _EXCEPTION
// ERRMODE_EXCEPTION il léve en effet une exeption de type PDOException qui est ensuite capturé par notre bloc catch ce qui fait que c'est le 
//die('Erreur:' $e->getMessage()); qui sexecute alors que warning va afficher l'erreur mais le code va continuer de s'executer
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

} catch (PDOException $e) {
	die('Erreur:'.$e->getMessage());
	
}



?>