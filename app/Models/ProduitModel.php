<?php
class ProduitModel extends Model
{
    protected string $table = 'produit';
    protected string $primaryKey = 'code';

    public function findAllWithCategorie(): array
    {
        $sql = "SELECT p.*, c.nom AS nom_categorie
                FROM produit p
                LEFT JOIN categorie c ON p.code_categorie = c.code
                ORDER BY p.code";
        return $this->db->query($sql)->fetchAll();
    }
}