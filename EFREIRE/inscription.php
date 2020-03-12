<?php
//EFREIRE/inscription.php
include('init.inc.php');
//Inclure le fichier init dans lequel se trouve l'ouverture de la session et la connexion à la BDD.
// Copier/coller de tout le code de init.inc.php


// echo '<pre>';
// print_r($_POST);
// print_r($_FILES);
// echo '</pre>';

if(!empty($_POST)){
  // Si le formulaire est activé

  if(empty($_POST['pseudo'])){
    $error[] = 'Veuillez renseigner un pseudo';
  }

  if(empty($_POST['email'])){
    $error[] = 'Veuillez renseigner un email';
  }

  if(empty($_POST['password'])){
    $error[] = 'Veuillez renseigner un password';
  }



  // traitement de la photo
  if(!empty($_FILES['photo']['name'])){
    // s'il y a une photo postée
    $nom_photo = time() . '_' . rand(1, 9999) . '_' . $_FILES['photo']['name'];
    // On renomme la photo de manière plus complexe

    $chemin_photo = __DIR__ . '/images/' . $nom_photo;
    //c://xampp/htdocs/php/efreire/images/nom-de-la-photo.jpg

    // enregistrement de la photo dans le dossier images:
    copy($_FILES['photo']['tmp_name'], $chemin_photo);
  }
  else{
    // Le visiteur n'a pas ajouté de $nom_photo
    $nom_photo = 'default.jpg';
  }


  if(!isset($error)){
    // Si $error est vide, cela signifie que le formulaire est valide
    // On peut procéder à l'inscription de l'utilisateur...


    $resultat = $pdo -> prepare("SELECT * FROM membres WHERE pseudo = ? ");
    $resultat -> execute(array($_POST['pseudo']));
    if($resultat -> rowCount() > 0){
      $error[] = 'Ce pseudo n\'est pas disponible';
    }
    else{

      $resultat = $pdo -> prepare("INSERT INTO membres (pseudo, email, password, photo) VALUES (?,?,?,?)");
      $validation = $resultat -> execute(array(
        $_POST['pseudo'],
        $_POST['email'],
        md5($_POST['password']), //password crypté selon MD5
        $nom_photo
      ));

      if($validation){
        // si la requete est OK, alors on redirige l'utilisateur vers la connexion
        header('location:index.php');
      }
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
      <div id="menu"></div>
      <div id="logo"><a href="index.php"><img src="images/logo.png" width="140"/></a></div>
      <div id="deco"></div>
    </header>
    <main>
        <h1>Inscription</h1>
        <?php
          if(isset($error)){
            echo '<div class="erreur">';
            foreach($error as $e){
              echo $e . '<br/>';
            }
            echo '</div>';
          }
        ?>
        <form method="post" action="" enctype="multipart/form-data">

          <input type="text" name="pseudo" placeholder="pseudo"/>
          <input type="text" name="email" placeholder="email"/>
          <input type="password" name="password" placeholder="Mot de passe"/>
          <input type="file" name="photo"/>

          <input type="submit" value="Inscription" />
        </form>
        <a href="index.php">Connectez-vous</a>
    </main>
  </body>
</html>
