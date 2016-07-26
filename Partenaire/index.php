<?php
session_start();
if (!isset($_SESSION['partenaire_id']) && !isset($_SESSION['name_partenaire']) && !isset($_SESSION['email_partenaire']) && !isset($_SESSION['num_tel_partenaire'])
 && !isset($_SESSION['num_ident_partenaire']) && !isset($_SESSION['solde_partenaire'])) {
    header('location:../partenaire.php');
  }else{
    require_once "includes/bootstrap.php";
$db = App::getDatabase();
    $user = $db->query("SELECT * FROM partenaire WHERE num_ident = ?", 
        [$_SESSION['num_ident_partenaire']])->fetch();
      if ($user) {
         $num_ident = $user->num_ident;
         $email = $user->email;
         $numTel = $user->num_tel;
         $soldePartenaire = $user->solde;
       }

?>

<?php // Changer email
  if (isset($_POST['change_email'])) {
    $db = App::getDatabase();
    extract($_POST);
    $errors = [];
    $success = [];
    if (!empty($_POST['new_email'])) {
      $password = htmlentities($_POST['password']);
            $password= base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($password), $password, MCRYPT_MODE_CBC, md5(md5($password))));

       $record = $db->query("SELECT email FROM partenaire WHERE email = ? AND password = ?", 
        [$email,$password])->fetch();
      if ($record) {
        $uniq = $db->query("SELECT email FROM partenaire WHERE email = ?", 
        [$_POST['new_email']])->fetch();
        if ($uniq) {
          $errors['email'] = "Cet adresse email existe déja dans la base de données";
        }else{
          $newEmail = htmlentities($_POST['new_email']);
          $db->query("UPDATE partenaire SET email = ? WHERE email = '$email'",[$newEmail]);
          $success[] = "Votre adresse email a bien été modifié le nouveau est $newEmail ";
        }
      }else{
        $errors[] = "Vos informations ne correspond pas";
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

       $record = $db->query("SELECT num_tel FROM partenaire WHERE num_tel = ? AND password = ?", 
        [$ancien_tel,$password])->fetch();
      if ($record) {
        $uniq = $db->query("SELECT num_tel FROM partenaire WHERE num_tel = ?", 
        [$new_num_tel])->fetch();
        if ($uniq) {
          $errors['num_tel'] = "Le numéro de téléphone existe déja dans la base de données";
        }else{
          
          $db->query("UPDATE partenaire SET num_tel = ? WHERE num_tel = '$ancien_tel'",[$new_num_tel]);
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


  $record = $db->query("SELECT password FROM partenaire WHERE password = ?", 
        [$ancien_password])->fetch();
  if ($record) {

    $db->query("UPDATE partenaire SET password = ? WHERE password = '$ancien_password'",[$nouveau_pass]);

    $success[] = "Votre mot de passe a bien été modifié";

  } else $errors[] = "Vérifiez votre ancien mot de passe" ;


      }else $errors[] = "Les nouveaux mots de passe ne sont pas identiques";

    }else $errors[] = "Veuillez SVP remplir tous les champs";
  }


?>

<?php
  if (isset($_POST['rechercher'])) {

    if (!empty($_POST['num_ident_dest'])) {
      $ident = strip_tags($_POST['num_ident_dest']);
      $Paiement = $db->query("SELECT * FROM paiements WHERE num_ident = ? AND num_ident_part = '$num_ident' ORDER BY date_paiement DESC", [$ident])->fetch();
    }
  }
?>

<?php // Paiement intégrale
   if (isset($_POST['payer'])) {

    $db = App::getDatabase();
    $errors = array();
    $success = array();
    extract($_POST);

    if (!empty($_POST['num_ident_client']) && !empty($_POST['desc_produit']) && !empty($_POST['prix_produit']) && !empty($_POST['password'])) {
      $password = htmlentities($_POST['password']);
      $password= base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($password), $password, MCRYPT_MODE_CBC, md5(md5($password))));

      $user = $db->query("SELECT * FROM clients WHERE num_ident = ? AND password = ?", 
        [$_POST['num_ident_client'],$password])->fetch();
      if ($user) {
        $num_ident_cl = $user->num_ident;
         $solde = $user->solde;
         $part = $db->query("SELECT * FROM partenaire WHERE num_ident = ?", [$num_ident])->fetch();
         if ($part) {
                     
            if ($solde >= $prix_produit) {
              $desc_produit = htmlentities($_POST['desc_produit']);
              $prix_produit = htmlentities($_POST['prix_produit']);
              
              $db->query("INSERT INTO paiements SET num_ident = ?, num_ident_part = ?, desc_produit = ?, prix_produit = ?",
     [$num_ident_cl,$num_ident, $desc_produit, $prix_produit]);

              $new_solde_user = $solde - $prix_produit;
              $new_solde_part = $soldePartenaire + $prix_produit;

              $db->query("UPDATE clients SET solde = ? WHERE num_ident = '$num_ident_cl'",[$new_solde_user]);
              $db->query("UPDATE partenaire SET solde = ? WHERE num_ident = '$num_ident'",[$new_solde_part]);

              $success[] = "Paiement términé, le produit payé est : $desc_produit, le prix du produit est $prix_produit numéro d'identification du client est $num_ident_client, votre nouveau solde est $new_solde_part";

            }else{
              $errors['solde'] = "Le solde du client est inférieur au produit a acheter";
            }
         }else{
          $errors['part'] = "Partenaire invalide";
         }
      }else{
        $errors['user'] = "Informations invalides";
      }
    }else{
      $errors[] = "Veuillez SVP remplir tous les champs";
    }

  }
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Epace Partenaire</title>
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
                <p>Vous pouvez administrez votre compte.<br></p>
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
    <label class="form-control" for="new_email">Nouveau Email</label>
    <input type="email" class="form-control" name="new_email" >
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

              <!-- fin -->

           
          </div>
     
                    <div class="col-lg-3">
                    <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Liste des paiements client</h3>
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
          <h4>Rechercher un paiement</h4>
          <form method="post" action="">
            <div class="form-group">
    <label class="form-control" for="ancien_password">Entrez le numéro d'identification du client</label>
    <input type="search" name="num_ident_dest"/>
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
    <div id="myModalPaie" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Formulaire de paiement intégrale</h4>
        </div>
        <div class="modal-body">
          <h4>Paiement intégrale</h4>

          <form action="" method="POST">
  <div class="form-group">
    <label class="form-control" for="num_ident_client">Numéro d'identification du client</label>
    <input type="text" name="num_ident_client" >
  </div> 
  <div class="form-group">
    <label class="form-control" for="desc_produit">Description du produit</label>
    <input type="text" name="desc_produit" >
  </div> 
  <div class="form-group">
    <label class="form-control" for="prix_produit">Prix produits</label>
    <input type="text" name="prix_produit" >
  </div> 
    <div class="form-group">
    <label class="form-control" for="password">Mot de passe</label>
    <input type="password"  name="password" >
  </div>
  <input type="submit" class="btn btn-success" name="payer" value="Effectuez le paiement">
</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Terminer</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">Annuler</button>
        </div>

      </div>
    </div>
  </div>
  <?php
  if (isset($_POST['rechercher']) && !empty($Paiement) ) {
    ob_start();
   ?>
  <div id="myModa" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Formulaire de recherche</h4>
        </div>
        <div class="modal-body">
          
           <table class="table table-striped">
              <thead>
              <tr>
                <th >Date</th>
                <th >Numéro du compte client</th>
                <th >Description du produit</th>
                <th >Prix produit</th>
              </tr>
              </thead>
              <tbody>
               <tr>
                <td class="success"><?= $Paiement->date_paiement ?></td>
                <td class="warning"><?= $Paiement->num_ident ?></td>
                <td class="info"><?= $Paiement->desc_produit ?></td>
                <td class="info"><?= $Paiement->prix_produit ?></td>
              </tr>
              </tbody>
            </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Terminer</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">Annuler</button>
        </div>

      </div>
    </div>
  </div>
  <button class="btn btn-warnig" data-toggle="modal" data-target="#myModa">
    Afficher le resultat
  </button><br/><br/>
   <?php
   $resultat = ob_get_clean();
   echo $resultat;
   }
   ?>
        

  <button class="btn btn-success" data-toggle="modal" data-target="#myModalRe">
    Rechercher un paiement 
  </button><br/><br/>
  <button class="btn btn-primary" data-toggle="modal" data-target="#myModalPaie">
     Valider un paiement
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