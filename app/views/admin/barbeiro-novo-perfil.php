<?php
// app/views/admin/barbeiro-novo-perfil.php
$usuariosDisponiveis = $usuariosDisponiveis ?? [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Barbeiro - Perfil</title>
</head>
<body>
    <h1>Passo 2: Completar perfil profissional</h1>

    <?php if (empty($usuariosDisponiveis)): ?>
        <p>
            Não há usuários pendentes de vínculo. 
            
            <a href="/barbearia/admin/barbeiros/novo-usuario">
                Criar uma nova conta
            </a> 
            
            primeiro.
        </p>
    <?php else: ?>
        <form action="/barbearia/admin/barbeiros/novo-perfil" method="POST" enctype="multipart/form-data">
            <label for="usuario_id">Conta vinculada</label>

            <select id="usuario_id" name="usuario_id" required>
                <?php foreach ($usuariosDisponiveis as $usuario): ?>
                    <option value="<?= $usuario['id'] ?>">
                        <?= htmlspecialchars($usuario['email']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="nome">Nome de exibição</label>
            <input type="text" id="nome" name="nome" required>

            <label for="especialidade">Especialidade</label>
            <input type="text" id="especialidade" name="especialidade">

            <label for="foto">Foto</label>
            <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp">

            <button type="submit">Salvar perfil</button>
        </form>
    <?php endif; ?>

    <a href="/barbearia/admin/barbeiros">
        Cancelar
    </a>
</body>
</html>