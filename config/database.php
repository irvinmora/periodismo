<?php
// Evitar que session_start() se ejecute múltiples veces
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        try {
            $this->conn = new mysqli('localhost', 'root', '', 'periodismo_utb');
            
            if ($this->conn->connect_error) {
                throw new Exception("Error de conexión: " . $this->conn->connect_error);
            }
            
            $this->conn->set_charset("utf8mb4");
            
        } catch (Exception $e) {
            // Para desarrollo, mostrar el error
            if (isset($_SESSION['admin'])) {
                die("Error de base de datos: " . $e->getMessage() . 
                    "<br><br>Por favor, ejecuta <a href='../crear_db.php'>crear_db.php</a> para crear la base de datos.");
            }
            // Para usuarios normales, no mostrar el error
            $this->conn = false;
        }
    }
    
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    public function isConnected() {
        return $this->conn !== false && !$this->conn->connect_error;
    }
}

// Función de conveniencia para obtener conexión
function getDBConnection() {
    $db = Database::getInstance();
    return $db->getConnection();
}

// Función para verificar conexión
function isDBConnected() {
    $db = Database::getInstance();
    return $db->isConnected();
}

function conectarBD() {
    $servidor = 'localhost';
    $usuario = 'root';
    $contraseña = '';
    $baseDatos = 'periodismo_utb';
    
    $conn = new mysqli($servidor, $usuario, $contraseña, $baseDatos);
    
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    
    return $conn;
}
?>