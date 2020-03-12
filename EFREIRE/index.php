<?php
//EFREIRE/index.php

//inclusion du fichier init  (session / connexion à la BDD)
include('init.inc.php');


// traitement de la déconnexion
if(isset($_GET['action']) && $_GET['action'] == 'deco'){
  unset($_SESSION['membre']);
  session_destroy();
  header('location:index.php');
}

// Si le membre est connecté on le redirige vers ces groupes
if(isset($_SESSION['membre'])){
  // Membre est connecté
  header('location:groupes.php');
}

// Traitement connecter le membre
if(!empty($_POST)){
// Si le formulaire est activé
  $resultat = $pdo -> prepare("SELECT * FROM membres WHERE pseudo = ?");
  $resultat -> execute(array($_POST['pseudo']));
  // On cherche dans la BDD un membre avec le pseudo saisi

  if($resultat -> rowCount() != 0){
    // Si le nbre de résultat est =/= de 0, cela signifie que le pseudo existe
    $membre = $resultat -> fetch();

    if($membre['password'] == md5($_POST['password'])){
      // Le mdp correspond à celui crypté et enregfistré en BDD
      $_SESSION['membre'] = $membre;
      // Je prend les infos du membre et je les enregistre en session.
      header('location:groupes.php');
    }
    else{
      $error[] = 'Erreur de mot de passe';
    }
  }
  else{
    $error[] = 'Erreur d\'identifiant';
  }
}


?>
<!DOCTYPE html>
<html>
  <head>
      <title>EFREIRE</title>
      <link rel="stylesheet" href="css/styles.css" />
      <meta name="viewport" content="width=device-width, initial-scalable=1">
  </head>
  <body>
    <header>
        <div id="menu"></div>
        <div id="logo"><a href=""><img src="images/logo.png" width="140"/></a></div>
        <div id="deco"></div>
    </header>
    <main>
        <h1>Connexion</h1>

        <?php
          if(isset($error)){
            echo '<div class="erreur">';
            foreach($error as $e){
              echo $e . '<br/>';
            }
            echo '</div>';
          }
        ?>

        <form method="post" action="">
          <input type="text" name="pseudo" placeholder="pseudo"/>
          <input type="password" name="password" placeholder="Mot de passe"/>
          <input type="submit" value="Connexion" />
        </form>
        <a class="action" href="inscription.php">Pas encore inscrit ?</a>
    </main>
  </body>
</html>
