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
    <title>BibliOS - Empréstimos Realizados</title>

    <!-- Ícones Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS próprio da página de empréstimos -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <!-- Cabeçalho Superior -->
    <header class="header-emprestimos">
        <div class="header-esquerda">
            <!-- Botão para voltar ao Dashboard -->
            <a href="../pagina_inicial2/index.php" class="btn-voltar" title="Voltar ao Dashboard">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>EMPRÉSTIMOS REALIZADOS</h2>
        </div>

        <div class="header-direita">
            <span class="escola-badge">Escola Aprender Mais</span>
            <a href="../pagina_login1/logout.php" class="avatar" title="Clique para sair do sistema">
                <i class="fa-solid fa-user"></i>
            </a>
        </div>
    </header>

    <!-- Abas de Navegação (TODOS / EM ANDAMENTO) -->
    <div class="container-abas">
        <button class="aba-btn aba-ativa">TODOS</button>
        <button class="aba-btn">EM ANDAMENTO</button>
    </div>

    <hr class="linha-divisoria">

    <!-- Conteúdo Principal -->
    <main class="container-conteudo">

        <!-- Barra de Busca e Filtros -->
        <div class="barra-ferramentas">
            <div class="campo-busca">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Buscar por leitor, livro ou data...">
            </div>
            <button class="btn-filtros">
                <i class="fa-solid fa-filter"></i> Filtros
            </button>
        </div>

        <!-- Lista de Empréstimos -->
        <div class="lista-emprestimos">

            <!-- Item 1: No Prazo -->
            <div class="cartao-emprestimo">
                <div class="livro-info">
                    <div class="capa-box">
                        <i class="fa-solid fa-book-bookmark"></i>
                    </div>
                    <div class="livro-detalhes">
                        <strong>Dom Casmurro</strong>
                        <span class="autor">Machado de Assis</span>
                        <div class="info-linha">
                            <span><strong>Leitor:</strong> Maria Silva</span>
                            <span class="separador">&bull;</span>
                            <span><strong>Empréstimo:</strong> 10/10/2023</span>
                            <span class="separador">&bull;</span>
                            <span><strong>Devolução:</strong> 24/10/2023</span>
                        </div>
                    </div>
                </div>
                <div class="emprestimo-status">
                    <span class="badge-status no-prazo">No Prazo</span>
                    <i class="fa-solid fa-ellipsis-vertical menu-opcoes"></i>
                </div>
            </div>

            <!-- Item 2: Vence Hoje -->
            <div class="cartao-emprestimo">
                <div class="livro-info">
                    <div class="capa-box">
                        <i class="fa-solid fa-book"></i>
                    </div>
                    <div class="livro-detalhes">
                        <strong>1984</strong>
                        <span class="autor">George Orwell</span>
                        <div class="info-linha">
                            <span><strong>Leitor:</strong> João Santos</span>
                            <span class="separador">&bull;</span>
                            <span><strong>Empréstimo:</strong> 13/10/2023</span>
                            <span class="separador">&bull;</span>
                            <span><strong>Devolução:</strong> hoje</span>
                        </div>
                    </div>
                </div>
                <div class="emprestimo-status">
                    <span class="badge-status vence-hoje">Vence Hoje</span>
                    <i class="fa-solid fa-ellipsis-vertical menu-opcoes"></i>
                </div>
            </div>

            <!-- Item 3: Atrasado -->
            <div class="cartao-emprestimo">
                <div class="livro-info">
                    <div class="capa-box">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <div class="livro-detalhes">
                        <strong>O Pequeno Príncipe</strong>
                        <span class="autor">Antoine de Saint-Exupéry</span>
                        <div class="info-linha">
                            <span><strong>Leitor:</strong> Lucas Souza</span>
                            <span class="separador">&bull;</span>
                            <span><strong>Empréstimo:</strong> 01/10/2023</span>
                            <span class="separador">&bull;</span>
                            <span><strong>Devolução:</strong> 15/10/2023</span>
                        </div>
                    </div>
                </div>
                <div class="emprestimo-status">
                    <span class="badge-status atrasado">Atrasado</span>
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