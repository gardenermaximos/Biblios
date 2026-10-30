<?php
session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../pagina_login1/login.php");
    exit();
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

    <!-- Cabeçalho Superior -->
    <header class="header-catalogo">
        <div class="header-esquerda">
            <a href="../pagina_inicial2/index.php" class="btn-voltar" title="Voltar ao Dashboard">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>CATÁLOGO DE LIVROS</h2>
        </div>

        <div class="header-direita">
            <span class="escola-badge">Escola Aprender Mais</span>
            <a href="../pagina_login1/logout.php" class="avatar" title="Clique para sair do sistema">
                <i class="fa-solid fa-user"></i>
            </a>
        </div>
    </header>

    <!-- Abas de Navegação -->
    <div class="container-abas">
        <a href="catalogo.php" class="aba-btn">LISTA</a>
        <a href="cadastrar_livro.php" class="aba-btn aba-ativa">CADASTRO</a>
    </div>

    <hr class="linha-divisoria">

    <!-- Conteúdo Principal - Formulário -->
    <main class="container-conteudo">

        <div class="card-formulario">
            
            <div class="form-cabecalho">
                <h3>Cadastrar Novo Livro no Acervo</h3>
                <p>Adicione um livro ao acervo local ou solicite o exemplar a uma biblioteca parceira</p>
            </div>

            <form action="catalogo.php" method="POST" class="form-cadastro">
                
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
                        <label for="isbn">Código ISBN *</label>
                        <input type="text" id="isbn" name="isbn" placeholder="978-85-01-XXXXX-X" required>
                    </div>

                    <div class="campo-form">
                        <label for="genero">Gênero / Categoria *</label>
                        <select id="genero" name="genero" required>
                            <option value="">Selecione o gênero</option>
                            <option value="Clássico Nacional">Clássico Nacional</option>
                            <option value="Literatura Estrangeira">Literatura Estrangeira</option>
                            <option value="Distopia">Distopia</option>
                            <option value="Romance">Romance</option>
                            <option value="Científico">Científico</option>
                        </select>
                    </div>
                </div>

                <div class="linha-form">
                    <div class="campo-form">
                        <label for="quantidade">Quantidade de Exemplares *</label>
                        <input type="number" id="quantidade" name="quantidade" min="1" value="1" required>
                    </div>

                    <div class="campo-form">
                        <label for="biblioteca_origem">Origem do Livro</label>
                        <select id="biblioteca_origem" name="biblioteca_origem">
                            <option value="local">Acervo Próprio (Biblioteca Local)</option>
                            <option value="parceira_a">Solicitar a: Biblioteca Central</option>
                            <option value="parceira_b">Solicitar a: Biblioteca Municipal</option>
                            <option value="parceira_c">Solicitar a: Rede Escolar Unificada</option>
                        </select>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="botoes-form">
                    <a href="catalogo.php" class="btn-cancelar">CANCELAR</a>
                    <button type="submit" name="acao" value="solicitar" class="btn-solicitar">
                        <i class="fa-solid fa-building-columns"></i> PEDIR A OUTRA BIBLIOTECA
                    </button>
                    <button type="submit" name="acao" value="salvar" class="btn-salvar">
                        SALVAR NO ACERVO
                    </button>
                </div>

            </form>

        </div>

    </main>

</body>
</html>