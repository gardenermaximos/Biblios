<?php
session_start();

require_once __DIR__ . '/../conexao.php';

if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../pagina_login1/index.php");
    exit();
}

$id_leitor = $_GET['id'] ?? null;

if ($id_leitor) {
    try {
        // Tenta excluir o leitor
        $stmt = $pdo->prepare("DELETE FROM tb_leitores WHERE id_leitor = :id");
        $stmt->execute([':id' => $id_leitor]);

        header("Location: ../pagina_leitores3/leitores.php?status_msg=excluido");
        exit();
    } catch (PDOException $e) {
        // Se falhar devido a restrição de chave estrangeira (empréstimos ativos ou passados vinculados ao leitor)
        header("Location: ../pagina_leitores3/leitores.php?status_msg=erro_vinculo");
        exit();
    }
} else {
    header("Location: ../pagina_leitores3/leitores.php");
    exit();
}