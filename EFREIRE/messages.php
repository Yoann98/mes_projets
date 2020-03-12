<?php
//EFREIRE/messages.php
include('init.inc.php');

// 1 : Si user pas connecté --> redirection
if(!isset($_SESSION['membre'])){
  header('location:index.php');
}

// 2 : Récupérer tous les messages du groupes
if(isset($_GET['id_groupe'])  && !empty($_GET['id_groupe'])){
  $requete = "
  SELECT messages.content, messages.id_membres, membres.pseudo, date_format(messages.date, '%d/%m/%Y à %h:%i:%s') as date_heure, groupes.titre
  FROM messages, membres, groupes
  WHERE messages.id_membres = membres.id_membres
  AND groupes.id_groupes = messages.id_groupes
  AND messages.id_groupes = ?";

  $resultat = $pdo -> prepare($requete);
  $resultat -> execute(array($_GET['id_groupe']));

  $messages = $resultat -> fetchAll();
}
else{
  header('location:groupes.php');
}

// 3 : Afficher tous les messages du groupe (la bulle est différente si l"auteur est la pers connecté)
//cf partie html

// 4 : Traitement enregistrer un nouveau messages

if(!empty($_POST)){

  if(!empty($_POST['message'])){

    $id_groupes = $_GET['id_groupe'];
    $id_membres = $_SESSION['membre']['id_membres'];

    $resultat = $pdo -> prepare("INSERT INTO messages (content, date, id_membres, id_groupes) VALUES (?, NOW(), ?, ?) ");
    $validation = $resultat -> execute(array(
      $_POST['message'],
      $id_membres,
      $id_groupes
    ));
    header('location:messages.php?id_groupe=' . $id_groupes);
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
      <div id="menu"><a href="index.php"><img src="images/picto_eye.png" width="30"/></a></div>
      <div id="logo"><a href="index.php"><img src="images/logo.png" width="140"/></a></div>
      <div id="deco"><a href="index.php?action=deco"><img src="images/picto_off.png" width="30"/></a></div>
    </header>
    <main>
        <h1>Titre de la discussion</h1>
        <section class="section-messages">
        	<div class="message" style="padding-bottom: 80px; ">


          <?php if($messages) : ?>
          <?php foreach($messages as $mes) : ?>

          <?php if($mes['id_membres'] == $_SESSION['membre']['id_membres']) : ?>

            <div class="messages messages-app_user">
        			<div class="content">
        				<div class="content-text">
        					<?php echo $mes['content']  ?>
        				</div>
        				<div class="content-heure">
        					<?php echo $mes['pseudo']  ?> le <?php echo $mes['date_heure']  ?>
        				</div>
        			</div>
        		</div>
        		<div class="fleche-app_user">
        		</div>


          <?php else : ?>

            <div class="fleche">
            </div>
            <div class="messages">
              <div class="content">
                <div class="content-text">
                  <?php echo $mes['content']  ?>
                </div>
                <div class="content-heure">
                  <?php echo $mes['pseudo']  ?> le <?php echo $mes['date_heure']  ?>
                </div>
              </div>
            </div>

          <?php endif;  ?>
          <?php endforeach;  ?>
          <?php  else : ?>
          Aucun message
          <?php endif; ?>









        	</div>





        </section>
        <form class="form-message" method="post" action="">
        	<input type="text" name="message" placeholder="Message" />
        	<input type="submit" value="OK" />
        </form>
      </main>
    </body>
  </html>
