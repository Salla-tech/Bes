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

   $lastretrait = $db->query("SELECT * FROM admin_retrait_cash WHERE num_ident = ? ORDER BY date_retrait DESC", [$num_ident])->fetch();
    $lastretraitCompte = $db->query("SELECT * FROM retraits WHERE num_ident_admin = ? ORDER BY date_retrait DESC", [$num_ident])->fetch();
       ?>


<?php
// Retrait client

if (isset($_POST['retrait_client'])) {
  $errors = [];
  $success = [];
  if (!empty($_POST['num_ident']) && !empty($_POST['montant']) && !empty($_POST['password'])) {
    $password = htmlentities($_POST['password']);
      $password= base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($password), $password, MCRYPT_MODE_CBC, md5(md5($password))));
    $admin = $db->query("SELECT * FROM adminis WHERE num_ident_admin = ? AND password_admin = ?",  [$num_ident,$password])->fetch();
    if ($admin) {
      $soldeAdministrateur = $admin->solde_admin;
      $num_identAdministrateur = $admin->num_ident_admin;
      $montant = strip_tags($_POST['montant']);

       $user = $db->query("SELECT * FROM clients WHERE num_ident = ?", [$_POST['num_ident']])->fetch();

      if ($user) {
        $num_ident = $user->num_ident;
         $solde = $user->solde;
         if ($solde >= $montant) {
           $new_solde_admin = $soldeAdministrateur + $montant;
           $new_solde_user = $solde - $montant;

            $db->query("INSERT INTO retraits SET num_ident_admin = ?, num_ident_dest = ?, montant = ?",
     [$num_identAdministrateur, $num_ident, $montant]);

          $db->query("UPDATE adminis SET solde_admin = ? WHERE num_ident_admin = '$num_identAdministrateur'",[$new_solde_admin]);
           $db->query("UPDATE clients SET solde = ? WHERE num_ident = '$num_ident'",[$new_solde_user]);

           $success[] = "Retraits compte client términé, le montant retiré est $montant, numéro du client est $num_ident. Votre nouveau solde est $new_solde_admin";


         }else{
          $errors['solde'] = "Le solde du compte client est inférieur au montant a rechargé";
         }
  
        }else{
      $errors['user'] = "Utilisateur invalide";
    }

    }else{
      $errors['admin'] = "Informations invalide";
    }
    
  }else{
    $errors[] = "Veuillez svp remplir tous les champs";
  }
}

?>

<?php
  if (isset($_POST['retrait_cash'])) {
    if (!empty($_POST['code']) && !empty($_POST['montant']) && !empty($_POST['password'])) {
      
       $password = htmlentities($_POST['password']);
      $password= base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($password), $password, MCRYPT_MODE_CBC, md5(md5($password))));
    $admin = $db->query("SELECT * FROM adminis WHERE num_ident_admin = ? AND password_admin = ?",  [$num_ident,$password])->fetch();
    if ($admin) {
      $soldeAdministrateur = $admin->solde_admin;
      $num_identAdministrateur = $admin->num_ident_admin;
      $montant = strip_tags($_POST['montant']);
      $code = strip_tags($_POST['code']);
       $transaction = $db->query("SELECT * FROM transaction_cash WHERE code = ?", [$code])->fetch();
       $transact = $db->query("SELECT * FROM transaction_cash_admin WHERE code = ?", [$code])->fetch();
       $transactretir = $db->query("SELECT * FROM admin_retrait_cash WHERE code = ?", [$code])->fetch();

       if ($transactretir) {
         $errors[] = "La transaction est dejà retiré";
       }

      if ($transaction) {
        $code_trans = $transaction->code;
         $montant_trans = $transaction->montant_trans;
         if ($code_trans == $code) {
          $name_trans = $transaction->name;
           $new_solde_admin = $soldeAdministrateur + $montant;

            $db->query("INSERT INTO admin_retrait_cash SET num_ident = ?, name = ?, montant = ?, code = ?",
     [$num_identAdministrateur, $name_trans, $montant, $code_trans]);

            $etat = "Retiré";

          $db->query("UPDATE adminis SET solde_admin = ? WHERE num_ident_admin = '$num_identAdministrateur'",[$new_solde_admin]);
           $db->query("UPDATE transaction_cash SET code = ? WHERE code = '$code'",[$etat]);

           $success[] = "Retraits cash términé, le montant retiré est $montant, nom et prénom du client est $name_trans. Votre nouveau solde est $new_solde_admin";


         }else{
          $errors['code'] = "Le code n'existe pas dans la base de donnée";
         }
  
        }elseif ($transact) {
        $code_trans = $transact->code;
         $montant_trans = $transact->montant_trans;
         if ($code_trans == $code && $montant == $montant_trans) {
          $name_trans = $transact->name;
           $new_solde_admin = $soldeAdministrateur + $montant;

            $db->query("INSERT INTO admin_retrait_cash SET num_ident = ?, name = ?, montant = ?, code = ?",
     [$num_identAdministrateur, $name_trans, $montant, $code_trans]);

          $db->query("UPDATE adminis SET solde_admin = ? WHERE num_ident_admin = '$num_identAdministrateur'",[$new_solde_admin]);
           $db->query("UPDATE transaction_cash_admin SET code = ? WHERE code = '$code'",[null]);

           $success[] = "Retraits cash términé, le montant retiré est $montant, nom et prénom du client est $name_trans. Votre nouveau solde est $new_solde_admin";


         }else{
          $errors['solde'] = "Le solde du compte client est inférieur au montant";
         }
  
        

        }else{
      $errors[] = "La transaction est inéxistante";
    }

    }else{
      $errors['admin'] = "Informations invalide";
    }



    }
  }


?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Epace Administrateur | Retraits</title>
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
                <p>Vous pouvez effectuer des retraits.<br></p>
              </div>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-lg-12">
            <h2>Retrait cash et compte client</h2>
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
                  <h3 class="panel-title">Retrait compte client</h3>
                </div>
                <div class="panel-body">

         

  <div id="myModalT" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Formulaire de retrait de compte client</h4>
        </div>
        <div class="modal-body">
          <h4>Retrait client</h4>

             <form action="" method="POST">
   <div class="form-group">
    <label class="form-control" for="num_ident">Numéro du compte client</label>
    <input type="text" name="num_ident" >
  </div> 
  <div class="form-group">
    <label class="form-control" for="montant">Montant à retirer</label>
    <input type="text" name="montant" >
  </div> 
    <div class="form-group">
    <label class="form-control" for="password">Mot de passe</label>
    <input type="password"  name="password" >
  </div>
  <input type="submit" class="btn btn-success" name="retrait_client" value="Effectuez le retrait">
</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Terminer</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">Annuler</button>
        </div>

      </div>
    </div>
  </div>
   <div id="myModalR" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Formulaire de retrait de cash</h4>
        </div>
        <div class="modal-body">
          <h4>Retrait cash</h4>

             <form action="" method="POST">
   <div class="form-group">
    <label class="form-control" for="code">Code de la transaction</label>
    <input type="text" name="code" >
  </div> 
  <div class="form-group">
    <label class="form-control" for="montant">Montant à retirer</label>
    <input type="text" name="montant" >
  </div> 
    <div class="form-group">
    <label class="form-control" for="password">Mot de passe</label>
    <input type="password"  name="password" >
  </div>
  <input type="submit" class="btn btn-success" name="retrait_cash" value="Effectuez le retrait">
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
    Retrait compte client
  </button><br/><br/>
  <button class="btn btn-primary" data-toggle="modal" data-target="#myModalR">
    Retrait transaction cash
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
                  <h3 class="panel-title">Dernières Retraits</h3>
                </div>
                <div class="panel-body">
                <div id="myModalRe" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Retraits transacation cash</h4>
        </div>
        <div class="modal-body">
             <h4>Retraits cash</h4>
            <div class="table-responsive">
      <form id="form2" name="form2" method="post" action="">
            <table class="table table-striped">
              <thead>
              <tr>
                <th >Nom et prénom client</th>
                <th >Montant</th>
                <th >Code </th>
                <th >Date</th>
              </tr>
              </thead>
              <tbody>
               <tr>
                <td class="success"><?= $lastretrait->name ?></td>
                <td class="warning"><?= $lastretrait->montant ?></td>
                <td class="primary"><?= $lastretrait->code ?></td>
                <td class="info"><?= $lastretrait->date_retrait ?></td>
              </tr>
              </tbody>
            </table>
          </form>
          </div>
          <br>
          <hr/>
            <h4>Retraits compte client</h4>
            <div class="table-responsive">
      <form id="form2" name="form2" method="post" action="">
            <table class="table table-striped">
              <thead>
              <tr>
                <th >Numéro identification client</th>
                <th >Montant</th>
                <th >Date</th>
              </tr>
              </thead>
              <tbody>
               <tr>
                <td class="success"><?= $lastretraitCompte->num_ident_dest ?></td>
                <td class="warning"><?= $lastretraitCompte->montant ?></td>
                <td class="info"><?= $lastretraitCompte->date_retrait ?></td>
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
    Dernière retrait 
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