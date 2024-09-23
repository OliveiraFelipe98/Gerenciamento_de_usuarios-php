<?php
class Usuario {
    private $conn;

    public function __construct($pdo) {
        $this->conn = $pdo;
    }

    public function all() {
        $query = "SELECT * FROM usuarios"; // Altere para o nome correto da sua tabela
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna um array associativo de usuários
    }

    public function create($nome, $email) {
        $query = "INSERT INTO usuarios (nome, email) VALUES (:nome, :email)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    public function delete($id_usuario) {
        $query = "DELETE FROM usuarios WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        return $stmt->execute();
    }

    public function toggleStatus($id_usuario) {
        $query = "UPDATE usuarios SET status = IF(status = 'ativo', 'inativo', 'ativo') WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        return $stmt->execute();
    }
}
?>
