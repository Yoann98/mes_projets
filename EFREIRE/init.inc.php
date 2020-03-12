<?php
//EFREIRE/inc.init.php

// Ouverture de la session
session_start();

// Connexion à la BDD
$pdo = new PDO('mysql:host=localhost;dbname=efreire', 'root', 'root', array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
));


 ?>
