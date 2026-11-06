<?php
session_start();

require_once __DIR__ . '/../conexao.php';

if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../pagina_login1/login.php");
    exit();
}

$id_livro = $_GET['id'] ?? null;
$mensagem_erro = "";

if (!$id_livro) {
    header("Location: catalogo.php");
    exit();
}

// 1. Carrega as categorias do banco para o select
try {
    $stmtCat = $pdo->query("SELECT * FROM tb_categorias ORDER BY nome_categoria ASC");
    $categorias = $stmtCat->fetchAll();
} catch (PDOException $e) {
    $categorias = [];
}

// 2. Busca os dados atuais do livro
try {
    $stmt = $pdo->prepare("SELECT * FROM tb_livros WHERE id_livro = :id LIMIT 1");
    $stmt->execute([':id' => $id_livro]);
    $livro = $stmt->fetch();

    if (!$livro) {
        header("Location: catalogo.php");
        exit();
    }
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao buscar dados do livro: " . $e->getMessage();
}

// 3. Processa a atualização
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo       = trim($_POST['titulo'] ?? '');
    $autor        = trim($_POST['autor'] ?? '');
    $fk_categoria = $_POST['genero'] ?? null;
    $status       = isset($_POST['disponivel']) ? 1 : 0;

    if (!empty($titulo) && !empty($autor) && !empty($fk_categoria)) {
        try {
            $sql = "UPDATE tb_livros 
                    SET titulo = :titulo, autor = :autor, status = :status, fk_categoria = :categoria 
                    WHERE id_livro = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':titulo'    => $titulo,
                ':autor'     => $autor,
                ':status'    => $status,
                ':categoria' => $fk_categoria,
                ':id'        => $id_livro
            ]);

            header("Location: catalogo.php?status_msg=editado");
            exit();
        } catch (PDOException $e) {
            $mensagem_erro = "Erro ao atualizar o livro: " . $e->getMessage();
        }
    } else {
        $mensagem_erro = "Preencha todos os campos obrigatórios (*).";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BibliOS - Editar Livro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <header class="header-catalogo">
        <div class="header-esquerda">
            <a href="catalogo.php" class="btn-voltar" title="Voltar ao Catálogo">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>EDITAR LIVRO</h2>
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
        <div class="card-formulario">
            <div class="form-cabecalho">
                <h3>Editar Livro do Acervo</h3>
                <p>Altere as informações da obra e guarde as modificações</p>
            </div>

            <?php if (!empty($mensagem_erro)): ?>
                <div style="background-color: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: bold;">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($mensagem_erro); ?>
                </div>
            <?php endif; ?>

            <form action="editar_livro.php?id=<?php echo $id_livro; ?>" method="POST" class="form-cadastro">
                
                <div class="linha-form">
                    <div class="campo-form">
                        <label for="titulo">Título do Livro *</label>
                        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($livro['titulo']); ?>" required>
                    </div>

                    <div class="campo-form">
                        <label for="autor">Autor *</label>
                        <input type="text" id="autor" name="autor" value="<?php echo htmlspecialchars($livro['autor']); ?>" required>
                    </div>
                </div>

                <div class="linha-form">
                    <div class="campo-form">
                        <label for="genero">Gênero / Categoria *</label>
                        <select id="genero" name="genero" required>
                            <option value="">Selecione a categoria</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo $cat['id_categoria']; ?>" <?php echo ($livro['fk_categoria'] == $cat['id_categoria']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['nome_categoria']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="campo-form">
                        <label for="disponivel">Status no Acervo</label>
                        <select id="disponivel" name="disponivel">
                            <option value="1" <?php echo ($livro['status'] == 1) ? 'selected' : ''; ?>>Disponível para Empréstimo</option>
                            <option value="0" <?php echo ($livro['status'] == 0) ? 'selected' : ''; ?>>Emprestado / Indisponível</option>
                        </select>
                    </div>
                </div>

                <div class="botoes-form">
                    <a href="catalogo.php" class="btn-cancelar">CANCELAR</a>
                    <button type="submit" class="btn-salvar">SALVAR ALTERAÇÕES</button>
                </div>

            </form>
        </div>
    </main>

</body>
</html>