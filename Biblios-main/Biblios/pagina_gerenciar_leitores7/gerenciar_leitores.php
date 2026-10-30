<?php
session_start();

// Redireciona para o login caso não esteja autenticado
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
    <title>BibliOS - Gerenciar Leitores</title>

    <!-- Ícones Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS próprio da página de Gerenciar Leitores -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <!-- Cabeçalho Superior -->
    <header class="header-gerenciar">
        <div class="header-esquerda">
            <!-- Botão para voltar ao Dashboard -->
            <a href="../pagina_inicial2/index.php" class="btn-voltar" title="Voltar ao Dashboard">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>GERENCIAR LEITORES</h2>
        </div>

        <div class="header-direita">
            <span class="escola-badge">Escola Aprender Mais</span>
            <a href="../pagina_login1/logout.php" class="avatar" title="Clique para sair do sistema">
                <i class="fa-solid fa-user"></i>
            </a>
        </div>
    </header>

    <!-- Abas de Navegação -->
    <div class="container-abas">
        <a href="../pagina_leitores3/leitores.php" class="aba-btn">LISTA</a>
        <a href="gerenciar_leitores.php" class="aba-btn aba-ativa">CADASTRO</a>
    </div>

    <hr class="linha-divisoria">

    <!-- Conteúdo Principal - Formulário de Cadastro -->
    <main class="container-conteudo">

        <div class="card-formulario">
            
            <div class="form-cabecalho">
                <h3>Cadastrar Novo Leitor</h3>
                <p>Preencha os dados do aluno ou professor para conceder acesso ao acervo</p>
            </div>

            <form action="gerenciar_leitores.php" method="POST" class="form-cadastro">
                
                <div class="linha-form">
                    <!-- Nome Completo -->
                    <div class="campo-form">
                        <label for="nome">Nome Completo *</label>
                        <input type="text" id="nome" name="nome" placeholder="Digite o nome completo do leitor" required>
                    </div>

                    <!-- E-mail Institucional -->
                    <div class="campo-form">
                        <label for="email">E-mail Institucional *</label>
                        <input type="email" id="email" name="email" placeholder="exemplo@escola.com" required>
                    </div>
                </div>

                <div class="linha-form">
                    <!-- Telefone de Contato -->
                    <div class="campo-form">
                        <label for="telefone">Telefone de Contato</label>
                        <input type="text" id="telefone" name="telefone" placeholder="(11) 99999-9999">
                    </div>

                    <!-- Tipo de Cadastro -->
                    <div class="campo-form">
                        <label for="tipo">Tipo de Cadastro *</label>
                        <select id="tipo" name="tipo" required>
                            <option value="Aluno">Aluno</option>
                            <option value="Professor">Professor</option>
                            <option value="Funcionário">Funcionário</option>
                        </select>
                    </div>
                </div>

                <!-- Status Inicial -->
                <div class="campo-form-status">
                    <div class="status-texto">
                        <strong>Status Inicial do Cadastro</strong>
                        <p>Defina se o leitor já iniciará ativo para empréstimos</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="status" checked>
                        <span class="slider round"></span>
                    </label>
                </div>

                <!-- Botões de Ação -->
                <div class="botoes-form">
                    <a href="../pagina_inicial2/index.php" class="btn-cancelar">CANCELAR</a>
                    <button type="submit" class="btn-salvar">SALVAR CADASTRO</button>
                </div>

            </form>

        </div>

    </main>

</body>
</html>