<?php
// conexao.php (coloque em TCC/)
$host = "localhost";
$user = "root";
$pass = "";
$db = "estocando";

// Usando a abordagem orientada a objetos para criar a conexão
$conexao = new mysqli($host, $user, $pass, $db);
if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}
$conexao->set_charset("utf8mb4");
?>
