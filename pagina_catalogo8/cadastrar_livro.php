<?php
session_start();

require_once __DIR__ . '/../conexao.php';

if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../pagina_login1/login.php");
    exit();
}

$mensagem_erro = "";

try {
    $stmtCategorias = $pdo->query("SELECT * FROM tb_categorias ORDER BY nome_categoria ASC");
    $categorias = $stmtCategorias->fetchAll();
} catch (PDOException $e) {
    $categorias = [];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo            = trim($_POST['titulo'] ?? '');
    $autor             = trim($_POST['autor'] ?? '');
    $fk_categoria      = $_POST['genero'] ?? null;
    $biblioteca_origem = $_POST['biblioteca_origem'] ?? 'Acervo Local';
    $foto_livro        = 'livro_padrao.jpg';
    $status            = 1;

    // Trata o nome da origem
    if ($biblioteca_origem === 'local') {
        $origem_nome = 'Acervo Local';
    } elseif ($biblioteca_origem === 'parceira_a') {
        $origem_nome = 'Biblioteca Central';
    } elseif ($biblioteca_origem === 'parceira_b') {
        $origem_nome = 'Biblioteca Municipal';
    } else {
        $origem_nome = 'Rede Escolar Unificada';
    }

    if (!empty($titulo) && !empty($autor) && !empty($fk_categoria)) {
        try {
            $sql = "INSERT INTO tb_livros (titulo, autor, status, foto_livro, fk_categoria, origem) 
                    VALUES (:titulo, :autor, :status, :foto, :categoria, :origem)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':titulo'    => $titulo,
                ':autor'     => $autor,
                ':status'    => $status,
                ':foto'      => $foto_livro,
                ':categoria' => $fk_categoria,
                ':origem'    => $origem_nome
            ]);

            header("Location: catalogo.php");
            exit();
        } catch (PDOException $e) {
            $mensagem_erro = "Erro ao cadastrar livro: " . $e->getMessage();
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
    <title>BibliOS - Adicionar / Solicitar Livro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <header class="header-catalogo">
        <div class="header-esquerda">
            <a href="../pagina_inicial2/index.php" class="btn-voltar" title="Voltar ao Dashboard">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>CATÁLOGO GERAL</h2>
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
        <a href="catalogo.php" class="aba-btn">LISTA</a>
        <a href="cadastrar_livro.php" class="aba-btn aba-ativa">CADASTRO</a>
    </div>

    <hr class="linha-divisoria">

    <main class="container-conteudo">
        <div class="card-formulario">
            <div class="form-cabecalho">
                <h3>Cadastrar Novo Livro no Catálogo</h3>
                <p>Adicione uma obra ao acervo local ou registe livros disponíveis para solicitação externa</p>
            </div>

            <?php if (!empty($mensagem_erro)): ?>
                <div style="background-color: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: bold;">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($mensagem_erro); ?>
                </div>
            <?php endif; ?>

            <form action="cadastrar_livro.php" method="POST" class="form-cadastro">
                <div class="linha-form">
                    <div class="campo-form">
                        <label for="titulo">Título do Livro *</label>
                        <input type="text" id="titulo" name="titulo" placeholder="Ex: Dom Casmurro" required>
                    </div>

                    <div class="campo-form">
                        <label for="autor">Autor *</label>
                        <input type="text" id="autor" name="autor" placeholder="Ex: Machado de Assis" required>
                    </div>
                </div>

                <div class="linha-form">
                    <div class="campo-form">
                        <label for="genero">Gênero / Categoria *</label>
                        <select id="genero" name="genero" required>
                            <option value="">Selecione a categoria</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo $cat['id_categoria']; ?>">
                                    <?php echo htmlspecialchars($cat['nome_categoria']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="campo-form">
                        <label for="biblioteca_origem">Localização / Origem do Livro *</label>
                        <select id="biblioteca_origem" name="biblioteca_origem" required>
                            <option value="local">Acervo Próprio (Biblioteca Local)</option>
                            <option value="parceira_a">Solicitar a: Biblioteca Central</option>
                            <option value="parceira_b">Solicitar a: Biblioteca Municipal</option>
                            <option value="parceira_c">Solicitar a: Rede Escolar Unificada</option>
                        </select>
                    </div>
                </div>

                <div class="botoes-form">
                    <a href="catalogo.php" class="btn-cancelar">CANCELAR</a>
                    <button type="submit" class="btn-salvar">REGISTAR NO CATÁLOGO</button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>