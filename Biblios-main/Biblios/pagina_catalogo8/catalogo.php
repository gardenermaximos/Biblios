<?php
session_start();

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
    <title>BibliOS - Catálogo de Livros</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <!-- Cabeçalho Superior -->
    <header class="header-catalogo">
        <div class="header-esquerda">
            <a href="../pagina_inicial2/index.php" class="btn-voltar" title="Voltar ao Dashboard">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>CATÁLOGO DE LIVROS</h2>
        </div>

        <div class="header-direita">
            <a href="cadastrar_livro.php" class="btn-adicionar">
                <i class="fa-solid fa-plus"></i> Adicionar
            </a>
            <span class="escola-badge">Escola Aprender Mais</span>
            <a href="../pagina_login1/logout.php" class="avatar" title="Clique para sair do sistema">
                <i class="fa-solid fa-user"></i>
            </a>
        </div>
    </header>

    <!-- Abas LISTA / CADASTRO -->
    <div class="container-abas">
        <a href="catalogo.php" class="aba-btn aba-ativa">LISTA</a>
        <a href="cadastrar_livro.php" class="aba-btn">CADASTRO</a>
    </div>

    <hr class="linha-divisoria">

    <!-- Conteúdo Principal -->
    <main class="container-conteudo">

        <div class="barra-ferramentas">
            <div class="campo-busca">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Buscar livro por título, autor, gênero ou código ISBN...">
            </div>
            <button class="btn-filtros">
                <i class="fa-solid fa-filter"></i> Filtros
            </button>
        </div>

        <!-- Lista de Livros -->
        <div class="lista-livros">

            <!-- Item 1 -->
            <div class="cartao-livro">
                <div class="livro-info">
                    <div class="capa-box">
                        <i class="fa-solid fa-book-bookmark"></i>
                    </div>
                    <div class="livro-detalhes">
                        <strong>Dom Casmurro</strong>
                        <span class="autor">Machado de Assis</span>
                        <span class="isbn">ISBN: 978-85-01-12345-6</span>
                    </div>
                </div>
                <div class="livro-status">
                    <span class="badge-genero">Clássico Nacional</span>
                    <span class="badge-status disponivel">Disponível</span>
                    <i class="fa-solid fa-ellipsis-vertical menu-opcoes"></i>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="cartao-livro">
                <div class="livro-info">
                    <div class="capa-box">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <div class="livro-detalhes">
                        <strong>O Pequeno Príncipe</strong>
                        <span class="autor">Antoine de Saint-Exupéry</span>
                        <span class="isbn">ISBN: 978-85-01-23456-7</span>
                    </div>
                </div>
                <div class="livro-status">
                    <span class="badge-genero">Literatura Estrangeira</span>
                    <span class="badge-status disponivel">Disponível</span>
                    <i class="fa-solid fa-ellipsis-vertical menu-opcoes"></i>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="cartao-livro">
                <div class="livro-info">
                    <div class="capa-box">
                        <i class="fa-solid fa-book"></i>
                    </div>
                    <div class="livro-detalhes">
                        <strong>1984</strong>
                        <span class="autor">George Orwell</span>
                        <span class="isbn">ISBN: 978-85-01-34567-8</span>
                    </div>
                </div>
                <div class="livro-status">
                    <span class="badge-genero">Distopia</span>
                    <span class="badge-status disponivel">Disponível</span>
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