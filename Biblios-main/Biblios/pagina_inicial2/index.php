<?php
// Inicia a sessão na primeira linha
session_start();

// Se o utilizador NÃO estiver autenticado, redireciona para a página de login
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
    <title>BibliOS - Dashboard</title>

    <!-- Bibliotecas de Ícones (Font Awesome via CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Força a atualização do CSS via versão dinânica -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <!-- Barra de Navegação Superior -->
    <header class="topo-nav">
        <div class="logo-area">
            <i class="fa-solid fa-bars menu-icon"></i>
            <span class="titulo-nav">DASHBOARD</span>
        </div>
        <div class="user-area">
            <span class="escola-badge">Escola Aprender Mais</span>
            <a href="../pagina_login1/logout.php" class="avatar" title="Clique para sair do sistema">
                <i class="fa-solid fa-user"></i>
            </a>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main class="container">

        <section class="boas-vindas">
            <h2>Bem-vindo!</h2>
            <p>Resumo do Sistema – Escola Aprender Mais</p>
        </section>

        <!-- Grade com os 8 Cards idênticos ao Figma -->
        <div class="grid-dashboard">

            <!-- Card 1: Leitores Ativos -->
            <a href="../pagina_leitores3/leitores.php" class="card">
                <div class="badge-card">LEITORES ATIVOS</div>
                <div class="icone-box">
                    <i class="fa-solid fa-users"></i>
                </div>
            </a>

            <!-- Card 2: LIVROS CADASTRADOS -->
            <a href="../pagina_livros4/livros_cadastrados.php" class="card">
                <div class="badge-card">LIVROS CADASTRADOS</div>
                <div class="icone-box">
                   <i class="fa-solid fa-book"></i>
                </div>
            </a>

            <!-- Card 3: EMPRÉSTIMOS REALIZADOS -->
            <a href="../pagina_emprestimos5/emprestimos.php" class="card">
                <div class="badge-card">EMPRÉSTIMOS REALIZADOS</div>
                <div class="icone-box">
                    <i class="fa-solid fa-handshake"></i>
                </div>
            </a>

            <!-- Card 4: DEVOLUÇÕES ATRASADAS -->
            <a href="../pagina_devolucoes6/devolucoes_atrasadas.php" class="card">
                <div class="badge-card">DEVOLUÇÕES ATRASADAS</div>
                <div class="icone-box">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
            </a>

            <!-- Card 5: GERENCIAR LEITORES -->
            <a href="../pagina_gerenciar_leitores7/gerenciar_leitores.php" class="card">
                <div class="badge-card">GERENCIAR LEITORES</div>
                <div class="icone-box">
                    <i class="fa-solid fa-user"></i>
                </div>
            </a>

            <!-- Card 6: CATÁLOGO DE LIVROS -->
            <a href="../pagina_catalogo8/catalogo.php" class="card">
                <div class="badge-card">CATÁLOGO DE LIVROS</div>
                <div class="icone-box">
                    <i class="fa-solid fa-book-open"></i>
                </div>
            </a>

            <!-- Card 7: NOVO EMPRÉSTIMO -->
            <a href="../pagina_novo_emprestimo9/novo_emprestimo.php" class="card">
                <div class="badge-card">NOVO EMPRÉSTIMO</div>
                <div class="icone-box">
                    <i class="fa-solid fa-handshake"></i>
                </div>
            </a>

            <!-- Card 8: Registrar Devolução -->
            <a href="../pagina_registrar_devolucao10/registrar_devolucao.php" class="card">
                <div class="badge-card">REGISTRAR DEVOLUÇÃO</div>
                <div class="icone-box">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </div>
            </a>

        </div>

    </main>

</body>
</html>