<?php
// Evita erro caso a sessão não tenha sido iniciada (por segurança extra)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../pagina_de_cadastro_e_login/conexoes/conexao.php";

$usuario_id = $_SESSION['usuario_id'];

// Buscar históricos do usuário
$sql = "SELECT tipo, quantidade, valor, mensagem, data_doacao 
        FROM doacao
        WHERE id_usuario = ?
        ORDER BY data_doacao DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>


<section class="welcome">
    <h2>Seus históricos de doações</h2>
    <p>Acompanhe todas as suas contribuições para o RS</p>
</section>


<div class="historico-container">
    <h2 class="titulo-historico">📅 Todas as Doações</h2>

    <?php while ($row = $result->fetch_assoc()): ?>

        <div class="card-doacao">
            <div class="icone">
                <div class="icone-caixa <?= strtolower($row['tipo']) ?>"></div>
            </div>

            <div class="conteudo">
                <div class="topo">
                    <span class="categoria"><?= ucfirst($row['tipo']) ?></span>
                </div>

                <div class="valor">
                    <?php
                    $tipo = strtolower($row['tipo']);

                    if ($tipo === "dinheiro") {
                        echo "R$ " . number_format($row['valor'], 2, ',', '.');
                    } else {
                        // Converte para inteiro para evitar valores como 0.00 ou 3.00
                        $qtd = (int) $row['quantidade'];
                        echo $qtd . " itens doados";
                    }
                    ?>
                </div>



                <p class="descricao"><?= $row['mensagem'] ?></p>

                <div class="rodape">
                    <div>
                        <i class="far fa-calendar"></i>
                        <?= date("d/m/Y", strtotime($row['data_doacao'])) ?>
                    </div>
                </div>
            </div>
        </div>

    <?php endwhile; ?>

</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>