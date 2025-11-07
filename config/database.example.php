<?php
class Database {
    // CONFIGURAÇÕES DE PRODUÇÃO - ATUALIZE COM SUAS CREDENCIAIS
    private $host = "localhost";
    private $db_name = "seu_banco_de_dados";
    private $username = "seu_usuario";
    private $password = "sua_senha";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_PERSISTENT => false,
                    PDO::ATTR_EMULATE_PREPARES => false
                )
            );
            $this->conn->exec("set names utf8mb4");
        } catch(PDOException $exception) {
            // Em produção, não mostrar detalhes do erro
            error_log("Database Connection Error: " . $exception->getMessage());
            die("Erro ao conectar ao banco de dados. Por favor, tente novamente mais tarde.");
        }

        return $this->conn;
    }
}
