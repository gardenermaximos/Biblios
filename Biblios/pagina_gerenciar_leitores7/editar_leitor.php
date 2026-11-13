<?php
session_start();

require_once __DIR__ . '/../conexao.php';

if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../pagina_login1/index.php");
    exit();
}

$id_leitor = $_GET['id'] ?? null;
$mensagem_erro = "";

if (!$id_leitor) {
    header("Location: ../pagina_leitores3/leitores.php");
    exit();
}

// 1. Busca os dados atuais do leitor
try {
    $stmt = $pdo->prepare("SELECT * FROM tb_leitores WHERE id_leitor = :id LIMIT 1");
    $stmt->execute([':id' => $id_leitor]);
    $leitor = $stmt->fetch();

    if (!$leitor) {
        header("Location: ../pagina_leitores3/leitores.php");
        exit();
    }
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao buscar dados: " . $e->getMessage();
}

// 2. Processa a atualização
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome     = trim($_POST['nome'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $tipo     = $_POST['tipo'] ?? 'Aluno';
    $status   = isset($_POST['status']) ? 1 : 0;

    if (!empty($nome) && !empty($email)) {
        try {
            $sql = "UPDATE tb_leitores 
                    SET nome_leitor = :nome, email = :email, telefone = :telefone, tipo_leitor = :tipo, status = :status 
                    WHERE id_leitor = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome'     => $nome,
                ':email'    => $email,
                ':telefone' => $telefone,
                ':tipo'     => $tipo,
                ':status'   => $status,
                ':id'       => $id_leitor
            ]);

            header("Location: ../pagina_leitores3/leitores.php?status_msg=editado");
            exit();
        } catch (PDOException $e) {
            $mensagem_erro = "Erro ao atualizar leitor: " . $e->getMessage();
        }
    } else {
        $mensagem_erro = "Preencha os campos obrigatórios (*).";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BibliOS - Editar Leitor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <header class="header-gerenciar">
        <div class="header-esquerda">
            <a href="../pagina_leitores3/leitores.php" class="btn-voltar" title="Voltar">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>EDITAR LEITOR</h2>
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
                <h3>Editar Dados do Leitor</h3>
                <p>Altere as informações necessárias e clique em salvar</p>
            </div>

            <?php if (!empty($mensagem_erro)): ?>
                <div style="background-color: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: bold;">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($mensagem_erro); ?>
                </div>
            <?php endif; ?>

            <form action="editar_leitor.php?id=<?php echo $id_leitor; ?>" method="POST" class="form-cadastro">
                <div class="linha-form">
                    <div class="campo-form">
                        <label for="nome">Nome Completo *</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($leitor['nome_leitor']); ?>" required>
                    </div>

                    <div class="campo-form">
                        <label for="email">E-mail Institucional *</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($leitor['email']); ?>" required>
                    </div>
                </div>

                <div class="linha-form">
                    <div class="campo-form">
                        <label for="telefone">Telefone de Contato</label>
                        <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($leitor['telefone']); ?>">
                    </div>

                    <div class="campo-form">
                        <label for="tipo">Tipo de Cadastro *</label>
                        <select id="tipo" name="tipo" required>
                            <option value="Aluno" <?php echo ($leitor['tipo_leitor'] === 'Aluno') ? 'selected' : ''; ?>>Aluno</option>
                            <option value="Professor" <?php echo ($leitor['tipo_leitor'] === 'Professor') ? 'selected' : ''; ?>>Professor</option>
                            <option value="Funcionário" <?php echo ($leitor['tipo_leitor'] === 'Funcionário') ? 'selected' : ''; ?>>Funcionário</option>
                            <option value="Comunidade" <?php echo ($leitor['tipo_leitor'] === 'Comunidade') ? 'selected' : ''; ?>>Comunidade</option>
                        </select>
                    </div>
                </div>

                <div class="campo-form-status">
                    <div class="status-texto">
                        <strong>Status do Cadastro</strong>
                        <p>Ative ou inative o cadastro do leitor</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="status" <?php echo ($leitor['status'] == 1) ? 'checked' : ''; ?>>
                        <span class="slider round"></span>
                    </label>
                </div>

                <div class="botoes-form">
                    <a href="../pagina_leitores3/leitores.php" class="btn-cancelar">CANCELAR</a>
                    <button type="submit" class="btn-salvar">SALVAR ALTERAÇÕES</button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>