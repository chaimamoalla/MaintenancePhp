<?php
// Page "liste des produits"
require '../app/init.php';

$messages = [
    'ajoute'      => ['success', 'Produit ajouté avec succès.'],
    'modifie'     => ['success', 'Produit modifié avec succès.'],
    'supprime'    => ['success', 'Produit supprimé avec succès.'],
    'introuvable' => ['warning', 'Produit introuvable.'],
    'erreur'      => ['danger',  "Erreur d'accès à la base de données."],
];
$message = null;
if (isset($_GET['msg']) && isset($messages[$_GET['msg']])) {
    $message = $messages[$_GET['msg']];
}

try {
    $produitModel = new ProduitModel();
    $produits = $produitModel->findAllWithCategorie(); //bonjour yesmine 1233
} catch (PDOException $e) {
    $produits = [];
    $message  = ['danger', 'Impossible de charger les produits. Vérifiez la connexion à la base de données.'];
}

$title = 'Gestion des produits';
require 'layouts/header.php';
?>

<?php if ($message): ?>
    <div class="alert alert-<?= $message[0] ?> alert-dismissible fade show" role="alert">
        <?= $message[1] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gestion des Produits</h5>
        <form action="produit_form.php" method="get" class="m-0">
            <button type="submit" class="btn btn-primary btn-sm">Ajouter Produit</button>
        </form>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Code</th>
                    <th>Désignation</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Catégorie</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($produits)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Aucun produit.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($produits as $p): ?>
                    <tr>
                        <td><?= $p['code'] ?></td>
                        <td><?= htmlspecialchars($p['designation']) ?></td>
                        <td><?= $p['prix'] ?> DT</td>
                        <td><?= $p['qte'] ?></td>
                        <td><?= htmlspecialchars($p['nom_categorie'] ?? '') ?></td>

                        <td class="text-end">
                            <!-- Modifier -->
                            <a href="produit_form.php?code=<?= $p['code'] ?>"
                               class="btn btn-warning btn-sm">
                                Modifier
                            </a>

                            <!-- Supprimer -->
                            <a href="produit_delete.php?code=<?= $p['code'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Voulez-vous supprimer ce produit ?')">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require 'layouts/footer.php'; ?>