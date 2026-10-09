<?php
// Inicia a sessão na primeira linha
session_start();

// Se o usuário NÃO estiver logado, sai da pasta pagina_inicial2 e vai para o login.php dentro de pagina_login1
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

    <!-- 1. Bibliotecas de Ícones (Font Awesome via link CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- 2. Nosso arquivo de estilos CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Barra de Navegação Superior -->
    <header class="topo-nav">
        <div class="logo-area">
            <!-- Ícone do Menu de Barras -->
            <i class="fa-solid fa-bars menu-icon"></i>
            <span class="titulo-nav">DASHBOARD</span>
        </div>
        <div class="user-area">
            <span class="escola-badge">Escola Aprender Mais</span>
            <div class="avatar">
                <!-- Ícone de Usuário -->
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

        <!-- Grade de Botões/Atalhos -->
        <div class="grid-dashboard">

            <!-- Card 1: Leitores -->
            <a href="leitores.php" class="card">
                <div class="badge-card">GERENCIAR LEITORES</div>
                <div class="icone-box">
                    <i class="fa-solid fa-users"></i>
                </div>
            </a>

            <!-- Card 2: Livros -->
            <a href="livros.php" class="card">
                <div class="badge-card">CATÁLOGO DE LIVROS</div>
                <div class="icone-box">
                    <i class="fa-solid fa-book"></i>
                </div>
            </a>

            <!-- Card 3: Empréstimos -->
            <a href="emprestimos.php" class="card">
                <div class="badge-card">NOVO EMPRÉSTIMO</div>
                <div class="icone-box">
                    <i class="fa-solid fa-handshake"></i>
                </div>
            </a>

            <!-- Card 4: Devoluções -->
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