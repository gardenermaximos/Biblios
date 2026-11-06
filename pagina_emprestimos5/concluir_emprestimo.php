<?php
session_start();

require_once __DIR__ . '/../conexao.php';

if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../pagina_login1/login.php");
    exit();
}

$id_emprestimo = $_GET['id'] ?? null;

if ($id_emprestimo) {
    try {
        $pdo->beginTransaction();

        // 1. Busca o livro vinculado a este empréstimo
        $stmtBusca = $pdo->prepare("SELECT fk_livro, data_devolucao FROM tb_emprestimos WHERE id_emprestimo = :id LIMIT 1");
        $stmtBusca->execute([':id' => $id_emprestimo]);
        $emp = $stmtBusca->fetch();

        if ($emp && is_null($emp['data_devolucao'])) {
            $id_livro = $emp['fk_livro'];

            // 2. Regista a devolução com data/hora atual
            $stmtDev = $pdo->prepare("UPDATE tb_emprestimos 
                                     SET data_devolucao = NOW(), observacao_devolucao = 'Concluído via atalho rápido' 
                                     WHERE id_emprestimo = :id");
            $stmtDev->execute([':id' => $id_emprestimo]);

            // 3. Atualiza o livro para disponível no acervo
            $stmtLivro = $pdo->prepare("UPDATE tb_livros SET status = 1 WHERE id_livro = :id_livro");
            $stmtLivro->execute([':id_livro' => $id_livro]);

            $pdo->commit();
            header("Location: emprestimos.php?status_msg=concluido");
            exit();
        } else {
            $pdo->rollBack();
            header("Location: emprestimos.php?status_msg=ja_concluido");
            exit();
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        header("Location: emprestimos.php?status_msg=erro");
        exit();
    }
} else {
    header("Location: emprestimos.php");
    exit();
}