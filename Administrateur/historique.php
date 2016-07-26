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
         $soldeUser = $admin->solde_admin;
         $emailAdmin = $admin->email_admin;
         $numTel = $admin->num_tel_admin;
       }
$dateMySQL = $admin->date_inscrit;
//objet DateTime correspondant :
$date = new DateTime($dateMySQL);

//affichage de la date au format francophone:


?>

<?php // Historique des transactions cash 

  $HistoCash = $db->query("SELECT * FROM transaction_cash_admin WHERE num_ident = ? ORDER BY date_trans DESC", [$num_ident])->fetchALL();
   
?>


<?php // Historique rechargements et retraits

$HistoRecharge = $db->query("SELECT * FROM rechargement WHERE num_ident_admin = ? ORDER BY date_recharge DESC", [$num_ident])->fetchALL();

$HistoRetraits = $db->query("SELECT * FROM retraits WHERE num_ident_admin = ? ORDER BY date_retrait DESC", [$num_ident])->fetchALL();
 

?>

<?php // Historique des paiements
  
 $HistoRetraitsCash = $db->query("SELECT * FROM admin_retrait_cash WHERE num_ident = ? ORDER BY date_retrait DESC", [$num_ident])->fetchALL();
 
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Epace Administrateur | Historique</title>
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
                <p>Vous pouvez consulter l'historique de votre compte.<br></p>
              </div>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-lg-12">
            <h3>Historique des rechargements, retraits, transactions</h3>
            
        </div>
        <div class="row">
          <div class="col-lg-12">         
        
        <div class="row">
          <div class="col-lg-4">

           <!-- Début historique transactions-->
           <div class="panel panel-warning">
                <div class="panel-heading">
                  <h3 class="panel-title">Historique des Rechargements</h3>
                </div>
                <div class="panel-body">

                  <div id="myModalRechar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Historiques des Rechargements</h4>
        </div>
        <div class="modal-body">
        <?php if ($HistoRecharge){
                ?>
            <h4>Liste des Rechargements</h4>
            <div class="table-responsive">
            <table class="table table-striped">
              <thead>
              <tr>
                <th >Date</th>
                <th >Numéro du destinataire</th>
                <th >Montant</th>
              </tr>
              </thead>
              <tbody>
               
    <?php foreach ($HistoRecharge as $historecharge): ?>
            <tr>
                <td class="success"><?= $historecharge->date_recharge ?></td>
                <td class="warning"><?= $historecharge->num_ident_dest ?></td>
                <td class="info"><?= $historecharge->montant ?></td>
            </tr>  
<?php endforeach; ?>

              </tbody>
            </table>
          </div><br/>
          <hr/>
<?php
                }else{
                  echo "<div class='alert alert-warning'>";
                  echo "Vous n'avez pas encore effectuez de Rechargement";
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


  <button class="btn btn-warning" data-toggle="modal" data-target="#myModalRechar">
   Rechargement clients
  </button><br/><br/>
 
  <div id="tall" style="display: none;">
  </div>
                </div>
              </div>

              <!-- fin historique Rechargements-->

           
          </div>



          <div class="col-lg-4">

           <!-- Début historique transactions-->
           <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Historique des transactions</h3>
                </div>
                <div class="panel-body">

                  <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Historiques des transactions cash</h4>
        </div>
        <div class="modal-body">
        <?php if ($HistoCash){
                ?>
            <h4>Liste des Transactions</h4>
            <div class="table-responsive">
            <table class="table table-striped">
              <thead>
              <tr>
                <th >Date</th>
                <th >Numéro du destinataire</th>
                <th >Prénom et nom</th>
                <th >Montant</th>
                <th >Code</th>
              </tr>
              </thead>
              <tbody>
               
    <?php foreach ($HistoCash as $histocmpt): ?>
            <tr>
                <td class="success"><?= $histocmpt->date_trans ?></td>
                <td class="warning"><?= $histocmpt->num_tel_dest ?></td>
                <td class="warning"><?= $histocmpt->name ?></td>
                <td class="info"><?= $histocmpt->montant_trans ?></td>
                <td class="info"><?= $histocmpt->code ?></td>
            </tr>  
<?php endforeach; ?>

              </tbody>
            </table>
          </div><br/>
          <hr/>
<?php
                }else{
                  echo "<div class='alert alert-warning'>";
                  echo "Vous n'avez pas encore effectuez de transactions";
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

  
  <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">
   Transactions Cash
  </button><br/><br/>
  <div id="tall" style="display: none;">
  </div>
                </div>
              </div>

              <!-- fin historique transactions-->

           
          </div>
 
                  <div class="col-lg-4">

           <!-- Début historique Paiements-->
           <div class="panel panel-success">
                <div class="panel-heading">
                  <h3 class="panel-title">Retrait</h3>
                </div>
                <div class="panel-body">

 
  <div id="myModalretrait" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Historique des retraits cash</h4>
        </div>
          <div class="modal-body">
        <?php if ($HistoRetraitsCash){
                ?>
            <h4>Liste des Retraits</h4>
            <div class="table-responsive">
            <table class="table table-striped">
              <thead>
              <tr>
                <th >Date</th>
                <th >Name</th>
                <th >Montant</th>
                <th >Code</th>
              </tr>
              </thead>
              <tbody>
               
    <?php foreach ($HistoRetraitsCash as $historetrai): ?>
            <tr>
                <td class="success"><?= $historetrai->date_retrait ?></td>
                <td class="warning"><?= $historetrai->name ?></td>
                <td class="warning"><?= $historetrai->montant ?></td>
                <td class="info"><?= $historetrai->code ?></td>
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
    <div id="myModalretraitC" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Historique des retraits compte client</h4>
        </div>
          <div class="modal-body">
        <?php if ($HistoRetraits){
                ?>
            <h4>Liste des Retraits</h4>
            <div class="table-responsive">
            <table class="table table-striped">
              <thead>
              <tr>
                <th >Date</th>
                <th >Numéro d'identification client</th>
                <th >Montant</th>
              </tr>
              </thead>
              <tbody>
               
    <?php foreach ($HistoRetraits as $historetrai): ?>
            <tr>
                <td class="success"><?= $historetrai->date_retrait ?></td>
                <td class="warning"><?= $historetrai->num_ident_dest ?></td>
                <td class="info"><?= $historetrai->montant ?></td>
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

   <button class="btn btn-success" data-toggle="modal" data-target="#myModalretrait">
   Retraits Cash
  </button><br/><br/>
  <button class="btn btn-success" data-toggle="modal" data-target="#myModalretraitC">
   Retraits Compte
  </button><br/><br/>

  <div id="tall" style="display: none;">
  </div>
                </div>
              </div>

              <!-- fin historique Paiements-->

           
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