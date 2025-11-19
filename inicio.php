<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



if (!isset($_SESSION['usuario_id'])) {
    header("Location: pagina_de_cadastro_e_login/realizar_login.php");
    exit;
}



?>





<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>RS Solidário</title>

    <?php
    $css_dir = 'inicio_recursos\elementos\css';
    $css_files = glob($css_dir . '/*.css');
    foreach ($css_files as $css) {
        echo '<link rel="stylesheet" href="' . $css . '">' . "\n";
    }
    ?>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
    <header>
        <div class="logo">
            <img src="https://cdn-icons-png.flaticon.com/512/1077/1077012.png" alt="Logo">
            <div class="logo-text">
                <h1>RS Solidário</h1>
                <span>Enchentes RS 2024</span>
            </div>
        </div>

        <nav>
            <ul>
                <li class="active" data-page="home.php">
                    <i data-lucide="home"></i>
                    <a href="#">Início</a>
                </li>
                <li data-page="doacao.php">
                    <i data-lucide="hand-heart"></i>
                    <a href="#">Fazer Doação</a>
                </li>
                <li data-page="recompensas.php">
                    <i data-lucide="gift"></i>
                    <a href="#">Recompensas</a>
                </li>
                <li data-page="historicos.php">
                    <i data-lucide="clock"></i>
                    <a href="#">Histórico</a>
                </li>
                <li data-page="sobre.html">
                    <i data-lucide="info"></i>
                    <a href="#">Sobre a Causa</a>
                </li>
            </ul>
        </nav>

        <div class="user-info">
            <div class="points">
                <span id="pontosUsuario"><?php echo $_SESSION['usuario_pontos'] . " pts"; ?></span>
            </div>
            <div class="profile">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Usuário">
            </div>
        </div>
    </header>

    <!-- Aqui o conteúdo das páginas será carregado -->
    <main id="conteudo-principal">
        <?php include 'paginas/home.php'; ?>
    </main>

    <script src="inicio_recursos\elementos\js\elementosDaPagina.js"></script>



    <script>
        function atualizarPontos() {
            fetch('buscar_pontos.php', { cache: 'no-store' })
                .then(response => response.json())
                .then(data => {
                    if (data.pontos !== undefined) {
                        const pontosEl = document.getElementById('pontosUsuario');
                        const valorAtual = parseInt(pontosEl.textContent);

                        if (valorAtual !== data.pontos) {
                            pontosEl.textContent = data.pontos + " pts";
                            // animação suave de atualização
                            pontosEl.style.transition = "color 0.4s ease";
                            pontosEl.style.color = "#00c853"; // verde
                            setTimeout(() => pontosEl.style.color = "", 600);
                        }
                    }
                })
                .catch(error => console.error('Erro ao buscar pontos:', error));
        }

        // Atualiza junto com o saldo
        setInterval(atualizarPontos, 5000);
        atualizarPontos();
    </script>





    <script>
        // Função que busca os pontos atualizados
        function atualizarPontos() {
            fetch('buscar_pontos.php')
                .then(res => res.json())
                .then(data => {
                    if (data.pontos !== undefined) {
                        document.getElementById('pontosUsuario').textContent = data.pontos;
                    }
                })
                .catch(err => console.error('Erro ao buscar pontos:', err));
        }

        // Atualiza quando a página carrega
        document.addEventListener('DOMContentLoaded', atualizarPontos);

        // Atualiza também após uma doação (chame esta função depois de enviar o formulário)
        function doacaoConcluida() {
            atualizarPontos(); // Atualiza os pontos do usuário
            alert('Doação registrada com sucesso!');
        }
    </script>




</body>

</html>