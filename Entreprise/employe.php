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

  $RecEmploye = $db->query("SELECT * FROM employe WHERE entreprise = ?", [$entreprise->name])->fetchAll();


?>

<?php
  // Enregistrer un employé
  if (isset($_POST['save'])) {
    $errors = array();
    $success = array();
    $entrep = $entreprise->name;
    if (empty($_POST['name']) && empty($_POST['num_ident']) && empty($_POST['email']) && empty($_POST['num_tel']) && empty($_POST['fonction']) && empty($_POST['salaire'])) {
      $errors[] = "Veuillez svp remplir tous les champs";
    }

     //Validation Name
  $name = htmlentities($_POST['name']);
  if (empty($name) || !preg_match('/^[a-zA-Z ]+$/', $name)) {
    
    $errors['name'] = "Votre nom doit être alphanumérique (a-zA-Z)";

  }
    //Validation email
  $email = htmlentities($_POST['email']);
  if (empty($email) || !filter_var($email,FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Votre email n'est pas valide";
  }else{
    $record = $db->query("SELECT id FROM employe WHERE email = ?", [$email])->fetch();
    if ($record) {
      $errors['email'] = "Cette adresse email est dèja utilisé pour un autre compte";
    }
  }

    
   //Validation Numéro identification
  $num_ident = htmlentities($_POST['num_ident']);

    if (empty($num_ident) || strlen($num_ident) != 13 || !preg_match('/^[0-9]+$/', $num_ident)) {
      $errors['num_ident'] = "Le numéro d'identification doit être numérique";
    }else{
    $record = $db->query("SELECT id FROM employe WHERE num_ident = ?", [$num_ident])->fetch();
    if ($record) {
      $errors['num_ident'] = "Le numéro d'identification est dèja utilisé pour un autre compte";
    }
  }


         //Validation Numéro de téléphone
  $num_tel = htmlentities($_POST['num_tel']);
   if (empty($num_tel) || strlen($num_tel) != 9 || !preg_match('/^[0-9]+$/', $num_tel)) {
      $errors['num_tel'] = "Votre numéro de téléphone doit être numérique et contenir 9 chiffres(770001122).";
    }else{
    $record = $db->query("SELECT id FROM employe WHERE num_tel = ?", [$num_tel])->fetch();
    if ($record) {
      $errors['num_tel'] = "Le numéro de téléphone est dèja utilisé pour un autre compte";
    }
  }
  
       //Validation fonction
  $fonction = htmlentities($_POST['fonction']);
  if (empty($fonction) || !preg_match('/^[a-zA-Z ]+$/', $fonction)) {
    
    $errors['fonction'] = "La fonction doit être alphabétique (a-zA-Z)";

  }

       //Validation salaire
  $salaire = htmlentities($_POST['salaire']);
  if (empty($salaire) || !preg_match('/^[0-9 ]+$/', $salaire)) {
    
    $errors['salaire'] = "Votre salire doit être numérique (0-9)";

  }

  if (empty($errors)) {
        
    $db->query("INSERT INTO employe SET name = ?, email = ?,num_tel =?, num_ident = ?, salaire = ?, entreprise = ?, fonction = ?",
     [$name, $email, $num_tel, $num_ident, $salaire, $entrep, $fonction]);    

      $success[] = "Enregistrement términè le nom de l'employé est $name, son numéro d'identification est $num_ident, sa fonction est $fonction et son salaire est $salaire.";  
     }
    }

?>

<?php 
  
$dateMySQL = $entreprise->date_inscrit;
//objet DateTime correspondant :
$date = new DateTime($dateMySQL);

//affichage de la date au format francophone:

?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Epace Entreprise | Employés</title>
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
                <h2><?= "Bienvenue ".$entreprise->name; ?></h2>
                <p>Vous pouvez enregistrer modifier ou supprimer des employés.<br></p>
              </div>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-lg-12">
            <h2>Gérez la liste de vos employés</h2>
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
                           <div class="col-lg-4">

           <!-- Début html & css-->
           <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Rechercher un employé</h3>
                </div>
                <div class="panel-body">
                <form method="post" action="">
                  <div class="form-group">
    <label class="form-control" for="email">Numéro identification de l'employé</label>
    <input type="search" name="num_employe" >
  </div> 
  <input type="submit" class="btn btn-primary" name="rechercher" value="Rechercher">
                </form>
                </div>
              </div>

              <!-- fin Informations-->

           
          </div>
          <div class="col-lg-4">

           <!-- transaction compte à compte-->
           <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Ajouter un employé</h3>
                </div>
                <div class="panel-body">

                  <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Formulaire d'enregistrement des employés</h4>
        </div>
        <div class="modal-body">
          <h4>Enregistrer un employé</h4>
           <form method="post" action="" >
          <div class="form-group">
    <label class="form-control" for="name">Prénom et nom de l'employé</label>
    <input type="text" name="name" >
  </div>
	<div class="form-group">
		<label class="form-control" for="num_ident">Numéro d'identification de l'employé</label>
		<input type="text" name="num_ident" >
	</div> 
  <div class="form-group">
    <label class="form-control" for="email">Email</label>
    <input type="text" name="email" >
  </div> 
  <div class="form-group">
    <label class="form-control" for="num_tel">Numéro de téléphone</label>
    <input type="text" name="num_tel" >
  </div> 
		<div class="form-group">
		<label class="form-control" for="fonction">Fonction</label>
		<input type="text"  name="fonction" >
	</div>
  <div class="form-group">
    <label class="form-control" for="salaire">Salaire</label>
    <input type="text"  name="salaire" >
  </div>
	<input type="submit" class="btn btn-success" name="save" value="Enregistrer">
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
    Ajouter un employé
  </button><br/><br/>
 
  <div id="tall" style="display: none;">
  </div>
                </div>
              </div>

              <!-- fin html & css-->

           
          </div>
 
                    <div class="col-lg-4">
                    <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Liste des employés</h3>
                </div>
                <div class="panel-body">
              <div id="myModalRe" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
    <div class="modal-dialog" style="width:1000px;">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Liste des employés</h4>
        </div>
          <div class="modal-body">
        <?php if ($RecEmploye){
                ?>
            <h4>Liste des employés</h4>
            <div class="table-responsive">
            <table class="table table-striped">
              <thead>
              <tr>
                <th >Date</th>
                <th >Nom et prénom</th>
                <th >Email</th>
                <th >Téléphone</th>
                <th >Identification</th>
                <th >Fonction</th>
                <th >Salaire</th>
              </tr>
              </thead>
              <tbody>
               
    <?php foreach ($RecEmploye as $emploi): ?>
            <tr>
                <td class="success"><?= $emploi->date_enregistre; ?></td>
                <td class="warning"><?= $emploi->name; ?></td>
                <td class="info"><?= $emploi->email; ?></td>
                <td class="info"><?= $emploi->num_tel; ?></td>
                <td class="info"><?= $emploi->num_ident; ?></td>
                <td class="info"><?= $emploi->fonction; ?></td>
                <td class="info"><?= $emploi->salaire; ?></td>
            </tr>  
<?php endforeach; ?>

              </tbody>
            </table>
          </div><br/>
          <hr/>
<?php
                }else{
                  echo "<div class='alert alert-warning'>";
                  echo "Vous n'avez pas encore effectuer de retrait";
                  echo "</div>";

                  }  ?>
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Terminer</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">Annuler</button>
        </div>

      </div>
    </div>
  </div>
  <button class="btn btn-success" data-toggle="modal" data-target="#myModalRe">
    Liste des employés 
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