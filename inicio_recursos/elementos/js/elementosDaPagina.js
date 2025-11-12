lucide.createIcons();

const navItems = document.querySelectorAll("nav ul li");
const conteudo = document.getElementById("conteudo-principal");

navItems.forEach(item => {
    item.addEventListener("click", () => {
        // Atualiza o item ativo
        navItems.forEach(i => i.classList.remove("active"));
        item.classList.add("active");

        // Carrega o conteúdo HTML correspondente
        const pagina = item.getAttribute("data-page");
        if (pagina) {
            // Adiciona classe de saída (fade-out)
            conteudo.classList.remove("fade-in");
            conteudo.classList.add("fade-out");

            // Aguarda a animação de saída terminar
            setTimeout(() => {
                fetch("paginas/" + pagina)
                    .then(resp => {
                        if (!resp.ok) throw new Error("Página não encontrada");
                        return resp.text();
                    })
                    .then(html => {
                        conteudo.innerHTML = html;

                        // Adiciona o efeito de entrada (fade-in)
                        conteudo.classList.remove("fade-out");
                        conteudo.classList.add("fade-in");
                    })
                    .catch(err => {
                        conteudo.innerHTML = `<p style="color:red;">Erro: ${err.message}</p>`;
                    });
            }, 300); // tempo igual à animação no CSS
        }
    });
});

