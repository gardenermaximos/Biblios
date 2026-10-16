<?php
// Inicia a sessão na primeira linha
session_start();

// Se o usuário NÃO estiver logado, redireciona para a página de login
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

    <!-- Nosso arquivo de estilos CSS -->
<!-- Força o navegador a carregar sempre a versão mais recente do CSS -->
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
            <div class="avatar">
                <i class="fa-solid fa-user"></i>
            </div>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main class="container">

        <section class="boas-vindas">
            <h2>Bem-vindo!</h2>
            <p>Resumo do Sistema – Escola Aprender Mais</p>
        </section>

        <!-- Grade com os 8 Cards do Figma -->
        <div class="grid-dashboard">

            <!-- --- PRIMEIRA FILEIRA (INDICADORES / CONSULTAS) --- -->

            <!-- Card 1: Leitores Ativos -->
            <a href="../pagina_leitores3/leitores.php" class="card">
                <div class="badge-card">LEITORES ATIVOS</div>
                <div class="icone-box">
                    <i class="fa-solid fa-users"></i>
                </div>
            </a>

            <!-- Card 2: Livros Cadastrados -->
            <a href="livros.php" class="card">
                <div class="badge-card">LIVROS CADASTRADOS</div>
                <div class="icone-box">
                    <i class="fa-solid fa-book"></i>
                </div>
            </a>

            <!-- Card 3: Empréstimos Realizados -->
            <a href="emprestimos.php" class="card">
                <div class="badge-card">EMPRÉSTIMOS REALIZADOS</div>
                <div class="icone-box">
                    <i class="fa-solid fa-handshake"></i>
                </div>
            </a>

            <!-- Card 4: Devoluções Atrasadas -->
            <a href="devolucoes.php" class="card">
                <div class="badge-card">DEVOLUÇÕES ATRASADAS</div>
                <div class="icone-box">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
            </a>

            <!-- --- SEGUNDA FILEIRA (AÇÕES / GERENCIAMENTO) --- -->

            <!-- Card 5: Gerenciar Leitores -->
            <a href="gerenciar_leitores.php" class="card">
                <div class="badge-card">GERENCIAR LEITORES</div>
                <div class="icone-box">
                    <i class="fa-solid fa-user"></i>
                </div>
            </a>

            <!-- Card 6: Catálogo de Livros -->
            <a href="livros.php" class="card">
                <div class="badge-card">CATÁLOGO DE LIVROS</div>
                <div class="icone-box">
                    <i class="fa-solid fa-book-open"></i>
                </div>
            </a>

            <!-- Card 7: Novo Empréstimo -->
            <a href="novo_emprestimo.php" class="card">
                <div class="badge-card">NOVO EMPRÉSTIMO</div>
                <div class="icone-box">
                    <i class="fa-solid fa-handshake"></i>
                </div>
            </a>

            <!-- Card 8: Registrar Devolução -->
            <a href="devolucoes.php" class="card">
                <div class="badge-card">REGISTRAR DEVOLUÇÃO</div>
                <div class="icone-box">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </div>
            </a>

        </div>

    </main>

</body>
</html>
