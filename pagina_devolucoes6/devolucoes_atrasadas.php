<?php
session_start();

// Inclui a conexão com o banco de dados db_biblios
require_once __DIR__ . '/../conexao.php';

// Verifica se o utilizador está autenticado
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../pagina_login1/login.php");
    exit();
}

// Termo de busca digitado
$termo_busca = trim($_GET['busca'] ?? '');

try {
    // Consulta SQL que traz apenas empréstimos NÃO devolvidos e vencidos (prazo > 14 dias)
    $sql = "SELECT e.*, l.titulo, l.autor, le.nome_leitor, le.telefone, le.tipo_leitor,
                   DATE_ADD(e.data_emprestimo, INTERVAL 14 DAY) AS data_prevista,
                   DATEDIFF(NOW(), DATE_ADD(e.data_emprestimo, INTERVAL 14 DAY)) AS dias_atraso
            FROM tb_emprestimos e
            INNER JOIN tb_livros l ON e.fk_livro = l.id_livro
            INNER JOIN tb_leitores le ON e.fk_leitor = le.id_leitor
            WHERE e.data_devolucao IS NULL 
              AND NOW() > DATE_ADD(e.data_emprestimo, INTERVAL 14 DAY)";

    $params = [];

    // Filtra pelo termo buscado caso informado
    if (!empty($termo_busca)) {
        $sql .= " AND (le.nome_leitor LIKE :busca OR l.titulo LIKE :busca OR l.autor LIKE :busca)";
        $params[':busca'] = '%' . $termo_busca . '%';
    }

    $sql .= " ORDER BY dias_atraso DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $atrasados = $stmt->fetchAll();

    $total_atrasos = count($atrasados);

} catch (PDOException $e) {
    $atrasados = [];
    $total_atrasos = 0;
    $erro_banco = "Erro ao carregar devoluções atrasadas: " . $e->getMessage();
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
    <a href="../pagina_login1/logout.php" 
       class="avatar" 
       title="Sair do Sistema" 
       onclick="return confirm('Tem certeza que deseja sair do sistema?');">
        <i class="fa-solid fa-door-open"></i>
    </a>
</div>
    </header>

    <!-- Banner de Alerta Vermelho Destaque -->
    <div class="banner-alerta">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span>Atenção: Existem <?php echo $total_atrasos; ?> empréstimo(s) com devolução atrasada que exigem ação imediata.</span>
    </div>

    <!-- Conteúdo Principal -->
    <main class="container-conteudo">

        <!-- Barra de Busca e Filtros -->
        <form method="GET" action="devolucoes_atrasadas.php" class="barra-ferramentas">
            <div class="campo-busca">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="busca" value="<?php echo htmlspecialchars($termo_busca); ?>" placeholder="Buscar atraso por leitor ou livro...">
            </div>
            <button type="submit" class="btn-filtros">
                <i class="fa-solid fa-filter"></i> Buscar
            </button>
            <?php if (!empty($termo_busca)): ?>
                <a href="devolucoes_atrasadas.php" class="btn-filtros" style="text-decoration: none; color: #8d5b55;">Limpar</a>
            <?php endif; ?>
        </form>

        <!-- Lista de Devoluções Atrasadas Vinda do MySQL -->
        <div class="lista-atrasos">

            <?php if (!empty($atrasados)): ?>
                <?php foreach ($atrasados as $item): ?>
                    <?php 
                        $dt_prevista = new DateTime($item['data_prevista']);
                        $dias_atraso = (int)$item['dias_atraso'];
                    ?>
                    <div class="cartao-atraso">
                        <div class="livro-info">
                            <div class="capa-box">
                                <i class="fa-solid fa-book-bookmark"></i>
                            </div>
                            <div class="livro-detalhes">
                                <strong><?php echo htmlspecialchars($item['titulo']); ?></strong>
                                <span class="isbn">Autor: <?php echo htmlspecialchars($item['autor']); ?></span>
                                <div class="info-linha">
                                    <span><strong>Leitor:</strong> <?php echo htmlspecialchars($item['nome_leitor']); ?> (<?php echo htmlspecialchars($item['tipo_leitor']); ?>)</span>
                                    <span class="separador">&bull;</span>
                                    <span>Contato: <?php echo htmlspecialchars($item['telefone']); ?></span>
                                    <span class="separador">&bull;</span>
                                    <span>Esperado: <?php echo $dt_prevista->format('d/m/Y'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="atraso-status">
                            <span class="badge-atraso"><?php echo $dias_atraso; ?> dia(s) de atraso</span>
                            <a href="../pagina_registrar_devolucao10/registrar_devolucao.php?id=<?php echo $item['id_emprestimo']; ?>" title="Registrar Devolução" style="color: #888888; text-decoration: none;">
                                <i class="fa-solid fa-arrows-rotate menu-opcoes"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: #10b981; padding: 30px; background: #fff; border-radius: 12px; font-weight: bold;">
                    <i class="fa-solid fa-circle-check"></i> Excelente! Nenhuma devolução em atraso no momento.
                </p>
            <?php endif; ?>

        </div>

        <!-- Paginação -->
        <div class="paginacao">
            <button class="btn-pag">&lt;</button>
            <button class="btn-pag pag-ativo">1</button>
            <button class="btn-pag">&gt;</button>
        </div>

    </main>

</body>
</html>