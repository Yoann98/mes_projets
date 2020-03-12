<?php
//EFREIRE/groupes.php

//inclusion du fichier init  (session / connexion à la BDD)
include('init.inc.php');

// Traitement Pour redirections
if(!isset($_SESSION['membre'])){
  // Le visiteur n'est pas connecté
  header('location:index.php');
}

// Traitement pour récupérer tous les groupes d'un utilisateur
$requete = "
  SELECT groupes.*
  FROM groupes, groupes_membres
  WHERE groupes_membres.id_groupes = groupes.id_groupes
  AND groupes_membres.id_membres = ?
";

$resultat = $pdo -> prepare($requete);
$resultat -> execute(array($_SESSION['membre']['id_membres']));

if($resultat -> rowCount() > 0){
  // Si le membre appartient à des groupes de discussion, on récupère les infos des groupes.
  $groupes = $resultat -> fetchAll();

  //pour chaque groupe on doit récupérer le dernier message :
  foreach($groupes as $indice => $groupe){
    $requete = "SELECT content FROM messages WHERE id_groupes = ? ORDER BY date DESC LIMIT 0,1";
    $resultat = $pdo -> prepare($requete);
    $resultat -> execute(array($groupe['id_groupes']));

    if($resultat -> rowCount() > 0){
      $message = $resultat -> fetch();
      $groupes[$indice]['last_message'] = $message['content'];
    }
    else{
      $groupes[$indice]['last_message'] = 'Aucun message...';
    }
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
        <div id="menu"><a href="add_groupe.php"><img src="images/picto_edit.png" width="30"/></a></div>


        <div id="logo"><a href="index.php"><img src="images/logo.png" width="140"/></a></div>

        <div id="deco"><a href="index.php?action=deco"><img src="images/picto_off.png" width="30"/></a></div>


    </header>
    <main>
        <h1>Discussions</h1>



        <?php if(!empty($groupes)) : ?>
        <?php foreach($groupes as $gr) : ?>
        <a href="messages.php?id_groupe=<?php echo $gr['id_groupes'] ?>" class="groupes">
          <div class="groupe">
            <div class="groupe_img">
                <img src="images/<?php echo $gr['photo'] ?>" width="80px" />
            </div>
            <div class="groupe_info">
              <div class="groupe_title">
                <?php echo $gr['titre'] ?>
              </div>
              <div class="groupe_last">
                <?php echo $gr['last_message'] ?>
              </div>
            </div>
          </div>
        </a>
        <?php endforeach;  ?>
        <?php else : ?>
        <p>Vous n'avez aucune discussion ouverte<br/>
        <a href="add_groupe.php" class="action">Créer une discussion</a></p>
        <?php endif; ?>



    </main>
  </body>
</html>
