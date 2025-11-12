<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../../pagina_de_cadastro_e_login/conexoes/conexao.php";

// 🧩 PEGAR DADOS DO FORMULÁRIO
$usuario_id = $_SESSION['usuario_id'] ?? null;
$nome = $_SESSION['usuario_nome'] ?? '';
$email = $_SESSION['usuario_email'] ?? '';
$telefone = trim($_POST['telefone'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$mensagem = trim($_POST['mensagem'] ?? '');
$tipo = trim($_POST['tipo'] ?? 'desconhecido');
$imagem = null;
$quantidade = intval($_POST['quantidade'] ?? 0);
$valor = floatval($_POST['valor'] ?? 0.00);

// ✅ Validação: valores inteiros apenas para doação em dinheiro
if ($tipo === 'dinheiro' && floor($valor) != $valor) {
    die("Erro: Doações em dinheiro devem ser valores inteiros (ex: 1, 2, 50).");
}

// ✅ Regra de pontos: cada R$1 = 2 pontos
$pontos = $tipo === 'dinheiro' ? $valor * 2 : max(1, $quantidade ?: round($valor));

// 🧠 VERIFICA CAMPOS OBRIGATÓRIOS
if ($tipo === 'dinheiro') {
    if (!$usuario_id || empty($telefone) || $valor <= 0) {
        die("Erro: Campos obrigatórios não preenchidos (telefone e valor são obrigatórios para doação em dinheiro).");
    }
} else {
    if (!$usuario_id || empty($endereco) || empty($telefone) || $quantidade <= 0) {
        die("Erro: Campos obrigatórios não preenchidos.");
    }
}

// 📸 UPLOAD DE IMAGEM (opcional)
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $pasta = __DIR__ . "/uploads/";
    if (!is_dir($pasta)) {
        mkdir($pasta, 0777, true);
    }

    $extensao = pathinfo($_FILES["imagem"]["name"], PATHINFO_EXTENSION);
    $nomeArquivo = uniqid("img_", true) . "." . strtolower($extensao);
    $caminhoCompleto = $pasta . $nomeArquivo;

    if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminhoCompleto)) {
        $imagem = "uploads/" . $nomeArquivo;
    } else {
        die("Erro ao fazer upload da imagem.");
    }
}

// 📊 CONSULTAR QUANTIDADE DE DOAÇÕES EXISTENTES
$qtd_stmt = $conn->prepare("SELECT COUNT(*) FROM doacao WHERE id_usuario = ?");
$qtd_stmt->bind_param("i", $usuario_id);
$qtd_stmt->execute();
$qtd_stmt->bind_result($qtd_atual);
$qtd_stmt->fetch();
$qtd_stmt->close();

$qtd_doacoes_total = $qtd_atual + 1;

// 💾 INSERIR NOVA DOAÇÃO
$sql = "INSERT INTO doacao 
        (id_usuario, nome, tipo, imagem, quantidade, valor, endereco, telefone, email, mensagem, pontos, qtd_doacoes)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "issisdssssii",
    $usuario_id,
    $nome,
    $tipo,
    $imagem,
    $quantidade,
    $valor,
    $endereco,
    $telefone,
    $email,
    $mensagem,
    $pontos,
    $qtd_doacoes_total
);

// 🧩 EXECUTA E VERIFICA
if ($stmt->execute()) {
    if ($tipo === 'dinheiro') {
        // 🔻 Atualizar saldo e pontos do usuário
        $update = $conn->prepare("
            UPDATE usuarios 
            SET qtd_doacoes = qtd_doacoes + 1,
                pontos = pontos + ?,
                telefone = ?,
                saldo = saldo - ?
            WHERE id = ? AND saldo >= ?
        ");
        $update->bind_param("isdii", $pontos, $telefone, $valor, $usuario_id, $valor);
        $update->execute();

        if ($update->affected_rows === 0) {
            die("Erro: saldo insuficiente para realizar esta doação.");
        }
    } else {
        // Doação física
        $update = $conn->prepare("
            UPDATE usuarios 
            SET qtd_doacoes = qtd_doacoes + 1,
                pontos = pontos + ?,
                telefone = ?
            WHERE id = ?
        ");
        $update->bind_param("isi", $pontos, $telefone, $usuario_id);
        $update->execute();
    }

    echo "<script>
        alert('🎉 Doação registrada com sucesso! Você ganhou +{$pontos} ponto(s) 💚');
        window.location.href = '../../../inicio.php';
    </script>";
} else {
    echo "Erro ao salvar doação: " . htmlspecialchars($stmt->error);
}

$stmt->close();
$conn->close();
?>