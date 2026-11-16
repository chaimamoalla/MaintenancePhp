<?php
require '../app/init.php';
$a = Database::getInstance();
$b = Database::getInstance();
var_dump($a === $b); // bool(true)
$pdo = $a->getConnection();
$result = $pdo->query('SELECT * FROM produit');
$p = $result->fetch();
var_dump($p);
echo $p['designation'];
