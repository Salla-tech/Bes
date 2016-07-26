<?php
session_start();
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['name_admin']) && !isset($_SESSION['email_admin']) && !isset($_SESSION['num_tel_admin'])
 && !isset($_SESSION['num_ident_admin']) && !isset($_SESSION['solde_admin'])) {
    header('location:../administrateur.php');
  }else{
    require_once "includes/bootstrap.php";
$db = App::getDatabase();
    $admin = $db->query("SELECT * FROM adminis WHERE id = ?", 
        [$_GET['id']])->fetch();
      if ($admin) {
         $num_ident = $admin->num_ident_admin;
         $emailAdmin = $admin->email_admin;
         $numTel = $admin->num_tel_admin;
         $soldeAdmin = $admin->solde_admin;

         $dateMySQL = $admin->date_inscrit;
//objet DateTime correspondant :
$date = new DateTime($dateMySQL);

//affichage de la date au format francophone:

       }

       $lastTrans_cash = $db->query("SELECT * FROM transaction_cash_admin WHERE num_ident = ? ORDER BY date_trans DESC", [$num_ident])->fetch();
       ?>


<?php
// Transaction compte à cash

if (isset($_POST['transfer_cash'])) {
  require_once 'includes/function.php';

  $errors = [];
  $success = [];
  extract($_POST);
  if (!empty($_POST['name_dest']) && !empty($_POST['tel_dest']) && !empty($_POST['montant']) && !empty($_POST['password'])) {
    $password = htmlentities($_POST['password']);
      $password= base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($password), $password, MCRYPT_MODE_CBC, md5(md5($password))));
      $user = $db->query("SELECT * FROM adminis WHERE num_ident_admin = ? AND password_admin = ?", 
        [$_SESSION['num_ident_admin'],$password])->fetch();

      if ($user) {
        $num_ident = $user->num_ident_admin;
         $solde = $user->solde_admin;


          if ($solde >= $montant && $_SESSION['num_ident_admin'] === $user->num_ident_admin) {
             $code = str_random(8);
              
      $db->query("INSERT INTO transaction_cash_admin SET num_ident = ?, num_tel_dest = ?, name = ?, montant_trans = ?, code = ?",
     [$num_ident, $_POST['tel_dest'], $_POST['name_dest'], $montant, $code]);

              $new_solde_user = $solde - $montant;
              
              $db->query("UPDATE adminis SET solde_admin = ? WHERE num_ident_admin = '$num_ident'",[$new_solde_user]);
              
              $success[] = "Transaction términé, le montant de la transaction est  $montant, nom et prénom du destinataire sont $name_dest,le numéro de téléphone est $tel_dest, le code de la transaction est $code. Votre nouveau solde est $new_solde_user";

            }else{
              $errors['solde'] = "Votre solde est inférieur au montant que vous voulez transferer";
            }
      

      }else{
        $errors['users'] = " Vos informations sont invalide";
      }
  }else{
    $errors[] = "Veuillez svp remplir tous les champs";
  }
}

  




?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Epace Administrateur | Transactions</title>
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
                <h2><?= "Bienvenue ".$_SESSION['name_admin']; ?></h2>
                <p>Vous pouvez effectuer des transactions compte à cash.<br></p>
              </div>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-lg-12">
            <h2>Transactions compte à cash</h2>
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
                  <li>Prénom et Nom : <?= $admin->name_admin; ?></li>
                  <li>Adresse email : <?= $admin->email_admin; ?></li>
                  <li>Numéro d'identification : <?= $admin->num_ident_admin; ?></li>
                  <li>Numéro de Téléphone : <?= $admin->num_tel_admin; ?></li>
                  <li>Solde : <?php if ($admin->solde_admin <= 0) {
                    $soldeAdmin = "Veuillez rechargez votre compte";
                    echo $soldeAdmin;
                  }else{echo $admin->solde_admin;}  ?></li>
                  <li>Vous vous êtes inscrit à cette date : <?php echo $date->format('d/m/Y H:i:s'); ?></li>
                </ul>
                </div>
              </div>

              <!-- fin Informations-->

           
          </div>
          <div class="col-lg-4">

           <!-- transaction compte à compte-->
           <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Effectuez une transaction</h3>
                </div>
                <div class="panel-body">

         

  <div id="myModalT" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Formulaire de transaction compte à cash</h4>
        </div>
        <div class="modal-body">
          <h4>Transaction compte à cash</h4>

             <form action="" method="POST">
  <div class="form-group">
    <label class="form-control" for="name_dest">Prénom et Nom du destinataire</label>
    <input type="text" name="name_dest" >
  </div> 
   <div class="form-group">
    <label class="form-control" for="tel_dest">Téléphone du destinataire</label>
    <input type="text" name="tel_dest" >
  </div> 
  <div class="form-group">
    <label class="form-control" for="montant">Montant à transférer</label>
    <input type="text" name="montant" >
  </div> 
    <div class="form-group">
    <label class="form-control" for="password">Mot de passe</label>
    <input type="password"  name="password" >
  </div>
  <input type="submit" class="btn btn-success" name="transfer_cash" value="Effectuez la transaction">
</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Terminer</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">Annuler</button>
        </div>

      </div>
    </div>
  </div>
  <button class="btn btn-primary" data-toggle="modal" data-target="#myModalT">
    Transaction compte à cash
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
                  <h3 class="panel-title">Dernières Transactions</h3>
                </div>
                <div class="panel-body">
                <div id="myModalRe" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Compte à cash</h4>
        </div>
        <div class="modal-body">
             <h4>Compte à cash</h4>
            <div class="table-responsive">
      <form id="form2" name="form2" method="post" action="">
            <table class="table table-striped">
              <thead>
              <tr>
                <th >Nom</th>
                <th >Numéro Tél</th>
                <th >Montant</th>
                <th >Code</th>
                <th >Date</th>
                <th class="info">Modifier</th>
                <th class="warning">Supprimer</th>
              </tr>
              </thead>
              <tbody>
               <tr>
                <td class="success"><?= $lastTrans_cash->name ?></td>
                <td class="warning"><?= $lastTrans_cash->num_tel_dest ?></td>
                <td class="info"><?= $lastTrans_cash->montant_trans ?></td>
                <td class="warning"><?= $lastTrans_cash->code ?></td>
                <td class="info"><?= $lastTrans_cash->date_trans ?></td>
                <td><a href="#">
                <span onmouseover="this.style.color='green'" onmouseout="this.style.color=''">Modifier</span> </a></td>
                <td ><a href="#">
                <span onmouseover="this.style.color='red'" onmouseout="this.style.color=''">Supprimer</span> </a></td>
              </tr>
              </tbody>
            </table>
          </form>
          </div>
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Terminer</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">Annuler</button>
        </div>

      </div>
    </div>
  </div>
  <button class="btn btn-success" data-toggle="modal" data-target="#myModalRe">
    Dernières transactions 
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