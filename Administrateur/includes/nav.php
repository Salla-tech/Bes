 <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>   
              <nav class="navbar navbar-inverse" style="margin-top:25px;">
                <div class="container-fluid">
                  <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Administrateur</a>
                  </div>

                  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                     <ul class="nav navbar-nav">
                    <li class="active"><a href="index.php?id=<?= $_SESSION['admin_id'];?>" >Accueil <span class="sr-only">(current)</span></a></li>
                      <li><a href="transactions.php?id=<?= $_SESSION['admin_id'];?>">Transactions</a></li>
                      <li><a href="retraits.php?id=<?= $_SESSION['admin_id'];?>">Retraits</a></li>
                      <li><a href="rechargements.php?id=<?= $_SESSION['admin_id'];?>">Rechargements</a></li>
                      <li><a href="historique.php?id=<?= $_SESSION['admin_id'];?>">Historique</a></li>
                      <li><a href="contact.php?id=<?= $_SESSION['admin_id'];?>">Contact</a></li>
                      <li><a href="logout.php">Déconnexion</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                      <li><a href="#">Banque Electronique du Sénégal</a></li>
                    </ul>
                  </div>
                </div>
              </nav>
            
      </div>
    </div>