<?php
require __DIR__ . '/config/database.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $status = $_POST['status'] ?? '';

    if (empty($titulo)) {
        $erro = 'O título é obrigatório!';
    } elseif (!in_array($status, ['pendente', 'em_andamento', 'concluida'])) {
        $erro = 'Selecione um status válido!';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO tarefas (titulo, descricao, status) VALUES (?, ?, ?)');
            $stmt->execute([$titulo, $descricao, $status]);
            $sucesso = '✅ Tarefa cadastrada com sucesso!';
            $titulo = $descricao = '';
            $status = 'pendente';
        } catch (PDOException $e) {
            $erro = '❌ Erro ao cadastrar: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Tarefa</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main class="container">
        <h1>Nova Tarefa</h1>
        
        <?php if ($erro): ?>
            <p style="color: red; background: #ffebee; padding: 10px; border-radius: 4px;"><?= htmlspecialchars($erro) ?></p>
        <?php endif; ?>
        
        <?php if ($sucesso): ?>
            <p style="color: green; background: #e8f5e9; padding: 10px; border-radius: 4px;"><?= htmlspecialchars($sucesso) ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="titulo">Título *</label>
            <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($titulo ?? '') ?>" required>

            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" rows="4"><?= htmlspecialchars($descricao ?? '') ?></textarea>

            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="pendente" <?= ($status ?? '') === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                <option value="em_andamento" <?= ($status ?? '') === 'em_andamento' ? 'selected' : '' ?>>Em andamento</option>
                <option value="concluida" <?= ($status ?? '') === 'concluida' ? 'selected' : '' ?>>Concluída</option>
            </select>

            <p style="margin-top: 20px;">
                <button type="submit">Salvar Tarefa</button>
                <a href="index.php" class="botao" style="background: #ccc;">Voltar</a>
            </p>
        </form>
    </main>
</body>
</html>
