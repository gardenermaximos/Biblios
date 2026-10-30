<?php
session_start();

// Redireciona para o login caso não esteja autenticado
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
            <a href="../pagina_inicial2/index.php" class="btn-voltar" title="Voltar ao Dashboard">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>NOVO EMPRÉSTIMO</h2>
        </div>

        <div class="header-direita">
            <span class="escola-badge">Escola Aprender Mais</span>
            <a href="../pagina_login1/logout.php" class="avatar" title="Clique para sair do sistema">
                <i class="fa-solid fa-user"></i>
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

            <form action="../pagina_emprestimos5/emprestimos.php" method="POST" class="form-emprestimo">
                
                <div class="grid-form-emprestimo">
                    
                    <!-- Coluna da Esquerda: Campos do Formulário -->
                    <div class="coluna-campos">
                        
                        <!-- Leitor -->
                        <div class="campo-form">
                            <label for="leitor">Leitor *</label>
                            <select id="leitor" name="leitor" required>
                                <option value="" disabled selected>Selecione o leitor</option>
                                <option value="1">Maria Silva (Aluno)</option>
                                <option value="2">João Santos (Aluno)</option>
                                <option value="3">Ana Souza (Professor)</option>
                            </select>
                        </div>

                        <!-- Livro -->
                        <div class="campo-form">
                            <label for="livro">Livro *</label>
                            <select id="livro" name="livro" required>
                                <option value="" disabled selected>Selecione o livro</option>
                                <option value="1">Dom Casmurro - Machado de Assis</option>
                                <option value="2">O Pequeno Príncipe - Antoine de Saint-Exupéry</option>
                                <option value="3">1984 - George Orwell</option>
                            </select>
                        </div>

                        <!-- Datas Side-by-Side -->
                        <div class="linha-datas">
                            <div class="campo-form">
                                <label for="data_emprestimo">Data de Empréstimo *</label>
                                <input type="text" id="data_emprestimo" name="data_emprestimo" placeholder="DD/MM/AAAA" onfocus="(this.type='date')" onblur="(this.type='text')" required>
                            </div>

                            <div class="campo-form">
                                <label for="previsao_devolucao">Previsão de Devolução *</label>
                                <input type="text" id="previsao_devolucao" name="previsao_devolucao" placeholder="DD/MM/AAAA" onfocus="(this.type='date')" onblur="(this.type='text')" required>
                            </div>
                        </div>

                        <!-- Observações -->
                        <div class="campo-form">
                            <label for="observacoes">Observações</label>
                            <textarea id="observacoes" name="observacoes" rows="3" placeholder="Digite observações (opcional)..."></textarea>
                        </div>

                    </div>

                    <!-- Coluna da Direita: Verificações de Segurança -->
                    <div class="coluna-seguranca">
                        <label class="titulo-seguranca">Verificações de Segurança</label>
                        
                        <div class="boxes-upload">
                            <!-- Box 1: Foto Leitor -->
                            <div class="upload-box">
                                <i class="fa-solid fa-plus icon-plus"></i>
                                <strong>Foto 3x4 do Leitor</strong>
                                <span class="btn-text-add">Adicionar</span>
                            </div>

                            <!-- Box 2: Capa Livro -->
                            <div class="upload-box">
                                <i class="fa-solid fa-plus icon-plus"></i>
                                <strong>Capa do Livro</strong>
                                <span class="btn-text-add">Adicionar</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Botões de Ação -->
                <div class="botoes-form">
                    <a href="../pagina_inicial2/index.php" class="btn-cancelar">CANCELAR</a>
                    <button type="submit" class="btn-salvar">REGISTRAR EMPRÉSTIMO</button>
                </div>

            </form>

        </div>

    </main>

</body>
</html>