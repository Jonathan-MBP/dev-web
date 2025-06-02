
<?php

include "function.php";

//parametre de connexion

$host ="localhost";
$dbname ="base_struct";
$login ="root";
$password ="root";


try{
    $connexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $login, $password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    /*echo "<p>Connexion réussie</p>";*/
} catch(PDOException $e) {
    echo "<p>Erreur de connexion: " . $e->getMessage() . "</p>";
}
?>