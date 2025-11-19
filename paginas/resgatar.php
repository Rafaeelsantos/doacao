<?php
session_start();
include "../pagina_de_cadastro_e_login/conexoes/conexao.php";

if (!isset($_GET['id'])) {
    die("ID inválido");
}

$id = intval($_GET['id']);
$usuario_id = $_SESSION['usuario_id'];

// Buscar recompensa
$stmt = $conn->prepare("SELECT * FROM recompensas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$recompensa = $stmt->get_result()->fetch_assoc();

if (!$recompensa) {
    die("Recompensa não encontrada.");
}

// Buscar pontos do usuário
$stmt = $conn->prepare("SELECT pontos FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
$usuario_pontos = $usuario["pontos"];

// Verifica pontos
if ($usuario_pontos < $recompensa['pontos_necessarios']) {
    die("Você não tem pontos suficientes.");
}

// Gerar código
$codigo = "RW-" . time() . "-" . strtoupper(substr(md5(uniqid()), 0, 8));

// Debitar pontos
$novo_total = $usuario_pontos - $recompensa['pontos_necessarios'];
$stmt = $conn->prepare("UPDATE usuarios SET pontos = ? WHERE id = ?");
$stmt->bind_param("ii", $novo_total, $usuario_id);
$stmt->execute();

// Registrar resgate
$stmt = $conn->prepare("INSERT INTO resgates (usuario_id, recompensa_id, codigo_resgate, data_resgate) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iis", $usuario_id, $id, $codigo);
$stmt->execute();

// Atualiza sessão
$_SESSION['usuario_pontos'] = $novo_total;
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Resgate</title>


<body>

    <div class="popup show">
        <div class="popup-content">

            <!-- CÍRCULO VERDE COM ✔ -->
            <div class="popup-icon">✔</div>

            <!-- TÍTULO PRINCIPAL -->
            <h2>Resgate Confirmado!</h2>
            <p>Sua recompensa foi resgatada com sucesso</p>

            <!-- BOX DA RECOMPENSA -->
            <div class="popup-box">
                <label style="color:#777; font-size:14px;">Recompensa</label>

                <h3><?= $recompensa['titulo'] ?></h3>

                <p>
                    <i class="fa-solid fa-medal" style="color:#ff7a00; margin-right:6px;"></i>
                    <?= $recompensa['pontos_necessarios'] ?> pontos gastos
                </p>

                <div class="popup-separator"></div>

                <label style="color:#777; font-size:14px;">Código de Resgate</label>

                <div class="codigo-resgate"><?= $codigo ?></div>
            </div>

            <p class="popup-footer-text">
                Um email foi enviado com os detalhes do seu resgate
            </p>

            <!-- BOTÃO FECHAR -->
            <button id="popup-fechar" onclick="window.location.href='../inicio.php'">
                Fechar
            </button>
        </div>
    </div>

</body>

<style>
    /* === OVERLAY === */
    .popup {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.55);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        opacity: 0;
        pointer-events: none;
        transition: .25s;
    }

    .popup.show {
        opacity: 1;
        pointer-events: all;
    }

    /* === CAIXA PRINCIPAL === */
    .popup-content {
        background: #fff;
        width: 450px;
        border-radius: 16px;
        padding: 35px 30px;
        text-align: center;
        position: relative;
        animation: scaleIn .28s ease;
        font-family: "Poppins", sans-serif;
    }

    /* Animação */
    @keyframes scaleIn {
        from {
            transform: scale(0.85);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* === X para fechar (igual ao da imagem) === */
    .popup-content::after {
        position: absolute;
        top: 18px;
        right: 18px;
        font-size: 18px;
        color: #555;
        cursor: pointer;
    }

    /* === Ícone verde redondo === */
    .popup-icon {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background: #0AC15F;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px auto;
        font-size: 38px;
        color: white;
        font-weight: bold;
    }

    /* === Títulos === */
    .popup-content h2 {
        font-size: 26px;
        font-weight: 600;
        color: #111;
        margin-bottom: 6px;
    }

    .popup-content p {
        color: #666;
        font-size: 15px;
        margin-bottom: 25px;
    }

    /* === CARTÃO DA RECOMPENSA === */
    .popup-box {
        background: linear-gradient(180deg, #F2FFF8 0%, #FFFFFF 100%);
        border: 1px solid #DDE7E2;
        border-radius: 14px;
        padding: 22px;
        text-align: center;
    }

    /* Nome da recompensa */
    .popup-box h3 {
        font-size: 20px;
        font-weight: 600;
        color: #222;
        margin-bottom: 4px;
    }

    /* Pontos gastos */
    .popup-box p {
        margin: 0;
        font-size: 15px;
        color: #ff5b2e;
        font-weight: 600;
    }

    /* Separador horizontal */
    .popup-separator {
        width: 100%;
        height: 1px;
        background: #d7dfe5;
        margin: 18px 0;
    }

    /* === Código de Resgate === */
    .codigo-resgate {
        font-size: 17px;
        font-weight: 600;
        color: #333;
        background: #F7FAFB;
        border: 1px dashed #C6D3DA;
        padding: 14px;
        border-radius: 10px;
        margin-top: 10px;
    }

    /* Texto final */
    .popup-footer-text {
        margin-top: 18px;
        font-size: 13px;
        color: #666;
    }

    /* === BOTÃO FECHAR === */
    #popup-fechar {
        width: 100%;
        margin-top: 22px;
        background: #00AEEF;
        color: white;
        padding: 13px 0;
        border-radius: 10px;
        border: none;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s;
    }

    #popup-fechar:hover {
        background: #0098D1;
        transform: translateY(-1px);
    }
</style>
</head>


</html>