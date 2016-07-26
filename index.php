<?php require_once "includes/_header.php"; ?>

  <body>

  <?php require_once "includes/_nav.php";?>

    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>Banque Electronique du Sénégal</h1>
        <p>La Banque Electronique du Sénégal(BES) est une plateforme de transaction d’argent et de paiement en ligne. A travers le système que nous offrons, 
        vous aurez la possibilité de transférer de l’argent, de payer vos factures, de payer la scolarité de vos enfants,
         de payer vos ordonnances au niveau des pharmacies,
          d’acheter du carburant, de faire votre réservation au niveau des hôtels,des agences de voyages et au port autonome,
           d’acheter au niveau des centres commerciaux, de payer vos achats au niveau des supermarchés, de payer votre addition au niveau des restaurants, d’acheter ou de louer au niveau des agences automobile.</p>




        <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Banque électonique du Sénégal</h4>
        </div>
        <div class="modal-body">
          <h4>A propos de l'inscription</h4>
          <p>Pour vous inscrire vous devez obligatoirement utiliser les informations de votre carte d’identité nationale, votre email et votre numéro de téléphone. </p>

          <p><a href="#" role="button" class="btn btn-success js-popover" title="Compte Client, Partenaire et Admin" data-content="Vous pouvez ouvrir un compte client, partenaire ou administrateur" data-placement="left">Les types de comptes</a></p>

          <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-primary">
              <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="js-tooltip" title="Compte client cliquez ici!">
                    Compte Client
                  </a>
                </h4>
              </div>
              <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                 Pour ouvrir un compte client, il faut vous inscrire au niveau de la partie client. Ensuite vous devez
                  recharger votre compte au niveau des administrateurs ou de notre agence.
Pour le rechargement de votre compte il faut amener votre carte d’identité pour l’identification du numéro.
 Après rechargement vous pouvez vérifier le rechargement au niveau de votre compte client dans la rubrique ou il est
  marqué solde.
Ainsi vous aurez la possibilité de transférer de l’argent de compte à compte et de compte à cash et de faire l’ensemble 
des opérations que vous offrent la plateforme.
A travers votre compte vous pouvez consulter l’historique de votre compte depuis votre inscription c’est-à-dire 
les transactions, les paiements et les rechargements.
Vous pouvez aussi domicilier votre salaire chez nous pour pouvoir bénéficier des prêts qui sont payables à long terme.

                </div>
              </div>
            </div>
             <div class="panel panel-danger">
              <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title">
                  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" class="js-tooltip" title="Compte client cliquez ici!">
                    Compte Administrateur
                  </a>
                </h4>
              </div>
              <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
                <div class="panel-body">
                 Pour un compte administrateur vous devez vous inscrire au niveau de la partie administrateur, ensuite vous devez recharger votre compte au niveau de notre agence.
Pour le rechargement de votre compte il faut amener votre carte d’identité pour l’identification du numéro. Après rechargement vous pouvez vérifier le rechargement au niveau de votre compte admin dans la rubrique ou il est marqué solde.
A travers votre compte vous pouvez recharger les comptes client, faire des transactions vers cash,  de faire des retraits cash pour les clients et pour les partenaires. Vous aurez la possibilité de consulter l’historique de votre compte et de pouvoir faire toutes les fonctionnalités que la plateforme vous offre.

                </div>
              </div>
            </div>
            <div class="panel panel-info">
              <div class="panel-heading" role="tab" id="headingTree">
                <h4 class="panel-title">
                  <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTree" aria-expanded="false" aria-controls="collapseTwo" class="js-tooltip" title="Comte partenaire cliquez ici">
                    Compte Partenaire
                  </a>
                </h4>
              </div>
              <div id="collapseTree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTree">
                <div class="panel-body">
                Il faut vous inscrire au niveau de la partie partenaire, vous n’avez pas besoin de recharger votre solde.
En fonction des clients qui viendront payer chez vous pouvez récupérer cet argent par cash depuis notre agence ou chez nos partenaires.

                </div>
              </div>
            </div>
          </div>

          <hr>

          <h4 style="color:red">NB</h4>
          <p>Après inscription, vous ne pouvez changer que votre numéro de téléphone, votre adresse email et votre mot de passe.<br/>Le nom, le prénom ne peuvent pas être changer, ni le numéro de la carte d'identité.<br/>
Pour des mesures de sécurité si vous voulez changer ces informations veuillez se rapprocher de notre agence. <br/>
Donc veuillez svp remplir les bonnes informations.</p>
        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
        </div>

      </div>
    </div>
  </div>

  <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
    Comment ça marche ?
  </button>
  <br><br>


       </div>
    </div>

    </div> <!-- /container -->


   <?php require_once "includes/_footer.php";?>