<?php
session_start();

// Verifica se o utilizador está autenticado
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
    <title>BibliOS - Leitores Ativos</title>

    <!-- Ícones Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS próprio da pasta pagina_leitores3 -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <!-- Cabeçalho Superior -->
    <header class="header-leitores">
        <div class="header-esquerda">
            <!-- Botão para voltar ao Dashboard -->
            <a href="../pagina_inicial2/index.php" class="btn-voltar" title="Voltar ao Dashboard">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>LEITORES ATIVOS</h2>
        </div>

        <div class="header-direita">
            <!-- Link para a tela de Cadastro em pagina_gerenciar_leitores7 -->
            <a href="../pagina_gerenciar_leitores7/gerenciar_leitores.php" class="btn-adicionar">
                <i class="fa-solid fa-plus"></i> Adicionar
            </a>
            <span class="escola-badge">Escola Aprender Mais</span>
            <a href="../pagina_login1/logout.php" class="avatar" title="Clique para sair do sistema">
                <i class="fa-solid fa-user"></i>
            </a>
        </div>
    </header>

    <!-- Abas de Navegação (LISTA / CADASTRO) -->
    <div class="container-abas">
        <a href="leitores.php" class="aba-btn aba-ativa">LISTA</a>
        <!-- Clique em CADASTRO navega para a pasta do cadastro -->
        <a href="../pagina_gerenciar_leitores7/gerenciar_leitores.php" class="aba-btn">CADASTRO</a>
    </div>

    <hr class="linha-divisoria">

    <!-- Conteúdo Principal -->
    <main class="container-conteudo">

        <!-- Barra de Busca e Filtros -->
        <div class="barra-ferramentas">
            <div class="campo-busca">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Buscar leitor por nome, email ou matrícula...">
            </div>
            <button class="btn-filtros">
                <i class="fa-solid fa-filter"></i> Filtros
            </button>
        </div>

        <!-- Lista de Leitores -->
        <div class="lista-leitores">

            <!-- Leitor 1 -->
            <div class="cartao-leitor">
                <div class="leitor-info">
                    <div class="avatar-foto">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="leitor-detalhes">
                        <strong>Maria Silva</strong>
                        <span>maria@email.com &bull; (11) 99999-9999</span>
                    </div>
                </div>
                <div class="leitor-status">
                    <span class="badge-tipo badge-aluno">Aluno</span>
                    <span class="status-indicador"><i class="fa-solid fa-circle ponto-verde"></i> Ativo</span>
                    <i class="fa-solid fa-ellipsis-vertical menu-opcoes"></i>
                </div>
            </div>

            <!-- Leitor 2 -->
            <div class="cartao-leitor">
                <div class="leitor-info">
                    <div class="avatar-foto">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="leitor-detalhes">
                        <strong>João Santos</strong>
                        <span>joao@email.com &bull; (11) 98888-8888</span>
                    </div>
                </div>
                <div class="leitor-status">
                    <span class="badge-tipo badge-aluno">Aluno</span>
                    <span class="status-indicador"><i class="fa-solid fa-circle ponto-verde"></i> Ativo</span>
                    <i class="fa-solid fa-ellipsis-vertical menu-opcoes"></i>
                </div>
            </div>

            <!-- Leitor 3 -->
            <div class="cartao-leitor">
                <div class="leitor-info">
                    <div class="avatar-foto">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="leitor-detalhes">
                        <strong>Ana Souza</strong>
                        <span>ana@email.com &bull; (11) 97777-7777</span>
                    </div>
                </div>
                <div class="leitor-status">
                    <span class="badge-tipo badge-professor">Professor</span>
                    <span class="status-indicador"><i class="fa-solid fa-circle ponto-verde"></i> Ativo</span>
                    <i class="fa-solid fa-ellipsis-vertical menu-opcoes"></i>
                </div>
            </div>

        </div>

        <!-- Paginação -->
        <div class="paginacao">
            <button class="btn-pag">&lt;</button>
            <button class="btn-pag pag-ativo">1</button>
            <button class="btn-pag">2</button>
            <button class="btn-pag">3</button>
            <button class="btn-pag">&gt;</button>
        </div>

    </main>

</body>
</html>