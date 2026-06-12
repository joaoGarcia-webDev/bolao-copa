<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titulo ?? 'Admin') ?> — Bolão da Copa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('css/bolao.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary navbar-expand-lg shadow-sm">
        <div class="container">
            <span class="navbar-brand mb-0 h1"><i class="bi bi-trophy-fill me-2"></i>Bolão da Copa — Admin</span>
            <div class="d-flex align-items-center text-white">
                <span class="me-3 d-none d-sm-inline"><?= esc(session()->get('admin_nome') ?? '') ?></span>
                <a href="<?= base_url('admin/logout') ?>" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right"></i> Sair</a>
            </div>
        </div>
    </nav>

    <div class="container py-3">
        <ul class="nav nav-pills mb-4 flex-wrap gap-1">
            <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/jogos') ?>"><i class="bi bi-calendar-event"></i> Jogos</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/palpites') ?>"><i class="bi bi-list-check"></i> Palpites</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/relatorios') ?>"><i class="bi bi-bar-chart-line"></i> Relatórios</a></li>
        </ul>

        <?php if (session()->getFlashdata('sucesso')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('sucesso')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('erro')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('erro')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
