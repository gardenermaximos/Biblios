<?php
session_start();

require_once __DIR__ . '/../conexao.php';

if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../pagina_login1/index.php");
    exit();
}

$id_livro = $_GET['id'] ?? null;

if ($id_livro) {
    try {
        // Tenta eliminar o livro do acervo
        $stmt = $pdo->prepare("DELETE FROM tb_livros WHERE id_livro = :id");
        $stmt->execute([':id' => $id_livro]);

        header("Location: catalogo.php?status_msg=excluido");
        exit();
    } catch (PDOException $e) {
        // Caso exista empréstimo ativo ou histórico vinculado a este livro
        header("Location: catalogo.php?status_msg=erro_vinculo");
        exit();
    }
} else {
    header("Location: catalogo.php");
    exit();
}