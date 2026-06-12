<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Bolão da Copa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('css/bolao.css') ?>" rel="stylesheet">
</head>
<body class="login-bg d-flex align-items-center justify-content-center">
    <div class="card login-card p-4" style="width: 100%; max-width: 400px;">
        <div class="text-center mb-4">
            <i class="bi bi-trophy-fill text-primary" style="font-size: 2.5rem;"></i>
            <h1 class="h4 mt-2 text-primary fw-bold">Admin — Bolão da Copa</h1>
        </div>

        <?php if (session()->getFlashdata('erro')): ?>
            <div class="alert alert-danger py-2"><?= esc(session()->getFlashdata('erro')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('sucesso')): ?>
            <div class="alert alert-success py-2"><?= esc(session()->getFlashdata('sucesso')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('erros')): ?>
            <div class="alert alert-danger py-2">
                <?php foreach (session()->getFlashdata('erros') as $erro): ?>
                    <div><?= esc(is_array($erro) ? implode(', ', $erro) : $erro) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('admin/login') ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuário</label>
                <input type="text" class="form-control" id="usuario" name="usuario"
                       value="<?= esc(old('usuario')) ?>" required autofocus>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
