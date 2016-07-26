<?php 
require_once "includes/bootstrap.php";
session_start();
if (isset($_POST['inscription'])) {
  $errors = array();
  extract($_POST);
  $solde = 0;

  $db = App::getDatabase();

  if (empty($_POST['name']) && empty($_POST['email']) && empty($_POST['num_ident']) && empty($_POST['num_tel'])
   && empty($_POST['password']) && empty($_POST['repeat_password'])) {
    $errors[] = "Vous devez remplir tous les champs";
  }

  //Validation Name
  $name = htmlentities($_POST['name']);
  if (empty($name) || !preg_match('/^[a-zA-Z ]+$/', $name)) {
    
    $errors['name'] = "Votre nom doit être alphanumérique (a-zA-Z0-9)";

  }
    //Validation email
  $email = htmlentities($_POST['email']);
  if (empty($email) || !filter_var($email,FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Votre email n'est pas valide";
  }else{
    $record = $db->query("SELECT id FROM entreprise WHERE email = ?", [$email])->fetch();
    if ($record) {
      $errors['email'] = "Cette adresse email est dèja utilisé pour un autre compte";
    }
  }

    
   //Validation Numéro identification
  $num_ident = htmlentities($_POST['num_ident']);

    if (empty($num_ident) || strlen($num_ident) != 9 || !preg_match('/^[a-zA-Z0-9]+$/', $num_ident)) {
      $errors['num_ident'] = "Le NINEA doit être alphanumérique";
    }else{
    $record = $db->query("SELECT id FROM entreprise WHERE num_ident = ?", [$num_ident])->fetch();
    if ($record) {
      $errors['num_ident'] = "Le NINEA est dèja utilisé pour un autre compte";
    }
  }


         //Validation Numéro de téléphone
  $num_tel = htmlentities($_POST['num_tel']);
   if (empty($num_tel) || strlen($num_tel) != 9 || !preg_match('/^[0-9]+$/', $num_tel)) {
      $errors['num_tel'] = "Votre numéro de téléphone doit être numérique et contenir 9 chiffres(770001122).";
    }else{
    $record = $db->query("SELECT id FROM entreprise WHERE num_tel = ?", [$num_tel])->fetch();
    if ($record) {
      $errors['num_tel'] = "Le numéro de téléphone est dèja utilisé pour un autre compte";
    }
  }
  
    // Validation password
  $password = htmlentities($_POST['password']);
  if (empty($password) || mb_strlen($password) <6) {
    $errors['password'] = "Votre mot de passe doit contenir au moins 6 caractère";
  }

  $repeat_password = htmlentities($_POST['repeat_password']);
  if (empty($repeat_password) || $repeat_password != $password) {
    $errors['repeat_password'] = "Votre deux mot de passe doit être identique";
  }
  
    
  // teste des erreurs et enregistrement de l'utilisateurs
$password= base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($password), $password, MCRYPT_MODE_CBC, md5(md5($password))));

  if (empty($errors)) {
        
    $db->query("INSERT INTO entreprise SET name = ?, email = ?, num_ident = ?,num_tel =?, password= ?, solde = ?",
     [$name, $email, $num_ident, $num_tel, $password, $solde]);    

      
      $_SESSION['flash'] = "Inscription términè vous pouvez vous connectez à votre compte";  

    header('location:entreprise.php');
    exit();
     }
  }




require_once "includes/_header.php"; ?>

<?php 
  
  if (isset($_POST['connexion'])) {
    $errors = array();
    if (empty($_POST['num_ident']) && empty($_POST['password'])) {
      $errors[] = "Veuillez svp remplir tous les champs";
    }

    $num_ident = htmlentities($_POST['num_ident']);
    $password = htmlentities($_POST['password']);
    $password= base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($password), $password, MCRYPT_MODE_CBC, md5(md5($password))));

      $db = App::getDatabase();

      $entreprise = $db->query("SELECT * FROM entreprise WHERE num_ident = ? AND password = ?", [$num_ident,$password])->fetch();
      if ($entreprise) {
        session_start();
   
      $_SESSION['entreprise_id'] = $entreprise->id;
      $_SESSION['name_entreprise'] = $entreprise->name;
      $_SESSION['email_entreprise'] = $entreprise->email;
      $_SESSION['num_tel_entreprise'] = $entreprise->num_tel;
      $_SESSION['num_ident_entreprise'] = $entreprise->num_ident;
      $_SESSION['solde_entreprise'] = $entreprise->solde;
      $_SESSION['date_inscrit_entreprise'] = $entreprise->date;

        header('location:Entreprise/index.php?id='.$entreprise->id);
      }

  }

 ?>

  <body>

  <?php require_once "includes/_nav.php";?>

    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>Compte entreprise</h1>
        <p>Inscrivez-vous, déjà entreprise connectez-vous.</p>




        <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Banque électonique du Sénégal</h4>
        </div>
        <div class="modal-body">
          <h4>Formulaire de connexion</h4>    

          <hr>

          
        <form action="" method="POST">
  <div class="form-group">
    <label  for="num_ident">Votre NINEA</label>
    <input type="text" name="num_ident" class="form-control" require="required" placeholder="1234567891234"/>
  </div>

  <div class="form-group">
    <label  for="password">Mot de passe</label>
    <input type="password" name="password" class="form-control" require="required" placeholder="Votre mot de passe">
  </div> 
  <input type="submit" class="btn btn-primary" name="connexion" value="Se connecter">
</form>
        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
        </div>

      </div>
    </div>
  </div>

  <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
    Me connecter  
  </button>
  <br><br>
  <div id="tall" style="display: none;">
    
  </div>

       </div>
    </div>
    <div class="col-md-12">
      <div class="col-md-3">
      <br/>
      <div class="panel panel-success">
                <div class="panel-heading">
                  <h3 class="panel-title">Compte entreprise</h3>
                </div>
                <div class="panel-body">
                 <ul>
            <li>Accéder à mes infos</li>
            <li>Voir vos paiements de salaire</li>
            <li>Historique des rechargements</li>
            <li>Contact</li>
            <li>Effectuer un paiement</li>
            <li>Paiement de salaire</li>
            <li>Achat de carburant</li>
            <li>Me deconnecter</li>
          </ul>
                </div>
              </div>
    </div>
    <div class="col-md-5">
    <?php
      if (isset($_SESSION['flash'])) {
        echo "<div class='aler alert-success'>";
        echo $_SESSION['flash'];
        echo "</div>";

        session_destroy();
      }
     ?>
    <h1>Inscription</h1>
    <?php if (!empty($errors)): ?>

  <div class="alert alert-danger">
    <p>Vous n'avez pas rempli le formulaire correctement</p>
    <ol>
    <?php foreach ($errors as $error): ?>
      <li><?= $error; ?></li>
    <?php endforeach; ?>
    </ol>
  </div>
<?php endif; ?>
      <form action="" method="POST">
  <div class="form-group">
    <label  for="name">Nom de l'entreprise</label>
    <input type="text" class="form-control" name="name"/>
  </div>
  <div class="form-group">
    <label  for="email">Email</label>
    <input type="email" class="form-control" name="email" >
  </div>
  <div class="form-group">
    <label  for="num_ident">NINEA</label>
    <input type="text" class="form-control" name="num_ident"/>
  </div>
  <div class="form-group">
    <label  for="num_tel">Numéro de téléphone</label>
    <input type="text" class="form-control" name="num_tel"/>
  </div>
  <div class="form-group">
    <label  for="password">Mot de passe</label>
    <input type="password" class="form-control" name="password" >
  </div>
    <div class="form-group">
    <label  for="repeat_password">Répetez Mot de passe</label>
    <input type="password" class="form-control" name="repeat_password">
  </div> 
  <input type="submit" class="btn btn-primary" name="inscription" value="M'inscrire">
</form>
    </div>
    <div class="col-md-4">
    <br/>
    <div class="panel panel-success">
                <div class="panel-heading">
                  <h3 class="panel-title">Partenaires</h3>
                </div>
                <div class="panel-body">
                  Nos partenaires
                </div>
              </div>
    </div>
</div>
    </div> <!-- /container -->


   <?php require_once "includes/_footer.php";?>