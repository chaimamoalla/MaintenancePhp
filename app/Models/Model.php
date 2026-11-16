
<?php
abstract class Model
{
    protected string $table;
    protected string $primaryKey = 'id';
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Lire tous les enregistrements
    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$this->primaryKey}";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    // Chercher un enregistrement par son identifiant
    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE {$this->primaryKey} = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    // Ajouter un enregistrement
    public function create(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table}
                ($columns) VALUES ($placeholders)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);

        return (int) $this->db->lastInsertId();
    }

    // Modifier un enregistrement
    public function update(int $id, array $data): bool
    {
        $sets = [];

        foreach (array_keys($data) as $column) {
            $sets[] = "$column = :$column";
        }

        $setString = implode(', ', $sets);

        $sql = "UPDATE {$this->table}
                SET $setString
                WHERE {$this->primaryKey} = :pk";

        $data['pk'] = $id;

        $stmt = $this->db->prepare($sql);

        return $stmt->execute($data);
    }

    // Ajouter ou modifier
    public function save(array $data): int
    {
        $id = isset($data[$this->primaryKey])
            ? $data[$this->primaryKey]
            : '';

        unset($data[$this->primaryKey]);

        if ($id !== '') {
            $this->update((int) $id, $data);
            return (int) $id;
        }

        return $this->create($data);
    }

    // Supprimer un enregistrement
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table}
                WHERE {$this->primaryKey} = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }
}
