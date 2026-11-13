<?php
session_start();

require_once __DIR__ . '/../conexao.php';

if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../pagina_login1/index.php");
    exit();
}

$termo_busca = trim($_GET['busca'] ?? '');
$mensagem_status = $_GET['status_msg'] ?? '';

try {
    if (!empty($termo_busca)) {
        $sql = "SELECT l.*, c.nome_categoria 
                FROM tb_livros l
                LEFT JOIN tb_categorias c ON l.fk_categoria = c.id_categoria
                WHERE l.titulo LIKE :busca 
                   OR l.autor LIKE :busca 
                   OR c.nome_categoria LIKE :busca
                   OR l.origem LIKE :busca
                ORDER BY l.id_livro DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':busca' => '%' . $termo_busca . '%']);
    } else {
        $sql = "SELECT l.*, c.nome_categoria 
                FROM tb_livros l
                LEFT JOIN tb_categorias c ON l.fk_categoria = c.id_categoria
                ORDER BY l.id_livro DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    $livros = $stmt->fetchAll();
} catch (PDOException $e) {
    $livros = [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BibliOS - Catálogo Geral</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <header class="header-catalogo">
        <div class="header-esquerda">
            <a href="../pagina_inicial2/home.php" class="btn-voltar" title="Voltar ao Dashboard">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>CATÁLOGO DE LIVROS (REDE E ACERVO)</h2>
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
        <a href="catalogo.php" class="aba-btn aba-ativa">LISTA GERAL</a>
        <a href="cadastrar_livro.php" class="aba-btn">CADASTRO / SOLICITAÇÃO</a>
    </div>

    <hr class="linha-divisoria">

    <main class="container-conteudo">

        <?php if ($mensagem_status === 'excluido'): ?>
            <div style="background-color: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: bold;">
                <i class="fa-solid fa-circle-check"></i> Livro removido do catálogo!
            </div>
        <?php elseif ($mensagem_status === 'erro_vinculo'): ?>
            <div style="background-color: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: bold;">
                <i class="fa-solid fa-triangle-exclamation"></i> Não é possível eliminar este livro pois possui registos de empréstimo.
            </div>
        <?php elseif ($mensagem_status === 'editado'): ?>
            <div style="background-color: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: bold;">
                <i class="fa-solid fa-circle-check"></i> Dados do livro atualizados!
            </div>
        <?php endif; ?>

        <form method="GET" action="catalogo.php" class="barra-ferramentas">
            <div class="campo-busca">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="busca" value="<?php echo htmlspecialchars($termo_busca); ?>" placeholder="Buscar no catálogo por título, autor, gênero ou origem...">
            </div>
            <button type="submit" class="btn-filtros"><i class="fa-solid fa-filter"></i> Buscar</button>
            <?php if (!empty($termo_busca)): ?>
                <a href="catalogo.php" class="btn-filtros" style="text-decoration: none; color: #8d5b55;">Limpar</a>
            <?php endif; ?>
        </form>

        <div class="lista-livros">
            <?php if (!empty($livros)): ?>
                <?php foreach ($livros as $livro): ?>
                    <?php 
                        $is_local      = ($livro['origem'] === 'Acervo Local');
                        $origem_txt    = $livro['origem'] ?? 'Acervo Local';
                        $status_texto  = $is_local ? ($livro['status'] == 1 ? 'Local (Disponível)' : 'Local (Emprestado)') : 'Disponível p/ Solicitação';
                        $status_classe = $is_local ? ($livro['status'] == 1 ? 'disponivel' : 'emprestado') : 'disponivel';
                        $categoria     = $livro['nome_categoria'] ?? 'Geral';
                    ?>
                    <div class="cartao-livro">
                        <div class="livro-info">
                            <div class="capa-box" style="background-color: <?php echo $is_local ? '#8d5b55' : '#2e5a44'; ?>;">
                                <i class="fa-solid <?php echo $is_local ? 'fa-book-bookmark' : 'fa-building-columns'; ?>"></i>
                            </div>
                            <div class="livro-detalhes">
                                <strong><?php echo htmlspecialchars($livro['titulo']); ?></strong>
                                <span class="autor"><?php echo htmlspecialchars($livro['autor']); ?></span>
                                <span class="isbn">Origem: <strong><?php echo htmlspecialchars($origem_txt); ?></strong></span>
                            </div>
                        </div>
                        <div class="livro-status">
                            <span class="badge-genero"><?php echo htmlspecialchars($categoria); ?></span>
                            <span class="badge-status <?php echo $status_classe; ?>"><?php echo $status_texto; ?></span>
                            
                            <div class="acoes-livro" style="display: flex; gap: 12px; margin-left: 10px;">
                                <a href="editar_livro.php?id=<?php echo $livro['id_livro']; ?>" title="Editar Livro" style="color: #8d5b55; font-size: 1.1rem;">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="excluir_livro.php?id=<?php echo $livro['id_livro']; ?>" 
                                   onclick="return confirm('Deseja realmente eliminar este livro do catálogo?');" 
                                   title="Eliminar Livro" style="color: #ef4444; font-size: 1.1rem;">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: #777; padding: 30px; background: #fff; border-radius: 12px;">Nenhum livro encontrado no catálogo geral.</p>
            <?php endif; ?>
        </div>

    </main>

</body>
</html>