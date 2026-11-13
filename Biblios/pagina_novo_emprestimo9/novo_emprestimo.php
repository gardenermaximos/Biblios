<?php
session_start();

// Inclui a conexão com o banco db_biblios
require_once __DIR__ . '/../conexao.php';

// Redireciona para o login caso não esteja autenticado
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../pagina_login1/index.php");
    exit();
}

$mensagem_erro = "";

// 1. Carrega Leitores Ativos no banco (status = 1)
try {
    $stmtLeitores = $pdo->query("SELECT id_leitor, nome_leitor, tipo_leitor FROM tb_leitores WHERE status = 1 ORDER BY nome_leitor ASC");
    $leitores = $stmtLeitores->fetchAll();
} catch (PDOException $e) {
    $leitores = [];
}

// 2. Carrega Livros Disponíveis no banco (status = 1)
try {
    $stmtLivros = $pdo->query("SELECT id_livro, titulo, autor FROM tb_livros WHERE status = 1 ORDER BY titulo ASC");
    $livros = $stmtLivros->fetchAll();
} catch (PDOException $e) {
    $livros = [];
}

// 3. Processa a criação do novo empréstimo
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_leitor        = $_POST['leitor'] ?? null;
    $id_livro         = $_POST['livro'] ?? null;
    $data_emprestimo  = $_POST['data_emprestimo'] ?? date('Y-m-d H:i:s');
    $observacao_saida = trim($_POST['observacoes'] ?? 'Livro em bom estado');

    if (!empty($id_leitor) && !empty($id_livro)) {
        try {
            $pdo->beginTransaction();

            // Insere o empréstimo na tb_emprestimos
            $sql = "INSERT INTO tb_emprestimos (data_emprestimo, observacao_saida, fk_livro, fk_leitor) 
                    VALUES (:data_emp, :obs, :id_livro, :id_leitor)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':data_emp'  => date('Y-m-d H:i:s', strtotime($data_emprestimo)),
                ':obs'       => $observacao_saida,
                ':id_livro'  => $id_livro,
                ':id_leitor' => $id_leitor
            ]);

            // Atualiza o status do livro para Indisponível/Emprestado (status = 0)
            $stmtUpdateLivro = $pdo->prepare("UPDATE tb_livros SET status = 0 WHERE id_livro = :id_livro");
            $stmtUpdateLivro->execute([':id_livro' => $id_livro]);

            $pdo->commit();

            // Redireciona para a lista de empréstimos
            header("Location: ../pagina_emprestimos5/emprestimos.php");
            exit();

        } catch (PDOException $e) {
            $pdo->rollBack();
            $mensagem_erro = "Erro ao registrar empréstimo: " . $e->getMessage();
        }
    } else {
        $mensagem_erro = "Selecione o leitor e o livro obrigatoriamente (*).";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BibliOS - Registrar Novo Empréstimo</title>

    <!-- Ícones Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS próprio da página -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    
</head>
<body>

    <!-- Cabeçalho Superior -->
    <header class="header-emprestimo">
        <div class="header-esquerda">
            <!-- Botão para voltar ao Dashboard -->
            <a href="../pagina_inicial2/home.php" class="btn-voltar" title="Voltar ao Dashboard">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>NOVO EMPRÉSTIMO</h2>
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

    <!-- Conteúdo Principal -->
    <main class="container-conteudo">

        <div class="card-formulario">
            
            <div class="form-cabecalho">
                <h3>Registrar Novo Empréstimo</h3>
                <p>Insira as informações do leitor e da obra para criar o registro de saída temporária</p>
            </div>

            <!-- Alerta de erro caso ocorra -->
            <?php if (!empty($mensagem_erro)): ?>
                <div style="background-color: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: bold; font-size: 0.9rem;">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($mensagem_erro); ?>
                </div>
            <?php endif; ?>

            <form action="novo_emprestimo.php" method="POST" class="form-emprestimo">
                
                <div class="grid-form-emprestimo">
                    
                    <!-- Coluna da Esquerda: Campos do Formulário -->
                    <div class="coluna-campos">
                        
                        <!-- Leitor Dinâmico -->
                        <div class="campo-form">
                            <label for="leitor">Leitor *</label>
                            <select id="leitor" name="leitor" required>
                                <option value="" disabled selected>Selecione o leitor</option>
                                <?php foreach ($leitores as $l): ?>
                                    <option value="<?php echo $l['id_leitor']; ?>">
                                        <?php echo htmlspecialchars($l['nome_leitor']); ?> (<?php echo htmlspecialchars($l['tipo_leitor']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Livro Dinâmico -->
                        <div class="campo-form">
                            <label for="livro">Livro Disponível *</label>
                            <select id="livro" name="livro" required>
                                <option value="" disabled selected>Selecione o livro</option>
                                <?php foreach ($livros as $liv): ?>
                                    <option value="<?php echo $liv['id_livro']; ?>">
                                        <?php echo htmlspecialchars($liv['titulo']); ?> - <?php echo htmlspecialchars($liv['autor']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Datas Side-by-Side -->
                        <div class="linha-datas">
                            <div class="campo-form">
                                <label for="data_emprestimo">Data de Empréstimo *</label>
                                <input type="date" id="data_emprestimo" name="data_emprestimo" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>

                            <div class="campo-form">
                                <label for="previsao_devolucao">Previsão de Devolução *</label>
                                <input type="date" id="previsao_devolucao" name="previsao_devolucao" value="<?php echo date('Y-m-d', strtotime('+14 days')); ?>" required>
                            </div>
                        </div>

                        <!-- Observações -->
                        <div class="campo-form">
                            <label for="observacoes">Observações de Saída</label>
                            <textarea id="observacoes" name="observacoes" rows="3" placeholder="Digite observações de estado do livro (opcional)..."></textarea>
                        </div>

                    </div>

                    <!-- Coluna da Direita: Verificações de Segurança -->
                    <div class="coluna-seguranca">
                        <label class="titulo-seguranca">Verificações de Segurança</label>
                        
                        <div class="boxes-upload">
                            <!-- Box 1: Foto Leitor -->
                            <div class="upload-box">
                                <i class="fa-solid fa-user icon-plus" style="font-size: 1.6rem; margin-bottom: 6px;"></i>
                                <strong>Foto do Leitor</strong>
                                <span class="btn-text-add">Cadastrada</span>
                            </div>

                            <!-- Box 2: Capa Livro -->
                            <div class="upload-box">
                                <i class="fa-solid fa-book icon-plus" style="font-size: 1.6rem; margin-bottom: 6px;"></i>
                                <strong>Capa do Livro</strong>
                                <span class="btn-text-add">Cadastrada</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Botões de Ação -->
                <div class="botoes-form">
                    <a href="../pagina_inicial2/home.php" class="btn-cancelar">CANCELAR</a>
                    <button type="submit" class="btn-salvar">REGISTRAR EMPRÉSTIMO</button>
                </div>

            </form>

        </div>

    </main>

</body>
</html>