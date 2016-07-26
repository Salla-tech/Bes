<?php
session_start();
if (!isset($_SESSION['entreprise_id']) && !isset($_SESSION['name_entreprise']) && !isset($_SESSION['email_entreprise']) && !isset($_SESSION['num_tel_entreprise'])
 && !isset($_SESSION['num_ident_entreprise']) && !isset($_SESSION['solde_entreprise'])) {
    header('location:../entreprise.php');
  }else{
    require_once "includes/bootstrap.php";
$db = App::getDatabase();
    $entreprise = $db->query("SELECT * FROM entreprise WHERE id = ?", 
        [$_GET['id']])->fetch();
      if ($entreprise) {
         $num_ident = $entreprise->num_ident;
         $emailUser = $entreprise->email;
         $numTel = $entreprise->num_tel;
         $soldeUser = $entreprise->solde;
         $date_inscrit = $entreprise->date_inscrit;
       }

?>

<?php // Changer email
  if (isset($_POST['change_email'])) {
    extract($_POST);
    $errors = [];
    $success = [];
    if (!empty($_POST['email'])) {
      $ancien_email = $emailUser;
      $password= base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($password), $password, MCRYPT_MODE_CBC, md5(md5($password))));

       $record = $db->query("SELECT email FROM entreprise WHERE email = ? AND password = ?", 
        [$ancien_email,$password])->fetch();
      if ($record) {
        $uniq = $db->query("SELECT email FROM entreprise WHERE email = ?", 
        [$_POST['email']])->fetch();
        if ($uniq) {
          $errors['email'] = "Cet adresse email existe déja dans la base de données";
        }else{
          $newEmail = htmlentities($_POST['email']);
          $db->query("UPDATE entreprise SET email = ? WHERE email = '$ancien_email'",[$newEmail]);
          $success[] = "Votre adresse email a bien été modifié le nouveau est $newEmail ";
        }
      }else{
        $errors[] = "Votre mot de passe ne correspond pas";
      }
    }else{
      $errors[] = "Veuillez svp remplir tous les champs";
    }
 }
?>

<?php // Changer Téléphone
  if (isset($_POST['change_tel'])) {
    extract($_POST);
    $errors = [];
    $success = [];
    if (!empty($_POST['new_num_tel'])) {
        $new_num_tel = htmlentities($_POST['new_num_tel']);
   if (empty($new_num_tel) || strlen($new_num_tel) != 9 || !preg_match('/^[0-9]+$/', $new_num_tel)) {
      $errors['num_tel'] = "Votre numéro de téléphone doit être numérique et contenir 9 chiffres(770001122).";
    }else{
      $ancien_tel = $numTel;
      $password= base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($password), $password, MCRYPT_MODE_CBC, md5(md5($password))));

       $record = $db->query("SELECT num_tel FROM entreprise WHERE num_tel = ? AND password = ?", 
        [$ancien_tel,$password])->fetch();
      if ($record) {
        $uniq = $db->query("SELECT num_tel FROM entreprise WHERE num_tel = ?", 
        [$new_num_tel])->fetch();
        if ($uniq) {
          $errors['num_tel'] = "Le numéro de téléphone existe déja dans la base de données";
        }else{
          
          $db->query("UPDATE entreprise SET num_tel = ? WHERE num_tel = '$ancien_tel'",[$new_num_tel]);
          $success[] = "Votre numéro de téléphone a bien été modifié le nouveau est $new_num_tel ";
        }
      }else{
        $errors[] = "Votre mot de passe ne correspond pas";
      }
  }

    
    }else{
      $errors[] = "Veuillez svp remplir tous les champs";
    }
 }
?>


<?php // Modifier mot de passe
  if (isset($_POST['change_pass'])) {
    extract($_POST);
    $errors = [];
    $success = [];
    if (!empty($_POST['ancien_password']) && !empty($_POST['new_password']) && !empty($_POST['new_repeat_password'])) {
      
      if ($new_password === $new_repeat_password) {
        $ancien_password = htmlentities($_POST['ancien_password']);
        $nouveau_pass  = htmlentities($_POST['new_password']);
      $ancien_password= base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($ancien_password), $ancien_password, MCRYPT_MODE_CBC, md5(md5($ancien_password))));
      $nouveau_pass= base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($nouveau_pass), $nouveau_pass, MCRYPT_MODE_CBC, md5(md5($nouveau_pass))));


  $record = $db->query("SELECT password FROM entreprise WHERE password = ?", 
        [$ancien_password])->fetch();
  if ($record) {

    $db->query("UPDATE entreprise SET password = ? WHERE password = '$ancien_password'",[$nouveau_pass]);

    $success[] = "Votre mot de passe a bien été modifié";

  } else $errors[] = "Vérifiez votre ancien mot de passe" ;


      }else $errors[] = "Les nouveaux mots de passe ne sont pas identiques";

    }else $errors[] = "Veuillez SVP remplir tous les champs";
  }


?>
<?php 
  
$dateMySQL = $user->date_inscrit;
//objet DateTime correspondant :
$date = new DateTime($dateMySQL);

//affichage de la date au format francophone:

?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Epace Entreprise</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="../css/style.css" media="screen">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../bower_components/html5shiv/dist/html5shiv.js"></script>
      <script src="../bower_components/respond/dest/respond.min.js"></script>
    <![endif]-->
    <script>

     var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-23019901-1']);
      _gaq.push(['_setDomainName', "bootswatch.com"]);
        _gaq.push(['_setAllowLinker', true]);
      _gaq.push(['_trackPageview']);

     (function() {
       var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
       ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
       var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
     })();

    </script>
  </head>
  <body>
   <?php
   require_once 'includes/nav.php';
    ?>


    <div class="container" style="margin-top:60px;">

      <!-- Navbar
      ================================================== -->


      <!-- Containers
      ================================================== -->
      <div class="bs-docs-section">

        <div class="row">
          <div class="col-lg-12">
            <div class="bs-component">
              <div class="jumbotron">
                <h2><?= "Bienvenue ".$_SESSION['name_entreprise']; ?></h2>
                <p>Vous pouvez administrez votre compte.<br></p>
               <?php echo $dateAUj = date("d/m/Y");?>
              </div>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-lg-12">
            <h2>Informations du compte client</h2>
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
          <?php if (!empty($success)): ?>

  <div class="alert alert-success">
    <p>Félicitations</p>
    <ol>
    <?php foreach ($success as $succes): ?>
      <li><?= $succes; ?></li>
    <?php endforeach; ?>
    </ol>
  </div>
<?php endif; ?>
        </div>
        <div class="row">
          <div class="col-lg-12">         
        
        <div class="row">
                       <div class="col-lg-5">

           <!-- Début Informations-->
           <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Informations de votre compte</h3>
                </div>
                <div class="panel-body">
                <ul>
                  <li>Nom de l'entreprise : <?= $entreprise->name; ?></li>
                  <li>Adresse email : <?= $entreprise->email; ?></li>
                  <li>NINEA : <?= $entreprise->num_ident; ?></li>
                  <li>Numéro de Téléphone : <?= $entreprise->num_tel; ?></li>
                  <li>Solde : <?php if ($entreprise->solde <= 0) {
                    $soldeUser = "Veuillez rechargez votre compte";
                    echo $soldeUser;
                  }else{echo $entreprise->solde;}  ?></li>
                  <li>Vous vous êtes inscrit à cette date : <?php echo $date->format('d/m/Y H:i:s'); ?></li>
                </ul>
                </div>
              </div>

              <!-- fin Informations-->

           
          </div>
          <div class="col-lg-4">

           <!-- Début html & css-->
           <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Modification des informations</h3>
                </div>
                <div class="panel-body">

                  <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Formulaire</h4>
        </div>
        <div class="modal-body">
          <h4>Modifier Email</h4>

          <form action="" method="POST">
	<div class="form-group">
		<label class="form-control" for="email">Nouveau Email</label>
		<input type="email" class="form-control" name="email" >
	</div> 
		<div class="form-group">
		<label class="form-control" for="password">Mot de passe</label>
		<input type="password" class="form-control" name="password" >
	</div>
	<input type="submit" class="btn btn-primary" name="change_email" value="Enregistrer">
</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Terminer</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">Annuler</button>
        </div>

      </div>
    </div>
  </div>

  <div id="myModalT" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Formulaire</h4>
        </div>
        <div class="modal-body">
          <h4>Modifier Téléphone</h4>

          <form action="" method="POST">
  <div class="form-group">
    <label class="form-control" for="new_num_tel">Nouveau numéro</label>
    <input type="text" class="form-control" name="new_num_tel" >
  </div> 
    <div class="form-group">
    <label class="form-control" for="password">Mot de passe</label>
    <input type="password" class="form-control" name="password" >
  </div>
  <input type="submit" class="btn btn-primary" name="change_tel" value="Enregistrer">
</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Terminer</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">Annuler</button>
        </div>

      </div>
    </div>
  </div>

  <div id="myModalP" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Formulaire</h4>
        </div>
        <div class="modal-body">
          <h4>Modifier Mot de Passe</h4>

          <form action="" method="POST">
  <div class="form-group">
    <label class="form-control" for="ancien_password">Ancien mot de passe</label>
    <input type="password" class="form-control" name="ancien_password"/>
  </div>
  <div class="form-group">
    <label class="form-control" for="new_password">Nouveau mot de passe</label>
    <input type="password" class="form-control" name="new_password" >
  </div> 
    <div class="form-group">
    <label class="form-control" for="new_repeat_password">Répétez mot de passe</label>
    <input type="password" class="form-control" name="new_repeat_password" >
  </div>
  <input type="submit" class="btn btn-primary" name="change_pass" value="Enregistrer">
</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Terminer</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">Annuler</button>
        </div>

      </div>
    </div>
  </div>
  <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">
    Modifier mon email 
  </button><br/><br/>
  <button class="btn btn-primary" data-toggle="modal" data-target="#myModalT">
    Modifier mon téléphone
  </button><br/><br/>
  <button class="btn btn-primary" data-toggle="modal" data-target="#myModalP">
    Modifier le mot de passe
  </button><br/><br/>
  <div id="tall" style="display: none;">
  </div>
                </div>
              </div>

              <!-- fin html & css-->

           
          </div>
     
                    <div class="col-lg-3">
                    <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Liste des points de rechargements</h3>
                </div>
                <div class="panel-body">
                <div id="myModalRe" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Formulaire de recherche</h4>
        </div>
        <div class="modal-body">
          <h4>Rechercher les points de rechargements</h4>

          <form action="" method="POST">
  <div class="form-group">
    <label class="form-control" for="email">Choisissez votre localité</label>
    <select name="region" id="region">
      <option value="dakar">Dakar</option>
      <option value="thies">Thiés</option>
      <option value="fatick">Fatick</option>
      <option value="kaolack">Kaolack</option>
      <option value="saint_louis">Saint-Louis</option>
      <option value="kaffrine">Kaffrine</option>
      <option value="louga">Louga</option>
      <option value="matam">Matam</option>
      <option value="sedhiou">Sédhiou</option>
      <option value="tambacounda">Tambacounda</option>
      <option value="ziguinchor">Ziguinchor</option>
      <option value="kedougou">Kédougou</option>
      <option value="diourbel">Diourbel</option>
      <option value="kolda">Kolda</option>
    </select>
    <select name="region" id="region">
      <option value="dakar">Dakar</option>
      <option value="pikine">Pikine</option>
      <option value="guediawaye">Guédiawaye</option>
      <option value="rufisque">Rufisque</option>
      <option value="thies">Thiès</option>
      <option value="mbour">Mbour</option>
      <option value="tivaoune">Tivaoune</option>
      <option value="fatick">Fatick</option>
      <option value="foundiougne">Foundiougne</option>
      <option value="gossas">Gossas</option>
      <option value="kaolack">Kaolack</option>
      <option value="nioro">Nioro du rip</option>
      <option value="guinguineo">Guinguineo</option>
      <option value="kolda">Kolda</option>
      <option value="myf">Médina-Yoro-Foula</option>
      <option value="velingara">Vélingara</option>
      <option value="diourbel">Diouberl</option>
      <option value="bambey">Bambey</option>
      <option value="mbacke">Mbacke</option>
      <option value="louga">Louga</option>
      <option value="linguere">Linguère</option>
      <option value="kebemer">Kebemer</option>
      <option value="saint_louis">Saint-Louis</option>
      <option value="dagana">Dagana</option>
      <option value="podor">Podor</option>
      <option value="matam">Matam</option>
      <option value="kanel">Kanel</option>
      <option value="ranerou">Ranerou-Ferlo</option>
      <option value="ziguinchor">Ziguinchor</option>
      <option value="oussouye">Oussouye</option>
      <option value="bignona">Bignona</option>
      <option value="tambacounda">Tambacounda</option>
      <option value="bakel">Bakel</option>
      <option value="goudiry">Goudiry</option>
      <option value="koumpentoum">Koumpentoum</option>
      <option value="sedhiou">Sédhiou</option>
      <option value="bounkiling">Bounkiling</option>
      <option value="goudomp">Goudomp</option>
      <option value="kaffrine">Kaffrine</option>
      <option value="kounghel">Kounghel</option>
      <option value="mbirkilane">Mbirkilane</option>
      <option value="malem_hodar">Malem-Hodar</option>
      <option value="kedougou">Kédougou</option>
      <option value="salemata">Salémata</option>
      <option value="sareya">Saréya</option>
    </select>
  </div> 
     <input type="submit" class="btn btn-primary" name="rechercher" value="Rechercher">
</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Terminer</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">Annuler</button>
        </div>

      </div>
    </div>
  </div>
  <button class="btn btn-success" data-toggle="modal" data-target="#myModalRe">
    Afficher la liste 
  </button><br/><br/>

                </div>
              </div>

          

           
          </div>
        </div>
      </div>

      <!-- Dialogs
      ================================================== -->
      <div class="bs-docs-section">

        
      </div>

      <div id="source-modal" class="modal fade">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Source Code</h4>
            </div>
            <div class="modal-body">
              <pre></pre>
            </div>
          </div>
        </div>
      </div>

      <footer>
        <div class="row">
          <div class="col-lg-12">
            
            

          </div>
        </div>

      </footer>


    </div>
<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../js/ie10-viewport-bug-workaround.js"></script>
    <script src="../js/dropdown.js"></script>
    <!-- JavaScript Includes -->
<script src="../js/jquery.min.js"></script>
<script src="../js/transition.js"></script>
<script src="../js/modal.js"></script>
<script src="../js/tooltip.js"></script>
<script src="../js/popover.js"></script>
<script src="../js/collapse.js"></script>

<!-- JavaScript Test -->
<script>
$(function () {
  $('.js-popover').popover()
  $('.js-tooltip').tooltip()
  $('#tall-toggle').click(function () {
    $('#tall').toggle()
  })
})
</script>
  </body>
</html>
<?php
}
?>