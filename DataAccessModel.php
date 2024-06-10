<?php
require_once 'config.php';

class DataAccessModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        if ($this->conn->connect_error) {
            die("Ошибка подключения: " . $this->conn->connect_error);
        }
    }

    // Методы для работы с базой данных

    // Закрытие соединения с базой данных
    public function closeConnection() {
        $this->conn->close();
    }
}
?>
