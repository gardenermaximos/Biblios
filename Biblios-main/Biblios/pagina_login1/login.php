<?php
// Inicia a sessão na primeira linha sem nenhum caractere ou espaço antes do <?php
session_start();

$mensagem_erro = "";

// Verifica se o formulário foi enviado através do botão "ENTRAR"
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Pega nos dados digitados e remove espaços acidentais nas pontas
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    // E-mail e Senha definidos para o acesso
    $email_correto = "bibliotecario@escola.com";
    $senha_correta = "123456";

    // Validação
    if ($usuario === $email_correto && $senha === $senha_correta) {
        
        // Define a variável global de sessão do usuário
        $_SESSION['usuario_logado'] = $usuario;
        
        // Força o PHP a gravar a sessão no disco IMEDIATAMENTE antes do redirecionamento
        session_write_close();
        
        // Redireciona para o Dashboard
        header("Location: ../pagina_inicial2/index.php");
        exit();
    } else {
        $mensagem_erro = "E-mail ou senha incorretos! Tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BibliOS - Login</title>

    <!-- Ícones Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Nosso CSS -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body class="pagina-login">

    <!-- Ilustrações de Livros Flutuantes no Fundo -->
    <div class="livro-fundo livro-1 cor-verde"><i class="fa-solid fa-book"></i></div>
    <div class="livro-fundo livro-2 cor-marrom"><i class="fa-solid fa-book-open"></i></div>
    <div class="livro-fundo livro-3 cor-verde"><i class="fa-solid fa-book-bookmark"></i></div>
    <div class="livro-fundo livro-4 cor-marrom"><i class="fa-solid fa-book"></i></div>

    <!-- Caixa Principal de Login -->
    <div class="card-login">

        <!-- LOGO OFICIAL -->
        <div class="logo-container">
            <img src="logo.png" alt="BibliOS - Sistema de Gerenciamento de Biblioteca" class="logo-imagem">
        </div>

        <!-- MENSAGEM DE ERRO -->
        <?php if (!empty($mensagem_erro)): ?>
            <div class="alerta-erro">
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $mensagem_erro; ?>
            </div>
        <?php endif; ?>

        <!-- Formulário de Acesso -->
        <form action="login.php" method="POST">
            
            <!-- Campo Usuário -->
            <div class="campo-grupo">
                <label for="usuario">
                    <i class="fa-solid fa-user icone-rotulo"></i> Usuário ou E-mail:
                </label>
                <div class="input-container">
                    <input type="email" id="usuario" name="usuario" placeholder="bibliotecario@escola.com" required>
                </div>
            </div>

            <!-- Campo Senha -->
            <div class="campo-grupo">
                <label for="senha">
                    <i class="fa-solid fa-lock icone-rotulo"></i> Sua Senha:
                </label>
                <div class="input-container">
                    <input type="password" id="senha" name="senha" placeholder="••••••••" required>
                    <i class="fa-solid fa-eye icone-olho"></i>
                </div>
            </div>

            <!-- Botão Entrar -->
            <button type="submit" class="btn-entrar">
                ENTRAR NO SISTEMA <i class="fa-solid fa-right-to-bracket"></i>
            </button>

            <!-- Link de Ajuda -->
            <a href="#" class="link-esqueceu">Esqueceu sua senha? Clique aqui para ajuda</a>

        </form>

    </div>

</body>
</html>