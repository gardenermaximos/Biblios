<?php
session_start();

require_once __DIR__ . '/../conexao.php';

if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../pagina_login1/login.php");
    exit();
}

$termo_busca = trim($_GET['busca'] ?? '');

try {
    // Filtro estrito: apenas livros do Acervo Local da escola
    if (!empty($termo_busca)) {
        $sql = "SELECT l.*, c.nome_categoria 
                FROM tb_livros l
                LEFT JOIN tb_categorias c ON l.fk_categoria = c.id_categoria
                WHERE l.origem = 'Acervo Local' 
                  AND (l.titulo LIKE :busca OR l.autor LIKE :busca OR c.nome_categoria LIKE :busca)
                ORDER BY l.id_livro DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':busca' => '%' . $termo_busca . '%']);
    } else {
        $sql = "SELECT l.*, c.nome_categoria 
                FROM tb_livros l
                LEFT JOIN tb_categorias c ON l.fk_categoria = c.id_categoria
                WHERE l.origem = 'Acervo Local'
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
    <title>BibliOS - Livros Cadastrados (Acervo Local)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <header class="header-livros">
        <div class="header-esquerda">
            <a href="../pagina_inicial2/index.php" class="btn-voltar" title="Voltar ao Dashboard">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>LIVROS CADASTRADOS (ACERVO FÍSICO)</h2>
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

    <hr class="linha-divisoria">

    <main class="container-conteudo">

        <form method="GET" action="livros_cadastrados.php" class="barra-ferramentas">
            <div class="campo-busca">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="busca" value="<?php echo htmlspecialchars($termo_busca); ?>" placeholder="Buscar no acervo local por título, autor ou categoria...">
            </div>
            <button type="submit" class="btn-filtros"><i class="fa-solid fa-filter"></i> Buscar</button>
            <?php if (!empty($termo_busca)): ?>
                <a href="livros_cadastrados.php" class="btn-filtros" style="text-decoration: none; color: #8d5b55;">Limpar</a>
            <?php endif; ?>
        </form>

        <div class="lista-livros">
            <?php if (!empty($livros)): ?>
                <?php foreach ($livros as $livro): ?>
                    <?php 
                        $is_disponivel = ($livro['status'] == 1);
                        $status_texto  = $is_disponivel ? 'Disponível na Estante' : 'Emprestado';
                        $status_classe = $is_disponivel ? 'disponivel' : 'emprestado';
                        $categoria     = $livro['nome_categoria'] ?? 'Geral';
                    ?>
                    <div class="cartao-livro">
                        <div class="livro-info">
                            <div class="capa-box">
                                <i class="fa-solid fa-book"></i>
                            </div>
                            <div class="livro-detalhes">
                                <strong><?php echo htmlspecialchars($livro['titulo']); ?></strong>
                                <span class="autor"><?php echo htmlspecialchars($livro['autor']); ?></span>
                                <span class="isbn">Registro Local: #<?php echo str_pad($livro['id_livro'], 4, '0', STR_PAD_LEFT); ?></span>
                            </div>
                        </div>
                        <div class="livro-status">
                            <span class="badge-genero"><?php echo htmlspecialchars($categoria); ?></span>
                            <span class="badge-status <?php echo $status_classe; ?>"><?php echo $status_texto; ?></span>
                            
                            <div class="acoes-livro" style="display: flex; gap: 12px; margin-left: 10px;">
                                <a href="../pagina_catalogo8/editar_livro.php?id=<?php echo $livro['id_livro']; ?>" title="Editar Livro" style="color: #8d5b55; font-size: 1.1rem;">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="../pagina_catalogo8/excluir_livro.php?id=<?php echo $livro['id_livro']; ?>" 
                                   onclick="return confirm('Deseja realmente eliminar este livro do acervo?');" 
                                   title="Eliminar Livro" style="color: #ef4444; font-size: 1.1rem;">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: #777; padding: 30px; background: #fff; border-radius: 12px;">Nenhum livro cadastrado no acervo físico local.</p>
            <?php endif; ?>
        </div>

    </main>

</body>
</html>