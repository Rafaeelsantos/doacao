<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Busca todas as informações do usuário
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();

        // Verifica senha criptografada
        if (password_verify($senha, $usuario['senha'])) {

            // Cria sessão com todos os dados do usuário
            foreach ($usuario as $chave => $valor) {
                $_SESSION['usuario_' . $chave] = $valor;
            }

            header("Location: ../../inicio.php");
            exit;
        } else {
            // Senha incorreta
            header("Location: ../realizar_login.php?erro=senha");
            exit;
        }
    } else {
        // Usuário não encontrado
        header("Location: ../realizar_login.php?erro=email");
        exit;
    }
}
?>