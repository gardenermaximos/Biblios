<?php
session_start();

// Verifica autenticação
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../pagina_login1/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BibliOS - Registrar Devolução</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- CSS Próprio -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <!-- Cabeçalho Superior -->
    <header class="header-devolucao">
        <div class="header-esquerda">
            <a href="../pagina_inicial2/index.php" class="btn-voltar" title="Voltar ao Dashboard">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>REGISTRAR DEVOLUÇÃO</h2>
        </div>

        <div class="header-direita">
            <span class="escola-badge">Escola Aprender Mais</span>
            <a href="../pagina_login1/logout.php" class="avatar" title="Clique para sair do sistema">
                <i class="fa-solid fa-user"></i>
            </a>
        </div>
    </header>

    <hr class="linha-divisoria">

    <!-- Conteúdo Principal -->
    <main class="container-conteudo">

        <div class="card-formulario">
            
            <div class="form-cabecalho">
                <h3>Registrar Devolução</h3>
                <p>Finalize empréstimos em aberto dando baixa rápida de retorno físico ao acervo</p>
            </div>

            <form action="../pagina_emprestimos5/emprestimos.php" method="POST" class="form-devolucao">
                
                <!-- Empréstimo Ativo -->
                <div class="campo-form">
                    <label for="emprestimo">Empréstimo Ativo *</label>
                    <select id="emprestimo" name="emprestimo" required>
                        <option value="" disabled selected>Selecione o empréstimo em andamento</option>
                        <option value="1">Dom Casmurro — Leitor: Maria Silva (10/10/2023)</option>
                        <option value="2">1984 — Leitor: João Santos (13/10/2023)</option>
                        <option value="3">O Pequeno Príncipe — Leitor: Lucas Souza (01/10/2023)</option>
                    </select>
                </div>

                <!-- Data de Devolução -->
                <div class="campo-form">
                    <label for="data_devolucao">Data de Devolução *</label>
                    <input type="text" id="data_devolucao" name="data_devolucao" placeholder="DD/MM/AAAA" onfocus="(this.type='date')" onblur="(this.type='text')" required>
                </div>

                <!-- Observações -->
                <div class="campo-form">
                    <label for="observacoes">Observações</label>
                    <textarea id="observacoes" name="observacoes" rows="4" placeholder="Digite observações (opcional)..."></textarea>
                </div>

                <!-- Botões de Ação -->
                <div class="botoes-form">
                    <a href="../pagina_inicial2/index.php" class="btn-cancelar">CANCELAR</a>
                    <button type="submit" class="btn-salvar">DAR BAIXA</button>
                </div>

            </form>

        </div>

    </main>

</body>
</html>