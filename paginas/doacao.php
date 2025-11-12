<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="alerta-container">
    <div class="alerta-icone">⚠️</div>
    <div class="alerta-conteudo">
        <h2>SITUAÇÃO CRÍTICA</h2>
        <p>O RS enfrenta ciclone após enchentes. Sua ajuda é URGENTE!</p>
    </div>
</div>

<section class="mensagem">
    <h2>Ajude o Rio Grande do Sul Agora</h2>
    <p>Milhares de famílias gaúchas precisam da sua solidariedade urgentemente</p>
</section>

<section class="categorias-doacao">

    <a href="paginas\formularios\form_roupas.php" class="card-link">
        <div class="card-categoria roupas" data-page="Roupas">
            <div class="icon"><i class="fas fa-tshirt"></i></div>
            <h3>Roupas</h3>
            <p>Roupas e agasalhos para as famílias</p>
        </div>
    </a>

    <a href="paginas\formularios\form_alimentos.php" class="card-link">
        <div class="card-categoria alimentos" data-categoria="Alimentos">
            <div class="icon"><i class="fas fa-apple-alt"></i></div>
            <h3>Alimentos</h3>
            <p>Alimentos não perecíveis</p>
        </div>
    </a>

    <a href="paginas\formularios\form_financeiro.php" class="card-link">
        <div class="card-categoria dinheiro" data-categoria="Dinheiro">
            <div class="icon"><i class="fas fa-dollar-sign"></i></div>
            <h3>Dinheiro</h3>
            <p>Contribuição financeira urgente</p>
        </div>
    </a>

</section>