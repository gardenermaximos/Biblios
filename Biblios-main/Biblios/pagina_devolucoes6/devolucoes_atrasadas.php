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
    <title>BibliOS - Devoluções Atrasadas</title>

    <!-- Ícones Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS próprio da página de devoluções atrasadas -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <!-- Cabeçalho Superior -->
    <header class="header-atrasos">
        <div class="header-esquerda">
            <!-- Botão para voltar ao Dashboard -->
            <a href="../pagina_inicial2/index.php" class="btn-voltar" title="Voltar ao Dashboard">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>DEVOLUÇÕES ATRASADAS</h2>
        </div>

        <div class="header-direita">
            <span class="escola-badge">Escola Aprender Mais</span>
            <a href="../pagina_login1/logout.php" class="avatar" title="Clique para sair do sistema">
                <i class="fa-solid fa-user"></i>
            </a>
        </div>
    </header>

    <!-- Banner de Alerta Vermelho -->
    <div class="banner-alerta">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span>Atenção: Existem empréstimos com devolução atrasada que exigem ação imediata.</span>
    </div>

    <!-- Conteúdo Principal -->
    <main class="container-conteudo">

        <!-- Barra de Busca e Filtros -->
        <div class="barra-ferramentas">
            <div class="campo-busca">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Buscar por leitor ou livro...">
            </div>
            <button class="btn-filtros">
                <i class="fa-solid fa-filter"></i> Filtros
            </button>
        </div>

        <!-- Lista de Devoluções Atrasadas -->
        <div class="lista-atrasos">

            <!-- Item 1 -->
            <div class="cartao-atraso">
                <div class="livro-info">
                    <div class="capa-box">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <div class="livro-detalhes">
                        <strong>O Pequeno Príncipe</strong>
                        <span class="isbn">ISBN: 978-85-01-23456-7</span>
                        <div class="info-linha">
                            <span><strong>Leitor:</strong> Ana Souza (Professora)</span>
                            <span class="separador">&bull;</span>
                            <span>Contato: (11) 97777-7777</span>
                            <span class="separador">&bull;</span>
                            <span>Devolução esperada: 15/10/2023</span>
                        </div>
                    </div>
                </div>
                <div class="atraso-status">
                    <span class="badge-atraso">5 dias de atraso</span>
                    <i class="fa-solid fa-ellipsis-vertical menu-opcoes"></i>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="cartao-atraso">
                <div class="livro-info">
                    <div class="capa-box">
                        <i class="fa-solid fa-book-bookmark"></i>
                    </div>
                    <div class="livro-detalhes">
                        <strong>Dom Casmurro</strong>
                        <span class="isbn">ISBN: 978-85-01-12345-6</span>
                        <div class="info-linha">
                            <span><strong>Leitor:</strong> Pedro Lima (Aluno)</span>
                            <span class="separador">&bull;</span>
                            <span>Contato: (11) 91111-2222</span>
                            <span class="separador">&bull;</span>
                            <span>Devolução esperada: 08/10/2023</span>
                        </div>
                    </div>
                </div>
                <div class="atraso-status">
                    <span class="badge-atraso">12 dias de atraso</span>
                    <i class="fa-solid fa-ellipsis-vertical menu-opcoes"></i>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="cartao-atraso">
                <div class="livro-info">
                    <div class="capa-box">
                        <i class="fa-solid fa-book"></i>
                    </div>
                    <div class="livro-detalhes">
                        <strong>1984</strong>
                        <span class="isbn">ISBN: 978-85-01-34567-8</span>
                        <div class="info-linha">
                            <span><strong>Leitor:</strong> Carla Dias (Aluna)</span>
                            <span class="separador">&bull;</span>
                            <span>Contato: (11) 93333-4444</span>
                            <span class="separador">&bull;</span>
                            <span>Devolução esperada: 18/10/2023</span>
                        </div>
                    </div>
                </div>
                <div class="atraso-status">
                    <span class="badge-atraso">2 dias de atraso</span>
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