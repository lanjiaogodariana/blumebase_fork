<?php
// define database host address (localhost loopback)
$host = '127.0.0.1'; 
// define database port number for mysql
$port = '3306'; 
// define mysql database name
$db   = 'blume_db';
// define database username
$user = 'root';
// define database password (empty for local xampp default)
$pass = ''; 

// build data source name (dsn) string for pdo connection
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

// PDO allows us to interact with different database types if we ever scale beyond MySQL.
// PDO natively supports prepared statements with parameter binding, which is our primary defense against SQL Injection attacks.
try {
    // initialize new pdo instance for database connection
    $pdo = new PDO($dsn, $user, $pass);
    // set pdo error mode to throw exceptions on failure
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // catch connection errors and terminate script with error message
    die("Database connection failed: " . $e->getMessage());
}
?>