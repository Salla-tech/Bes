<?php
session_start();
if (!isset($_SESSION['partenaire_id']) && !isset($_SESSION['name_partenaire']) && !isset($_SESSION['email_partenaire']) && !isset($_SESSION['num_tel_partenaire'])
 && !isset($_SESSION['num_ident_partenaire']) && !isset($_SESSION['solde_partenaire'])) {
    header('location:../partenaire.php');
  }else{
    require_once "includes/bootstrap.php";
$db = App::getDatabase();
     $user = $db->query("SELECT * FROM clients WHERE num_ident = ?", 
        [$_SESSION['num_ident']])->fetch();
      if ($user) {
         $num_ident = $user->num_ident;
         $email = $user->email;
         $numTel = $user->num_tel;
         $soldePartenaire = $user->solde;
       }

       ?>



<?php
  if (isset($_POST['envoyer'])) {
    $errors= array();
    $success = array();
    extract($_POST);
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['message'])) {
      $message = htmlspecialchars(trim(mysql_real_escape_string($_POST['message'])));

      if (!preg_match('/^[a-zA-Z ]+$/', $_POST['name'])) {
        $errors[] = "Votre nom et votre prénom ne doit contenir que des lettres";
      }elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Votre email est invalide";
      }else{
        $name = strip_tags($_POST['name']);
        $email = strip_tags($_POST['email']);
    $db->query("INSERT INTO contact SET name = ?, email = ?, message = ?",[$name, $email, $message]);

        $success[] = "Votre message à bien été envoyé vous serez répondu dans les meilleures délais";
      }


      
    }else $errors[] = "Veuillez svp remplir tous les champs";
  }

?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Epace Partenaire | Contact</title>
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
                <h2><?= "Bienvenue ".$_SESSION['name_partenaire']; ?></h2>
                <p>Vous pouvez nous contacter.<br></p>
              </div>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-lg-12">
            <h2>Contact</h2>
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

           <!-- Début html & css-->
           <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Informations de votre compte</h3>
                </div>
                <div class="panel-body">
                 <ul>
                  <li>Prénom et Nom : <?= $_SESSION['name_partenaire']; ?></li>
                  <li>Adresse email : <?= $email; ?></li>
                  <li>Numéro d'identification : <?= $_SESSION['num_ident_partenaire']; ?></li>
                  <li>Numéro de Téléphone : <?= $numTel; ?></li>
                  <li>Solde : <?php if ($soldePartenaire <= 0) {
                    $soldePartenaire = "Veuillez recharger votre compte";
                    echo $soldePartenaire;
                  }else{echo $soldePartenaire;}  ?></li>
                  <li>Vous vous êtes inscrit à cette date : <?= $_SESSION['date_inscrit_partenaire']; ?></li>
                </ul>
                </div>
              </div>

              <!-- fin Informations-->

           
          </div>
          <div class="col-lg-7">

           <!-- transaction compte à compte-->
           <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Contacter-nous</h3>
                </div>
                <div class="panel-body">

  <div id="myModalC" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Formulaire de contact</h4>
        </div>
        <div class="modal-body">
          <h4>Contact</h4>

             <form action="" method="POST">
  <div class="form-group">
    <label class="form-control" for="name">Prénom et Nom</label>
    <input type="text" name="name" >
  </div> 
   <div class="form-group">
    <label class="form-control" for="email">Email</label>
    <input type="text" name="email" >
  </div> 
  <div class="form-group">
    <label class="form-control" for="message">Message</label>
    <textarea cols="60" rows="5" name="message">
      
    </textarea>
  </div> 
  <input type="submit" class="btn btn-success" name="envoyer" value="Envoyer">
</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Terminer</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">Annuler</button>
        </div>

      </div>
    </div>
  </div>

  <button class="btn btn-primary" data-toggle="modal" data-target="#myModalC">
    Formulaire de contact
  </button><br/><br/>
  <div id="tall" style="display: none;">
  </div>
                </div>
              </div>

              <!-- fin html & css-->

           
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