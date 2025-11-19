<?php
// Evita erro caso a sessão não tenha sido iniciada (por segurança extra)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>





<section class="welcome">

    <h2>Bem-vindo, <span><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</span></h2>

    <p>Continue fazendo a diferença na reconstrução do RS</p>
</section>

<section class="saldo-card">
    <div class="saldo-info">
        <h3>Seu Saldo Disponível</h3>
        <h1 id="saldoUsuario">R$ <?php echo number_format($_SESSION['usuario_saldo'], 2, ',', '.'); ?></h1>

        <p><img src="https://images.vexels.com/media/users/3/157446/isolated/preview/383f43305de4fbc3c6a3bdfb25a1b758-marketing-graph-icon.png"
                alt=""> Total doado
            <strong id="saldoUsuario"><?php echo number_format($_SESSION['usuario_saldo'], 2, ',', '.'); ?></strong>
        </p>
    </div>
</section>









<section class="noticias-bloco">
    <h2 class="noticias-titulo">
        Últimas notícias
    </h2>

    <div class="noticias-container">


        <div class="noticia-card alerta">
            <div class="noticia-icone">⚠️</div>
            <h3>Ciclone atinge RS com ventos de até 100 km/h</h3>
            <p>Após as enchentes históricas, o estado enfrenta nova tragédia climática com ciclone devastador. Ajuda
                urgente necessária.</p>
            <div class="noticia-footer">
                <span class="hoje">⏱️ Hoje</span>
                <a href="#">↗</a>
            </div>
        </div>

        <div class="noticia-card azul-claro">
            <div class="noticia-icone">📰</div>
            <h3>Voluntários trabalham 24h na distribuição de doações</h3>
            <p>Corrente de solidariedade não para: brasileiros de todo país continuam mobilizados para ajudar o RS.</p>
            <div class="noticia-footer">
                <span>⏱️ 2 dias atrás</span>
                <a href="#">↗</a>
            </div>
        </div>

    </div>
</section>




<section class="impacto-geral">
    <h2 class="noticias-titulo">Impacto da nossa solidariedade</h2>
    <div class="cards">
        <div class="card azul">
            <div class="info">
                <h3>Famílias Ajudadas</h3>
                <p>30</p>
            </div>
            <div class="icone">
                <i class="fas fa-users"></i>
            </div>
        </div>

        <div class="card verde">
            <div class="info">
                <h3>Dinheiro Arrecadado</h3>
                <p>R$ 550.00</p>
            </div>
            <div class="icone">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>

        <div class="card verde2">
            <div class="info">
                <h3>Total de Doações</h3>
                <p>6</p>
            </div>
            <div class="icone">
                <i class="fas fa-box"></i>
            </div>
        </div>
    </div>
</section>



<section class="impacto">
    <h2>Vidas que Você Está Ajudando</h2>

    <div class="impacto-cards">
        <div class="impacto-card azul">
            <div class="icone">👥</div>
            <h3>2.3 Mi+</h3>
            <p>Pessoas afetadas</p>
        </div>

        <div class="impacto-card laranja">
            <div class="icone">⚠️</div>
            <h3>580 Mil</h3>
            <p>Desabrigados</p>
        </div>

        <div class="impacto-card verde">
            <div class="icone">💚</div>
            <h3>75 Mil</h3>
            <p>Famílias em abrigos</p>
        </div>
    </div>
</section>













<script>
    function atualizarSaldo() {
        fetch('buscar_saldo.php', { cache: 'no-store' })
            .then(response => response.json())
            .then(data => {
                if (data.saldo) {
                    document.getElementById('saldoUsuario').textContent = "R$ " + data.saldo;
                }
            })
            .catch(error => console.error('Erro ao buscar saldo:', error));
    }

    // Atualiza a cada 5 segundos
    setInterval(atualizarSaldo, 5000);

    // Atualiza também ao carregar a página
    atualizarSaldo();
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