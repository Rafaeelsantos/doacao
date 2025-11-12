<?php
// Evita erro caso a sessão não tenha sido iniciada (por segurança extra)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>


<section class="welcome">

    <h2>Seus históricos de doações</h2>
    <p>Acompanhe todas as suas contribuições para o RS</p>
</section>




<div class="historico-container">
    <h2 class="titulo-historico">📅 Todas as Doações</h2>

    <div class="card-doacao">
        <div class="icone">
            <div class="icone-caixa dinheiro"></div>
        </div>
        <div class="conteudo">
            <div class="topo">
                <span class="categoria">Dinheiro</span>
                <span class="status processada">processada</span>
            </div>
            <div class="valor">R$ 100,00</div>
            <p class="descricao">teste</p>
            <div class="rodape">
                <div><i class="far fa-calendar"></i> 05 de novembro de 2025</div>
                <div><i class="far fa-user"></i> 2 famílias</div>
            </div>
        </div>
    </div>

    <div class="card-doacao">
        <div class="icone">
            <div class="icone-caixa alimentos"></div>
        </div>
        <div class="conteudo">
            <div class="topo">
                <span class="categoria">Alimentos</span>
                <span class="status processada">processada</span>
            </div>
            <div class="valor">50 itens</div>
            <p class="descricao">Cestas básicas com arroz, feijão e óleo</p>
            <div class="rodape">
                <div><i class="far fa-calendar"></i> 05 de novembro de 2025</div>
                <div><i class="far fa-user"></i> 10 famílias</div>
            </div>
        </div>
    </div>

    <div class="card-doacao">
        <div class="icone">
            <div class="icone-caixa dinheiro"></div>
        </div>
        <div class="conteudo">
            <div class="topo">
                <span class="categoria">Dinheiro</span>
                <span class="status entregue">entregue</span>
            </div>
            <div class="valor">R$ 150,00</div>
            <p class="descricao">Doação para ajudar famílias carentes</p>
            <div class="rodape">
                <div><i class="far fa-calendar"></i> 05 de novembro de 2025</div>
                <div><i class="far fa-user"></i> 3 famílias</div>
            </div>
        </div>
    </div>

    <div class="card-doacao">
        <div class="icone">
            <div class="icone-caixa roupas"></div>
        </div>
        <div class="conteudo">
            <div class="topo">
                <span class="categoria">Roupas</span>
                <span class="status entregue">entregue</span>
            </div>
            <div class="valor">15 peças</div>
            <p class="descricao">Roupas infantis</p>
            <div class="rodape">
                <div><i class="far fa-calendar"></i> 05 de novembro de 2025</div>
                <div><i class="far fa-user"></i> 4 famílias</div>
            </div>
        </div>
    </div>

</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>