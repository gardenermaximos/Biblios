<?php
session_start();

require_once __DIR__ . '/../conexao.php';

if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../pagina_login1/index.php");
    exit();
}

$id_emprestimo = $_GET['id'] ?? null;
$mensagem_erro = "";

if (!$id_emprestimo) {
    header("Location: emprestimos.php");
    exit();
}

// 1. Carrega dados do empréstimo
try {
    $stmtEmp = $pdo->prepare("SELECT * FROM tb_emprestimos WHERE id_emprestimo = :id LIMIT 1");
    $stmtEmp->execute([':id' => $id_emprestimo]);
    $emprestimo = $stmtEmp->fetch();

    if (!$emprestimo) {
        header("Location: emprestimos.php");
        exit();
    }
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao buscar empréstimo: " . $e->getMessage();
}

// 2. Carrega lista de Leitores Ativos
try {
    $stmtLeitores = $pdo->query("SELECT id_leitor, nome_leitor, tipo_leitor FROM tb_leitores WHERE status = 1 ORDER BY nome_leitor ASC");
    $leitores = $stmtLeitores->fetchAll();
} catch (PDOException $e) {
    $leitores = [];
}

// 3. Carrega Livros (atual do empréstimo + disponíveis)
try {
    $stmtLivros = $pdo->prepare("SELECT id_livro, titulo, autor FROM tb_livros WHERE status = 1 OR id_livro = :livro_atual ORDER BY titulo ASC");
    $stmtLivros->execute([':livro_atual' => $emprestimo['fk_livro']]);
    $livros = $stmtLivros->fetchAll();
} catch (PDOException $e) {
    $livros = [];
}

// 4. Processa a atualização
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fk_leitor            = $_POST['leitor'] ?? null;
    $fk_livro             = $_POST['livro'] ?? null;
    $data_emprestimo      = $_POST['data_emprestimo'] ?? null;
    $data_devolucao       = !empty($_POST['data_devolucao']) ? $_POST['data_devolucao'] : null;
    $observacao_saida     = trim($_POST['observacao_saida'] ?? '');
    $observacao_devolucao = trim($_POST['observacao_devolucao'] ?? '');

    if (!empty($fk_leitor) && !empty($fk_livro) && !empty($data_emprestimo)) {
        try {
            $pdo->beginTransaction();

            $livro_antigo = $emprestimo['fk_livro'];

            if ($livro_antigo != $fk_livro) {
                $stmtFree = $pdo->prepare("UPDATE tb_livros SET status = 1 WHERE id_livro = :id_antigo");
                $stmtFree->execute([':id_antigo' => $livro_antigo]);
            }

            $sql = "UPDATE tb_emprestimos 
                    SET fk_leitor = :leitor, fk_livro = :livro, data_emprestimo = :dt_emp, 
                        data_devolucao = :dt_dev, observacao_saida = :obs_saida, observacao_devolucao = :obs_dev
                    WHERE id_emprestimo = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':leitor'    => $fk_leitor,
                ':livro'     => $fk_livro,
                ':dt_emp'    => date('Y-m-d H:i:s', strtotime($data_emprestimo)),
                ':dt_dev'    => $data_devolucao ? date('Y-m-d H:i:s', strtotime($data_devolucao)) : null,
                ':obs_saida' => $observacao_saida,
                ':obs_dev'   => $observacao_devolucao,
                ':id'        => $id_emprestimo
            ]);

            $novo_status = is_null($data_devolucao) ? 0 : 1;
            $stmtUpdateLivro = $pdo->prepare("UPDATE tb_livros SET status = :status WHERE id_livro = :id_livro");
            $stmtUpdateLivro->execute([':status' => $novo_status, ':id_livro' => $fk_livro]);

            $pdo->commit();

            header("Location: emprestimos.php?status_msg=editado");
            exit();

        } catch (PDOException $e) {
            $pdo->rollBack();
            $mensagem_erro = "Erro ao atualizar empréstimo: " . $e->getMessage();
        }
    } else {
        $mensagem_erro = "Preencha todos os campos obrigatórios (*).";
    }
}

$val_data_emp = date('Y-m-d', strtotime($emprestimo['data_emprestimo']));
$val_data_dev = $emprestimo['data_devolucao'] ? date('Y-m-d', strtotime($emprestimo['data_devolucao'])) : '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BibliOS - Editar Empréstimo</title>

    <!-- Ícones Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS próprio atualizado com fontes legíveis -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <!-- Cabeçalho Superior -->
    <header class="header-emprestimos">
        <div class="header-esquerda">
            <a href="emprestimos.php" class="btn-voltar" title="Voltar aos Empréstimos">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>EDITAR EMPRÉSTIMO</h2>
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
                <h3>Alterar Informações do Empréstimo #<?php echo str_pad($id_emprestimo, 4, '0', STR_PAD_LEFT); ?></h3>
                <p>Ajuste o leitor, livro, datas de devolução ou observações do registro</p>
            </div>

            <?php if (!empty($mensagem_erro)): ?>
                <div style="background-color: #fee2e2; color: #991b1b; padding: 14px 18px; border-radius: 12px; margin-bottom: 22px; font-weight: bold; font-size: 1.05rem;">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($mensagem_erro); ?>
                </div>
            <?php endif; ?>

            <form action="editar_emprestimo.php?id=<?php echo $id_emprestimo; ?>" method="POST" class="form-cadastro">
                
                <!-- Leitor -->
                <div class="linha-form">
                    <div class="campo-form">
                        <label for="leitor">Leitor *</label>
                        <select id="leitor" name="leitor" required>
                            <?php foreach ($leitores as $l): ?>
                                <option value="<?php echo $l['id_leitor']; ?>" <?php echo ($l['id_leitor'] == $emprestimo['fk_leitor']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($l['nome_leitor']); ?> (<?php echo htmlspecialchars($l['tipo_leitor']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Livro -->
                <div class="linha-form">
                    <div class="campo-form">
                        <label for="livro">Livro *</label>
                        <select id="livro" name="livro" required>
                            <?php foreach ($livros as $liv): ?>
                                <option value="<?php echo $liv['id_livro']; ?>" <?php echo ($liv['id_livro'] == $emprestimo['fk_livro']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($liv['titulo']); ?> — <?php echo htmlspecialchars($liv['autor']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Datas -->
                <div class="linha-form">
                    <div class="campo-form">
                        <label for="data_emprestimo">Data do Empréstimo *</label>
                        <input type="date" id="data_emprestimo" name="data_emprestimo" value="<?php echo $val_data_emp; ?>" required>
                    </div>

                    <div class="campo-form">
                        <label for="data_devolucao">Data de Devolução (Deixe vazio se em andamento)</label>
                        <input type="date" id="data_devolucao" name="data_devolucao" value="<?php echo $val_data_dev; ?>">
                    </div>
                </div>

                <!-- Observações de Saída -->
                <div class="linha-form">
                    <div class="campo-form">
                        <label for="observacao_saida">Observação de Saída</label>
                        <input type="text" id="observacao_saida" name="observacao_saida" value="<?php echo htmlspecialchars($emprestimo['observacao_saida'] ?? ''); ?>" placeholder="Estado do livro ao entregar">
                    </div>
                </div>

                <!-- Observações de Devolução -->
                <div class="linha-form">
                    <div class="campo-form">
                        <label for="observacao_devolucao">Observação de Devolução</label>
                        <input type="text" id="observacao_devolucao" name="observacao_devolucao" value="<?php echo htmlspecialchars($emprestimo['observacao_devolucao'] ?? ''); ?>" placeholder="Estado do livro ao retornar">
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="botoes-form">
                    <a href="emprestimos.php" class="btn-cancelar">CANCELAR</a>
                    <button type="submit" class="btn-salvar">SALVAR ALTERAÇÕES</button>
                </div>

            </form>

        </div>

    </main>

</body>
</html>