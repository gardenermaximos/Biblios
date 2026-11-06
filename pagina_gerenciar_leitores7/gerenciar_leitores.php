<?php
session_start();

require_once __DIR__ . '/../conexao.php';

if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../pagina_login1/login.php");
    exit();
}

$mensagem_erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome            = trim($_POST['nome'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $telefone        = trim($_POST['telefone'] ?? '');
    $tipo            = $_POST['tipo'] ?? 'Aluno';
    $data_nascimento = $_POST['data_nascimento'] ?? date('Y-m-d');
    $foto_leitor     = 'avatar_padrao.jpg';
    $status          = 1;

    if (!empty($nome) && !empty($email)) {
        try {
            $sql = "INSERT INTO tb_leitores (nome_leitor, telefone, tipo_leitor, data_nascimento, status, foto_leitor, email) 
                    VALUES (:nome, :telefone, :tipo, :data_nasc, :status, :foto, :email)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome'      => $nome,
                ':telefone'  => $telefone,
                ':tipo'      => $tipo,
                ':data_nasc' => $data_nascimento,
                ':status'    => $status,
                ':foto'      => $foto_leitor,
                ':email'     => $email
            ]);

            header("Location: ../pagina_leitores3/leitores.php?status=todos");
            exit();

        } catch (PDOException $e) {
            $mensagem_erro = "Erro ao cadastrar leitor: " . $e->getMessage();
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
    <title>BibliOS - Cadastrar Novo Leitor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <header class="header-gerenciar">
        <div class="header-esquerda">
            <a href="../pagina_leitores3/leitores.php" class="btn-voltar" title="Voltar à Lista de Leitores">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>GERENCIAR LEITORES</h2>
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

    <!-- Abas com navegação transparente entre Lista e Novo Cadastro -->
    <div class="container-abas">
        <a href="../pagina_leitores3/leitores.php" class="aba-btn">VER LISTA DE LEITORES</a>
        <a href="gerenciar_leitores.php" class="aba-btn aba-ativa">NOVO CADASTRO</a>
    </div>

    <hr class="linha-divisoria">

    <main class="container-conteudo">

        <div class="card-formulario">
            <div class="form-cabecalho">
                <h3>Cadastrar Novo Leitor</h3>
                <p>Insira os dados pessoais do aluno, professor ou funcionário para permitir novos empréstimos</p>
            </div>

            <?php if (!empty($mensagem_erro)): ?>
                <div style="background-color: #fee2e2; color: #991b1b; padding: 14px 18px; border-radius: 12px; margin-bottom: 22px; font-weight: bold; font-size: 1.05rem;">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($mensagem_erro); ?>
                </div>
            <?php endif; ?>

            <form action="gerenciar_leitores.php" method="POST" class="form-cadastro">
                <div class="linha-form">
                    <div class="campo-form">
                        <label for="nome">Nome Completo *</label>
                        <input type="text" id="nome" name="nome" placeholder="Ex: Maria Silva" required>
                    </div>

                    <div class="campo-form">
                        <label for="email">E-mail *</label>
                        <input type="email" id="email" name="email" placeholder="maria.silva@escola.com" required>
                    </div>
                </div>

                <div class="linha-form">
                    <div class="campo-form">
                        <label for="telefone">Telefone / Celular</label>
                        <input type="text" id="telefone" name="telefone" placeholder="(11) 98765-4321">
                    </div>

                    <div class="campo-form">
                        <label for="tipo">Tipo de Leitor *</label>
                        <select id="tipo" name="tipo" required>
                            <option value="Aluno">Aluno</option>
                            <option value="Professor">Professor</option>
                            <option value="Funcionário">Funcionário</option>
                            <option value="Comunidade">Comunidade</option>
                        </select>
                    </div>
                </div>

                <div class="linha-form">
                    <div class="campo-form">
                        <label for="data_nascimento">Data de Nascimento *</label>
                        <input type="date" id="data_nascimento" name="data_nascimento" required>
                    </div>
                </div>

                <div class="botoes-form">
                    <a href="../pagina_leitores3/leitores.php" class="btn-cancelar">CANCELAR</a>
                    <button type="submit" class="btn-salvar">CADASTRAR LEITOR</button>
                </div>
            </form>
        </div>

    </main>

</body>
</html>