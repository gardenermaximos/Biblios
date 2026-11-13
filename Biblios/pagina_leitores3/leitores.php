<?php
session_start();

require_once __DIR__ . '/../conexao.php';

if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../pagina_login1/index.php");
    exit();
}

$termo_busca     = trim($_GET['busca'] ?? '');
$filtro_status   = $_GET['status'] ?? 'todos'; // 'todos', '1' (ativos), '0' (inativos)
$mensagem_status = $_GET['status_msg'] ?? '';

try {
    $sql = "SELECT * FROM tb_leitores WHERE 1=1";
    $params = [];

    // Filtro por Status (Ativos / Inativos)
    if ($filtro_status === '1') {
        $sql .= " AND status = 1";
    } elseif ($filtro_status === '0') {
        $sql .= " AND status = 0";
    }

    // Filtro por Termo de Busca
    if (!empty($termo_busca)) {
        $sql .= " AND (nome_leitor LIKE :busca OR email LIKE :busca OR telefone LIKE :busca)";
        $params[':busca'] = '%' . $termo_busca . '%';
    }

    $sql .= " ORDER BY id_leitor DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $leitores = $stmt->fetchAll();

} catch (PDOException $e) {
    $leitores = [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BibliOS - Leitores</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <header class="header-leitores">
        <div class="header-esquerda">
            <a href="../pagina_inicial2/home.php" class="btn-voltar" title="Voltar ao Dashboard">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>
                <?php 
                    if ($filtro_status === '1') echo "LEITORES ATIVOS";
                    elseif ($filtro_status === '0') echo "LEITORES INATIVOS";
                    else echo "TODOS OS LEITORES CADASTRADOS";
                ?>
            </h2>
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

    <!-- Abas de Navegação e Filtros de Status -->
    <div class="container-abas">
        <a href="leitores.php?status=todos" class="aba-btn <?php echo ($filtro_status === 'todos') ? 'aba-ativa' : ''; ?>">TODOS</a>
        <a href="leitores.php?status=1" class="aba-btn <?php echo ($filtro_status === '1') ? 'aba-ativa' : ''; ?>">LEITORES ATIVOS</a>
        <a href="leitores.php?status=0" class="aba-btn <?php echo ($filtro_status === '0') ? 'aba-ativa' : ''; ?>">INATIVOS</a>
        <a href="../pagina_gerenciar_leitores7/gerenciar_leitores.php" class="aba-btn">+ CADASTRO</a>
    </div>

    <hr class="linha-divisoria">

    <main class="container-conteudo">

        <!-- Feedbacks do Sistema -->
        <?php if ($mensagem_status === 'excluido'): ?>
            <div style="background-color: #d1fae5; color: #065f46; padding: 14px 18px; border-radius: 12px; margin-bottom: 22px; font-weight: bold; font-size: 1.05rem;">
                <i class="fa-solid fa-circle-check"></i> Leitor excluído com sucesso!
            </div>
        <?php elseif ($mensagem_status === 'erro_vinculo'): ?>
            <div style="background-color: #fee2e2; color: #991b1b; padding: 14px 18px; border-radius: 12px; margin-bottom: 22px; font-weight: bold; font-size: 1.05rem;">
                <i class="fa-solid fa-triangle-exclamation"></i> Impossível excluir leitor: existem registos de empréstimo associados a esta pessoa.
            </div>
        <?php elseif ($mensagem_status === 'editado'): ?>
            <div style="background-color: #d1fae5; color: #065f46; padding: 14px 18px; border-radius: 12px; margin-bottom: 22px; font-weight: bold; font-size: 1.05rem;">
                <i class="fa-solid fa-circle-check"></i> Dados do leitor atualizados com sucesso!
            </div>
        <?php endif; ?>

        <!-- Form de Busca -->
        <form method="GET" action="leitores.php" class="barra-ferramentas">
            <input type="hidden" name="status" value="<?php echo htmlspecialchars($filtro_status); ?>">
            <div class="campo-busca">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="busca" value="<?php echo htmlspecialchars($termo_busca); ?>" placeholder="Buscar por nome, email ou telefone...">
            </div>
            <button type="submit" class="btn-filtros"><i class="fa-solid fa-filter"></i> Buscar</button>
            <?php if (!empty($termo_busca)): ?>
                <a href="leitores.php?status=<?php echo urlencode($filtro_status); ?>" class="btn-filtros" style="text-decoration: none; color: #8d5b55;">Limpar</a>
            <?php endif; ?>
        </form>

        <!-- Lista de Leitores -->
        <div class="lista-leitores">
            <?php if (!empty($leitores)): ?>
                <?php foreach ($leitores as $leitor): ?>
                    <?php 
                        $tipo_classe = (strtolower($leitor['tipo_leitor']) === 'professor') ? 'badge-professor' : 'badge-aluno';
                        $status_cor  = ($leitor['status'] == 1) ? 'ponto-verde' : 'ponto-vermelho';
                        $status_txt  = ($leitor['status'] == 1) ? 'Ativo' : 'Inativo';
                    ?>
                    <div class="cartao-leitor">
                        <div class="leitor-info">
                            <div class="avatar-foto">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div class="leitor-detalhes">
                                <strong><?php echo htmlspecialchars($leitor['nome_leitor']); ?></strong>
                                <span><?php echo htmlspecialchars($leitor['email']); ?> &bull; <?php echo htmlspecialchars($leitor['telefone']); ?></span>
                            </div>
                        </div>
                        <div class="leitor-status">
                            <span class="badge-tipo <?php echo $tipo_classe; ?>"><?php echo htmlspecialchars($leitor['tipo_leitor']); ?></span>
                            <span class="status-indicador">
                                <i class="fa-solid fa-circle <?php echo $status_cor; ?>"></i> <?php echo $status_txt; ?>
                            </span>
                            
                            <div class="acoes-leitor" style="display: flex; gap: 14px; margin-left: 10px;">
                                <a href="../pagina_gerenciar_leitores7/editar_leitor.php?id=<?php echo $leitor['id_leitor']; ?>" title="Editar Leitor" style="color: #8d5b55; font-size: 1.2rem;">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="../pagina_gerenciar_leitores7/excluir_leitor.php?id=<?php echo $leitor['id_leitor']; ?>" 
                                   onclick="return confirm('Deseja realmente apagar este leitor do sistema?');" 
                                   title="Excluir Leitor" style="color: #ef4444; font-size: 1.2rem;">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: #777; padding: 35px; background: #fff; border-radius: 14px; font-size: 1.1rem;">
                    Nenhum leitor encontrado para esta consulta.
                </p>
            <?php endif; ?>
        </div>

    </main>

</body>
</html>