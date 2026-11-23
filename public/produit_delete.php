<?php
require '../app/init.php';

try {
    $code = filter_var($_GET['code'] ?? '', FILTER_VALIDATE_INT);

    if ($code === false) {
        redirect('produits.php?msg=introuvable');
    }

    $model = new ProduitModel();
    $ok = $model->delete($code);

    redirect($ok ? 'produits.php?msg=supprime' : 'produits.php?msg=introuvable');
} catch (PDOException $e) {
    redirect('produits.php?msg=erreur');
}
