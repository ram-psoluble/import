<?php
try {
	$bdd = new PDO('mysql:host=localhost;dbname=assurance', 'root', '');
} catch(Exception $e) {
	exit('Impossible de se connecter à la base de données.');
}
?>