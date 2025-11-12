<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- saldo atual do usuário vindo da sessão ---
$saldo_atual = isset($_SESSION['usuario_saldo']) ? floatval($_SESSION['usuario_saldo']) : 0.00;
?>

<style>
    .page-bg {
        background: linear-gradient(180deg, #f4f8fb 0%, #e9f2f8 100%);
        min-height: 100vh;
        padding: 28px 24px;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    .form-card {
        max-width: 980px;
        margin: 18px auto;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(3, 27, 51, 0.06);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.03);
    }

    .form-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 28px;
        background: linear-gradient(90deg, #19e835ff 0%, #7bb917ff 100%);
        color: #fff;
        border-radius: 12px 12px 0 0;
    }

    .form-title {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .form-title i {
        font-size: 24px;
    }

    .form-topbar h2 {
        font-size: 20px;
        margin: 0;
        font-weight: 600;
    }

    .form-topbar .voltar {
        color: #fff;
        text-decoration: none;
        font-weight: 600;
        opacity: 0.95;
    }

    .form-topbar .voltar:hover {
        opacity: 0.85;
    }

    .form-body {
        padding: 28px 32px 36px 32px;
    }

    .form-body label {
        display: block;
        font-weight: 600;
        margin-bottom: 10px;
        color: #111827;
    }

    .form-body input,
    .form-body textarea {
        width: 100%;
        padding: 16px;
        border-radius: 10px;
        border: 1px solid #e6e9ee;
        background: #fff;
        font-size: 15px;
        color: #1f2937;
        box-sizing: border-box;
        outline: none;
        margin-bottom: 18px;
    }

    .form-body input::placeholder,
    .form-body textarea::placeholder {
        color: #bfc7cf;
        font-weight: 500;
    }

    .form-body input:focus,
    .form-body textarea:focus {
        border-color: rgba(53, 230, 5, 0.6);
        box-shadow: 0 6px 18px rgba(0, 147, 255, 0.08);
    }

    textarea {
        min-height: 100px;
        resize: vertical;
    }

    .preview-container {
        display: none;
        text-align: center;
        margin-bottom: 18px;
    }

    .preview-container img {
        max-width: 300px;
        max-height: 300px;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        object-fit: cover;
    }

    .saldo-info {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        padding: 12px 18px;
        border-radius: 8px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .btn-confirmar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 8px;
        width: 100%;
        padding: 16px 18px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        background: linear-gradient(90deg, #5cd33bff 0%, #6bcf5dff 100%);
        color: white;
        font-weight: 600;
        font-size: 16px;
        box-shadow: 0 8px 24px rgba(0, 86, 179, 0.18);
        transition: transform .14s ease, box-shadow .14s ease, opacity .14s ease;
    }

    .btn-confirmar:hover {
        transform: translateY(-2px);
        opacity: 0.98;
    }
</style>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="page-bg">
    <div class="form-card">
        <div class="form-topbar">
            <div class="form-title">
                <i class="fas fa-hand-holding-usd"></i>
                <h2>Doação em Dinheiro</h2>
            </div>
            <a href="#" class="voltar" onclick="history.back(); return false;">Voltar</a>
        </div>

        <div class="form-body">
            <!-- Mostra saldo atual -->
            <div class="saldo-info">
                Saldo disponível: <strong>R$ <?php echo number_format($saldo_atual, 2, ',', '.'); ?></strong>
            </div>

            <form id="formDoacao" action="processar_formulario/processar_forms.php" method="POST"
                enctype="multipart/form-data">
                <input type="hidden" name="tipo" value="dinheiro">
                <input type="hidden" name="usuario_id" value="<?php echo $_SESSION['usuario_id']; ?>">
                <input type="hidden" id="saldoAtual" value="<?php echo number_format($saldo_atual, 2, '.', ''); ?>">

                <label for="imagem">Comprovante de Doação (opcional)</label>
                <input type="file" id="imagem" name="imagem" accept="image/*" onchange="previewImage(event)">
                <div class="preview-container" id="preview-container">
                    <img id="preview-img" alt="Pré-visualização da imagem">
                </div>

                <label for="valor">Valor da Doação (R$) <span style="color:red">*</span></label>
                <input id="valor" name="valor" type="text" placeholder="Ex: 50" required>

                <label for="endereco">Endereço <span style="color:red">*</span></label>
                <input id="endereco" name="endereco" type="text" placeholder="Rua, número, bairro..." required>

                <label for="telefone">Telefone <span style="color:red">*</span></label>
                <input id="telefone" name="telefone" type="tel" placeholder="(11) 99999-9999" required maxlength="15"
                    value="<?php echo htmlspecialchars($_SESSION['usuario_telefone'] ?? ''); ?>">

                <label for="email">E-mail</label>
                <input id="email" name="email" type="email"
                    value="<?php echo htmlspecialchars($_SESSION['usuario_email']); ?>" readonly>

                <label for="mensagem">Mensagem de apoio (opcional)</label>
                <textarea id="mensagem" name="mensagem" placeholder="Deixe uma mensagem de solidariedade..."></textarea>

                <button class="btn-confirmar" type="submit">
                    <i class="fas fa-paper-plane"></i>
                    Confirmar Doação
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Preview da imagem
    function previewImage(event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById('preview-container');
        const previewImg = document.getElementById('preview-img');

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                previewContainer.style.display = 'block';
                previewContainer.style.textAlign = 'center';
            }
            reader.readAsDataURL(file);
        } else {
            previewImg.src = "";
            previewContainer.style.display = 'none';
        }
    }

    // Máscara para telefone
    const telefoneInput = document.getElementById('telefone');
    telefoneInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);
        if (value.length > 6) {
            value = value.replace(/^(\d{2})(\d{5})(\d{0,4}).*/, '($1) $2-$3');
        } else if (value.length > 2) {
            value = value.replace(/^(\d{2})(\d{0,5}).*/, '($1) $2');
        } else if (value.length > 0) {
            value = value.replace(/^(\d*)/, '($1');
        }
        e.target.value = value;
    });

    // Validação do formulário
    document.getElementById('formDoacao').addEventListener('submit', function (e) {
        let valor = parseFloat(document.getElementById('valor').value.replace(',', '.'));
        const saldo = parseFloat(document.getElementById('saldoAtual').value);
        const endereco = document.getElementById('endereco').value.trim();
        const telefone = document.getElementById('telefone').value.trim();

        if (isNaN(valor) || valor <= 0) {
            e.preventDefault();
            alert('Por favor, insira um valor de doação válido.');
            return;
        }

        // ✅ Validação: apenas valores inteiros
        if (!Number.isInteger(valor)) {
            e.preventDefault();
            alert('Valores com centavos não são permitidos. Insira apenas valores inteiros, ex: 1, 2, 50.');
            return;
        }

        if (valor > saldo) {
            e.preventDefault();
            alert('Saldo insuficiente. Seu saldo atual é R$ ' + saldo.toFixed(2).replace('.', ','));
            return;
        }

        const enderecoValido = /\d/.test(endereco) && endereco.split(' ').length >= 2;
        if (!enderecoValido) {
            e.preventDefault();
            alert('Por favor, insira um endereço válido.');
            return;
        }

        if (telefone.length < 14) {
            e.preventDefault();
            alert('Por favor, insira um número de telefone válido.');
            return;
        }

        // Garante que o valor enviado seja inteiro
        document.getElementById('valor').value = Math.floor(valor);
    });
</script>