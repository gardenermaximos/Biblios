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
$id_emprestimo_selecionado = $_GET['id'] ?? null;

// Processa o registro de devolução
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_emprestimo        = $_POST['emprestimo'] ?? null;
    $data_devolucao       = $_POST['data_devolucao'] ?? date('Y-m-d H:i:s');
    $observacao_devolucao = trim($_POST['observacoes'] ?? 'Devolvido em bom estado');

    if (!empty($id_emprestimo)) {
        try {
            $pdo->beginTransaction();

            // 1. Busca o ID do livro associado ao empréstimo
            $stmtBusca = $pdo->prepare("SELECT fk_livro FROM tb_emprestimos WHERE id_emprestimo = :id LIMIT 1");
            $stmtBusca->execute([':id' => $id_emprestimo]);
            $emprestimoObj = $stmtBusca->fetch();

            if ($emprestimoObj) {
                $id_livro = $emprestimoObj['fk_livro'];

                // 2. Atualiza o registro do empréstimo com a data de devolução e observação
                $sqlDevolucao = "UPDATE tb_emprestimos 
                                 SET data_devolucao = :data_dev, observacao_devolucao = :obs 
                                 WHERE id_emprestimo = :id_emp";
                $stmtDev = $pdo->prepare($sqlDevolucao);
                $stmtDev->execute([
                    ':data_dev' => date('Y-m-d H:i:s', strtotime($data_devolucao)),
                    ':obs'      => $observacao_devolucao,
                    ':id_emp'   => $id_emprestimo
                ]);

                // 3. Atualiza o livro de volta para Disponível no acervo (status = 1)
                $stmtLivro = $pdo->prepare("UPDATE tb_livros SET status = 1 WHERE id_livro = :id_livro");
                $stmtLivro->execute([':id_livro' => $id_livro]);

                $pdo->commit();

                // Redireciona para a lista geral de empréstimos
                header("Location: ../pagina_emprestimos5/emprestimos.php");
                exit();
            } else {
                $pdo->rollBack();
                $mensagem_erro = "Empréstimo não encontrado no sistema.";
            }

        } catch (PDOException $e) {
            $pdo->rollBack();
            $mensagem_erro = "Erro ao dar baixa na devolução: " . $e->getMessage();
        }
    } else {
        $mensagem_erro = "Selecione um empréstimo ativo para continuar.";
    }
}

// Carrega os empréstimos em aberto para o menu suspenso <select>
try {
    $sql = "SELECT e.id_emprestimo, e.data_emprestimo, l.titulo, le.nome_leitor 
            FROM tb_emprestimos e
            INNER JOIN tb_livros l ON e.fk_livro = l.id_livro
            INNER JOIN tb_leitores le ON e.fk_leitor = le.id_leitor
            WHERE e.data_devolucao IS NULL
            ORDER BY e.data_emprestimo ASC";
    $stmt = $pdo->query($sql);
    $emprestimos_abertos = $stmt->fetchAll();
} catch (PDOException $e) {
    $emprestimos_abertos = [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BibliOS - Registrar Devolução</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- CSS Próprio -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    
</head>
<body>

    <!-- Cabeçalho Superior -->
    <header class="header-devolucao">
        <div class="header-esquerda">
            <a href="../pagina_inicial2/home.php" class="btn-voltar" title="Voltar ao Dashboard">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>REGISTRAR DEVOLUÇÃO</h2>
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
                <h3>Registrar Devolução</h3>
                <p>Finalize empréstimos em aberto dando baixa rápida de retorno físico ao acervo</p>
            </div>

            <!-- Exibição de Alertas de Erro -->
            <?php if (!empty($mensagem_erro)): ?>
                <div style="background-color: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: bold; font-size: 0.9rem;">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($mensagem_erro); ?>
                </div>
            <?php endif; ?>

            <form action="registrar_devolucao.php" method="POST" class="form-devolucao">
                
                <!-- Empréstimo Ativo Dinâmico -->
                <div class="campo-form">
                    <label for="emprestimo">Empréstimo Ativo *</label>
                    <select id="emprestimo" name="emprestimo" required>
                        <option value="" disabled <?php echo empty($id_emprestimo_selecionado) ? 'selected' : ''; ?>>Selecione o empréstimo em andamento</option>
                        <?php foreach ($emprestimos_abertos as $emp): ?>
                            <?php 
                                $data_emp = (new DateTime($emp['data_emprestimo']))->format('d/m/Y');
                                $selected = ($id_emprestimo_selecionado == $emp['id_emprestimo']) ? 'selected' : '';
                            ?>
                            <option value="<?php echo $emp['id_emprestimo']; ?>" <?php echo $selected; ?>>
                                <?php echo htmlspecialchars($emp['titulo']); ?> — Leitor: <?php echo htmlspecialchars($emp['nome_leitor']); ?> (Empréstimo: <?php echo $data_emp; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Data de Devolução -->
                <div class="campo-form">
                    <label for="data_devolucao">Data de Devolução *</label>
                    <input type="date" id="data_devolucao" name="data_devolucao" value="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <!-- Observações -->
                <div class="campo-form">
                    <label for="observacoes">Observações de Devolução</label>
                    <textarea id="observacoes" name="observacoes" rows="4" placeholder="Informe o estado de conservação do livro ao retornar (ex: sem avarias)..."></textarea>
                </div>

                <!-- Botões de Ação -->
                <div class="botoes-form">
                    <a href="../pagina_inicial2/home.php" class="btn-cancelar">CANCELAR</a>
                    <button type="submit" class="btn-salvar">DAR BAIXA</button>
                </div>

            </form>

        </div>

    </main>

</body>
</html>