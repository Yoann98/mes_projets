<?php
//EFREIRE/messages.php
include('init.inc.php');

// 1 : Si pas connecté redirection
if(!isset($_SESSION['membre'])){
  // User n'est pas connecté
  header('location:index.php');
}

// 2 : Récupérer tous les membres de mon répertoire (table membres)
$id = $_SESSION['membre']['id_membres'];
$resultat = $pdo -> query("SELECT * FROM membres WHERE id_membres != $id ORDER BY pseudo ASC");
$membres = $resultat -> fetchAll();

// 3 : Afficher la liste des membres sous forme de formulaire
// CF La partie HTML

// 4 : Récupérer les infos du formulaire et créer le groupe

if(!empty($_POST)){
// Si le formulaire est activé
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

  // Enregistrement du groupe
  $resultat = $pdo -> prepare("INSERT INTO groupes (titre, photo, date, id_membres) VALUES (?, ?, NOW(), ?)");
  $validation = $resultat -> execute(array(
    $_POST['titre'],
    $nom_photo,
    $_SESSION['membre']['id_membres']
  ));

  // enregistrement des membres :
  $id_groupe = $pdo -> lastInsertId();
  foreach($_POST as $indice => $valeur){
    if(is_numeric($indice)){
        $pdo -> exec("INSERT INTO groupes_membres (id_groupes, id_membres) VALUES ($id_groupe, $indice)");
    }
  }
  $mon_id = $_SESSION['membre']['id_membres'];
  $pdo -> exec("INSERT INTO groupes_membres (id_groupes, id_membres) VALUES ($id_groupe, $mon_id)");

  header('location:index.php');

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
        <h1>Créer un groupe</h1>
        <section>
          <form id="add_group" method="post" action="" enctype="multipart/form-data">

            <div class="groupe_params">
              <input type="text" name="titre" placeholder="Titre du groupe"/>
              <input type="file" name="photo" />
            </div>
            <h2>Ajouter les membres</h2>
            <div class="groupe_membres ">
              <?php if($membres) : ?>
              <?php foreach($membres as $mem) : ?>
              <div class="membre">
                  <label for="<?php echo $mem['id_membres'] ?>">
                  <div class="membre_photo">
                    <img src="images/<?php echo $mem['photo'] ?>" width="40px" height="40px"/>
                  </div>
                  <div class="membre_infos">
                      <p><?php echo $mem['pseudo'] ?></p>
                      <p>(Online)</p>
                  </div>
                  </label>
                  <input type="checkbox" id="<?php echo $mem['id_membres'] ?>" name="<?php echo $mem['id_membres'] ?>" />
              </div>
              <?php endforeach;  ?>
              <?php endif;  ?>
            </div>
            <input type="submit" value="créer le groupe"/>
          </form>
        </section>
    </body>
</html>
