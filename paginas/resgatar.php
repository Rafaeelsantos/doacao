<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include "../pagina_de_cadastro_e_login/conexoes/conexao.php";

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../pagina_de_cadastro_e_login/realizar_login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_recompensa'])) {
    $id_recompensa = intval($_POST['id_recompensa']);

    // --- Busca a recompensa ---
    $stmt = $conn->prepare("SELECT * FROM recompensas WHERE id = ?");
    if (!$stmt) {
        die("Erro no prepare (recompensas): " . $conn->error);
    }
    $stmt->bind_param("i", $id_recompensa);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $recompensa = $resultado->fetch_assoc();
        $pontos_necessarios = intval($recompensa['pontos_necessarios']);

        // --- Busca pontos do usuário ---
        $stmt_user = $conn->prepare("SELECT pontos FROM usuarios WHERE id = ?");
        if (!$stmt_user) {
            die("Erro no prepare (usuarios): " . $conn->error);
        }
        $stmt_user->bind_param("i", $usuario_id);
        $stmt_user->execute();
        $res_user = $stmt_user->get_result();
        $usuario = $res_user->fetch_assoc();
        $pontos_usuario = intval($usuario['pontos']);

        if ($pontos_usuario >= $pontos_necessarios) {
            // Desconta os pontos
            $novo_saldo = $pontos_usuario - $pontos_necessarios;
            $update = $conn->prepare("UPDATE usuarios SET pontos = ? WHERE id = ?");
            if (!$update) {
                die("Erro no prepare (update usuarios): " . $conn->error);
            }
            $update->bind_param("ii", $novo_saldo, $usuario_id);
            if (!$update->execute()) {
                die("Erro ao atualizar pontos: " . $update->error);
            }
            $update->close();

            // --- INSERÇÃO CORRIGIDA ---
            $sql = "INSERT INTO resgates (usuario_id, id_recompensa, data_resgate) VALUES (?, ?, NOW())";
            $stmt_resgate = $conn->prepare($sql);

            if (!$stmt_resgate) {
                $mensagem = "<div class='msg erro'>Erro no prepare (resgates): " . htmlspecialchars($conn->error) . "</div>";
            } else {
                $stmt_resgate->bind_param("ii", $usuario_id, $id_recompensa);

                if (!$stmt_resgate->execute()) {
                    $mensagem = "<div class='msg erro'>Erro ao inserir resgate: " . htmlspecialchars($stmt_resgate->error) . "</div>";
                } else {
                    $mensagem = "<div class='msg sucesso'>🎉 Recompensa <strong>" . htmlspecialchars($recompensa['titulo']) . "</strong> resgatada com sucesso!</div>";
                    // Atualiza a sessão
                    $_SESSION['usuario_pontos'] = $novo_saldo;
                }

                $stmt_resgate->close();
            }
        } else {
            $mensagem = "<div class='msg erro'>❌ Você não tem pontos suficientes para essa recompensa.</div>";
        }
        $stmt_user->close();
    } else {
        $mensagem = "<div class='msg erro'>Recompensa não encontrada.</div>";
    }

    $stmt->close();
} else {
    $mensagem = "<div class='msg erro'>⚠️ Requisição inválida.</div>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Resgate de Recompensa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            width: 350px;
            text-align: center;
        }

        .msg {
            padding: 15px;
            border-radius: 8px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .msg.sucesso {
            background: #d4edda;
            color: #155724;
        }

        .msg.erro {
            background: #f8d7da;
            color: #721c24;
            text-align: left;
        }

        a {
            text-decoration: none;
            color: white;
            background: #007bff;
            padding: 10px 20px;
            border-radius: 8px;
            display: inline-block;
        }

        a:hover {
            background: #0056b3;
        }

        pre {
            background: #f1f1f1;
            padding: 10px;
            border-radius: 6px;
            overflow: auto;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="container">
        <?= $mensagem ?>
        <a href="../inicio.php">⬅ Voltar</a>
    </div>
</body>

</html>