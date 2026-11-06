<?php
session_start();

require_once __DIR__ . '/../conexao.php';

if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../pagina_login1/login.php");
    exit();
}

$nome_usuario = $_SESSION['usuario_logado'] ?? 'Bibliotecário';

try {
    // 1. Contagem de Leitores Ativos (status = 1)
    $stmtLeitoresAtivos = $pdo->query("SELECT COUNT(*) FROM tb_leitores WHERE status = 1");
    $total_leitores_ativos = $stmtLeitoresAtivos->fetchColumn();

    // 2. Contagem do Acervo Local Físico
    $stmtLivrosLocais = $pdo->query("SELECT COUNT(*) FROM tb_livros WHERE origem = 'Acervo Local'");
    $total_livros_locais = $stmtLivrosLocais->fetchColumn();

    // 3. Contagem Total do Catálogo Geral
    $stmtCatalogo = $pdo->query("SELECT COUNT(*) FROM tb_livros");
    $total_catalogo = $stmtCatalogo->fetchColumn();

    // 4. Contagem de Empréstimos Ativos
    $stmtEmprestimos = $pdo->query("SELECT COUNT(*) FROM tb_emprestimos WHERE data_devolucao IS NULL");
    $total_emprestimos = $stmtEmprestimos->fetchColumn();

    // 5. Contagem de Devoluções Atrasadas
    $stmtAtrasados = $pdo->query("SELECT COUNT(*) FROM tb_emprestimos WHERE data_devolucao IS NULL AND data_emprestimo < NOW() - INTERVAL 14 DAY");
    $total_atrasados = $stmtAtrasados->fetchColumn();

} catch (PDOException $e) {
    $total_leitores_ativos = 0;
    $total_livros_locais   = 0;
    $total_catalogo        = 0;
    $total_emprestimos     = 0;
    $total_atrasados       = 0;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BibliOS - Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <header class="topo-nav">
        <div class="logo-area">
            <span class="titulo-nav">DASHBOARD</span>
        </div>
        <div class="user-area">
    <span class="escola-badge">Escola Aprender Mais</span>
    <!-- Botão com ícone de porta e confirmação ao clicar -->
    <a href="../pagina_login1/logout.php" 
       class="avatar" 
       title="Sair do Sistema" 
       onclick="return confirm('Tem certeza que deseja sair do sistema?');">
        <i class="fa-solid fa-door-open"></i>
    </a>
</div>
    </header>

    <main class="container">

        <section class="boas-vindas">
            <h2>Bem-vindo(a), <?php echo htmlspecialchars($nome_usuario); ?>!</h2>
            <p>Resumo em tempo real do acervo e leitores – Escola Aprender Mais</p>
        </section>

        <div class="grid-dashboard">

            <!-- Card 1: LISTA DE LEITORES (Consulta, Edição e Exclusão) -->
            <a href="../pagina_leitores3/leitores.php" class="card">
                <div class="badge-card">LEITORES CADASTRADOS (<?php echo $total_leitores_ativos; ?>)</div>
                <div class="icone-box">
                    <i class="fa-solid fa-users"></i>
                </div>
            </a>

            <!-- Card 2: ACERVO FÍSICO LOCAL -->
            <a href="../pagina_livros4/livros_cadastrados.php" class="card">
                <div class="badge-card">ACERVO LOCAL (<?php echo $total_livros_locais; ?>)</div>
                <div class="icone-box">
                   <i class="fa-solid fa-book"></i>
                </div>
            </a>

            <!-- Card 3: EMPRÉSTIMOS ATIVOS -->
            <a href="../pagina_emprestimos5/emprestimos.php?aba=andamento" class="card">
                <div class="badge-card">EMPRÉSTIMOS ATIVOS (<?php echo $total_emprestimos; ?>)</div>
                <div class="icone-box">
                    <i class="fa-solid fa-handshake"></i>
                </div>
            </a>

            <!-- Card 4: DEVOLUÇÕES ATRASADAS -->
            <a href="../pagina_devolucoes6/devolucoes_atrasadas.php" class="card">
                <div class="badge-card">ATRASADOS (<?php echo $total_atrasados; ?>)</div>
                <div class="icone-box">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
            </a>

            <!-- Card 5: CADASTRAR NOVO LEITOR (Formulário Direto) -->
            <a href="../pagina_gerenciar_leitores7/gerenciar_leitores.php" class="card">
                <div class="badge-card">CADASTRAR NOVO LEITOR</div>
                <div class="icone-box">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
            </a>

            <!-- Card 6: CATÁLOGO GERAL -->
            <a href="../pagina_catalogo8/catalogo.php" class="card">
                <div class="badge-card">CATÁLOGO GERAL (<?php echo $total_catalogo; ?>)</div>
                <div class="icone-box">
                    <i class="fa-solid fa-book-open"></i>
                </div>
            </a>

            <!-- Card 7: NOVO EMPRÉSTIMO -->
            <a href="../pagina_novo_emprestimo9/novo_emprestimo.php" class="card">
                <div class="badge-card">NOVO EMPRÉSTIMO</div>
                <div class="icone-box">
                    <i class="fa-solid fa-handshake-angle"></i>
                </div>
            </a>

            <!-- Card 8: REGISTRAR DEVOLUÇÃO -->
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