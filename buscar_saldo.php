<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "pagina_de_cadastro_e_login/conexoes/conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["erro" => "Usuário não logado"]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT saldo FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($saldo);
$stmt->fetch();
$stmt->close();

echo json_encode(["saldo" => number_format($saldo, 2, ',', '.')]);
