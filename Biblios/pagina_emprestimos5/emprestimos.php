<?php
session_start();

require_once __DIR__ . '/../conexao.php';

if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../pagina_login1/index.php");
    exit();
}

$aba = $_GET['aba'] ?? 'todos';
$termo_busca = trim($_GET['busca'] ?? '');
$mensagem_status = $_GET['status_msg'] ?? '';

try {
    $sql = "SELECT e.*, l.titulo, l.autor, le.nome_leitor 
            FROM tb_emprestimos e
            INNER JOIN tb_livros l ON e.fk_livro = l.id_livro
            INNER JOIN tb_leitores le ON e.fk_leitor = le.id_leitor
            WHERE 1=1";

    $params = [];

    if ($aba === 'andamento') {
        $sql .= " AND e.data_devolucao IS NULL";
    }

    if (!empty($termo_busca)) {
        $sql .= " AND (le.nome_leitor LIKE :busca OR l.titulo LIKE :busca OR l.autor LIKE :busca)";
        $params[':busca'] = '%' . $termo_busca . '%';
    }

    $sql .= " ORDER BY e.id_emprestimo DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $emprestimos = $stmt->fetchAll();

} catch (PDOException $e) {
    $emprestimos = [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BibliOS - Empréstimos Realizados</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <header class="header-emprestimos">
        <div class="header-esquerda">
            <a href="../pagina_inicial2/home.php" class="btn-voltar" title="Voltar ao Dashboard">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>EMPRÉSTIMOS REALIZADOS</h2>
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

    <div class="container-abas">
        <a href="emprestimos.php?aba=todos<?php echo !empty($termo_busca) ? '&busca=' . urlencode($termo_busca) : ''; ?>" 
           class="aba-btn <?php echo ($aba === 'todos') ? 'aba-ativa' : ''; ?>" style="text-decoration: none;">TODOS</a>
        
        <a href="emprestimos.php?aba=andamento<?php echo !empty($termo_busca) ? '&busca=' . urlencode($termo_busca) : ''; ?>" 
           class="aba-btn <?php echo ($aba === 'andamento') ? 'aba-ativa' : ''; ?>" style="text-decoration: none;">EM ANDAMENTO</a>
    </div>

    <hr class="linha-divisoria">

    <main class="container-conteudo">

        <!-- Feedbacks de Alerta -->
        <?php if ($mensagem_status === 'concluido'): ?>
            <div style="background-color: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: bold;">
                <i class="fa-solid fa-circle-check"></i> Devolução registada e livro devolvido ao acervo!
            </div>
        <?php elseif ($mensagem_status === 'editado'): ?>
            <div style="background-color: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: bold;">
                <i class="fa-solid fa-circle-check"></i> Empréstimo atualizado com sucesso!
            </div>
        <?php endif; ?>

        <form method="GET" action="emprestimos.php" class="barra-ferramentas">
            <input type="hidden" name="aba" value="<?php echo htmlspecialchars($aba); ?>">
            <div class="campo-busca">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="busca" value="<?php echo htmlspecialchars($termo_busca); ?>" placeholder="Buscar por leitor, livro ou autor...">
            </div>
            <button type="submit" class="btn-filtros"><i class="fa-solid fa-filter"></i> Buscar</button>
            <?php if (!empty($termo_busca)): ?>
                <a href="emprestimos.php?aba=<?php echo urlencode($aba); ?>" class="btn-filtros" style="text-decoration: none; color: #8d5b55;">Limpar</a>
            <?php endif; ?>
        </form>

        <div class="lista-emprestimos">
            <?php if (!empty($emprestimos)): ?>
                <?php foreach ($emprestimos as $emp): ?>
                    <?php 
                        $dt_emp = new DateTime($emp['data_emprestimo']);
                        $dt_prev = clone $dt_emp;
                        $dt_prev->modify('+14 days');
                        $hoje = new DateTime('today');
                        
                        $is_concluido = ($emp['data_devolucao'] !== null);

                        if ($is_concluido) {
                            $status_texto  = 'Concluído';
                            $status_classe = 'no-prazo';
                            $dt_dev_str    = (new DateTime($emp['data_devolucao']))->format('d/m/Y');
                        } else {
                            $dt_dev_str = $dt_prev->format('d/m/Y');
                            if ($hoje > $dt_prev) {
                                $status_texto  = 'Atrasado';
                                $status_classe = 'atrasado';
                            } elseif ($hoje->format('Y-m-d') === $dt_prev->format('Y-m-d')) {
                                $status_texto  = 'Vence Hoje';
                                $status_classe = 'vence-hoje';
                            } else {
                                $status_texto  = 'No Prazo';
                                $status_classe = 'no-prazo';
                            }
                        }
                    ?>
                    <div class="cartao-emprestimo">
                        <div class="livro-info">
                            <div class="capa-box">
                                <i class="fa-solid fa-book-bookmark"></i>
                            </div>
                            <div class="livro-detalhes">
                                <strong><?php echo htmlspecialchars($emp['titulo']); ?></strong>
                                <span class="autor"><?php echo htmlspecialchars($emp['autor']); ?></span>
                                <div class="info-linha">
                                    <span><strong>Leitor:</strong> <?php echo htmlspecialchars($emp['nome_leitor']); ?></span>
                                    <span class="separador">&bull;</span>
                                    <span><strong>Saída:</strong> <?php echo $dt_emp->format('d/m/Y'); ?></span>
                                    <span class="separador">&bull;</span>
                                    <span><strong>Devolução:</strong> <?php echo $dt_dev_str; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="emprestimo-status">
                            <span class="badge-status <?php echo $status_classe; ?>"><?php echo $status_texto; ?></span>
                            
                            <!-- Botões de Ação: Concluir Rápido & Editar -->
                            <div class="acoes-emprestimo" style="display: flex; gap: 12px; margin-left: 10px;">
                                <?php if (!$is_concluido): ?>
                                    <a href="concluir_emprestimo.php?id=<?php echo $emp['id_emprestimo']; ?>" 
                                       onclick="return confirm('Confirmar a devolução deste livro ao acervo?');" 
                                       title="Dar Baixa / Concluir" style="color: #10b981; font-size: 1.2rem;">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="editar_emprestimo.php?id=<?php echo $emp['id_emprestimo']; ?>" title="Editar Empréstimo" style="color: #8d5b55; font-size: 1.1rem;">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: #777; padding: 30px; background: #fff; border-radius: 12px;">Nenhum empréstimo encontrado.</p>
            <?php endif; ?>
        </div>

    </main>

</body>
</html>