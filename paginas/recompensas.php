<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

}
include "../pagina_de_cadastro_e_login/conexoes/conexao.php";

// Busca todas as recompensas ativas
$stmt = $conn->prepare("SELECT * FROM recompensas WHERE ativo = 1");
$stmt->execute();
$result = $stmt->get_result();
$recompensas = $result->fetch_all(MYSQLI_ASSOC);


?>

<section class="recompensa-header">
    <h2>Suas Recompensas</h2>
    <p>Troque seus pontos de solidariedade por recompensas incríveis</p>
</section>

<section class="recompensa-card">
    <div class="recompensa-info">
        <div class="recompensa-titulo">
            <img src="https://cdn-icons-png.flaticon.com/512/1828/1828884.png" alt="Ícone de medalha">
            <h3>Seus Pontos de Solidariedade</h3>
        </div>

        <h1 class="recompensa-pontos">
            <?php echo $_SESSION['usuario_pontos'] . " pts"; ?>
        </h1>

        <p class="recompensa-mensagem">
            <img src="https://cdn-icons-png.flaticon.com/512/1250/1250690.png" alt="Ícone de gráfico">
            Continue doando para ganhar mais pontos!
        </p>
    </div>

    <div class="recompensa-presente">
        <img src="https://cdn-icons-png.flaticon.com/512/1441/1441208.png" alt="Presente colorido">
    </div>
</section>

<link rel="stylesheet" href="rewards.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-nT2IhD41V5WvQn7m6cItWghM3XJrDR2iISz4YZT+cVJqnvh8FXbz2TxNPhoWsyhSOng4CqgyuQnyS1G+1an9uA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<section class="rewards-wrapper">
    <div class="rewards-container">
        <div class="rewards-header">
            <h2>🎁 Suas Recompensas</h2>
            <p class="rewards-sub">
                Milhares de famílias precisam da sua solidariedade — troque pontos por recompensas.
            </p>
        </div>

        <div class="rewards-tabs">
            <button class="tab active"><i class="fas fa-gift"></i> Disponíveis</button>
            <button class="tab"><i class="fas fa-clock-rotate-left"></i> Meus Resgates</button>
        </div>

        <div class="rewards-grid">
            <?php if (!empty($recompensas)): ?>
                <?php foreach ($recompensas as $r): ?>
                    <div class="reward-card">
                        <div class="reward-header <?= htmlspecialchars($r['gradiente'] ?: 'gradient-blue') ?>">
                            <i class="<?= htmlspecialchars($r['icone'] ?: 'fas fa-gift') ?> reward-icon"></i>
                            <span class="reward-badge"><?= htmlspecialchars($r['tipo']) ?></span>
                        </div>

                        <div class="reward-body">
                            <h3><?= htmlspecialchars($r['titulo']) ?></h3>
                            <p><?= htmlspecialchars($r['descricao']) ?></p>

                            <div class="reward-info">
                                <div class="reward-points">
                                    <i class="fas fa-medal"></i>
                                    <strong><?= htmlspecialchars($r['pontos_necessarios']) ?> pts</strong>
                                </div>
                                <span class="reward-qty">
                                    <?= htmlspecialchars($r['quantidade_disponivel']) ?> disponíveis
                                </span>
                            </div>

                            <?php if ($_SESSION['usuario_pontos'] >= $r['pontos_necessarios']): ?>
                                <div class="reward-available">🎉 Você pode resgatar!</div>

                                <form action="paginas/resgatar.php" method="POST" class="reward-form">
                                    <input type="hidden" name="id_recompensa" value="<?= $r['id'] ?>">
                                    <button type="submit" class="resgatar-btn">
                                        <i class="fas fa-hand-holding-heart"></i> Resgatar
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="reward-disabled">
                                    Faltam <?= $r['pontos_necessarios'] - $_SESSION['usuario_pontos'] ?> pontos
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhuma recompensa disponível no momento.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
    .resgatar-btn {
        background: linear-gradient(135deg, #4CAF50, #2E7D32);
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        margin-top: 10px;
        transition: transform 0.2s, background 0.3s;
    }

    .resgatar-btn:hover {
        transform: scale(1.05);
        background: linear-gradient(135deg, #66BB6A, #388E3C);
    }

    .reward-form {
        text-align: center;
    }
</style>