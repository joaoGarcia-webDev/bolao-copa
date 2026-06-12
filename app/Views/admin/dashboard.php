<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h2 class="mb-4"><?= esc($titulo) ?></h2>

<div class="row g-3 mb-4">
    <div class="col-6 col-md"><div class="card stat-card text-center p-3"><div class="stat-value"><?= esc($total_jogos) ?></div><small class="text-muted">Jogos</small></div></div>
    <div class="col-6 col-md"><div class="card stat-card text-center p-3"><div class="stat-value"><?= esc($total_palpites) ?></div><small class="text-muted">Palpites</small></div></div>
    <div class="col-6 col-md"><div class="card stat-card text-center p-3"><div class="stat-value">R$ <?= number_format($total_arrecadado, 2, ',', '.') ?></div><small class="text-muted">Arrecadado</small></div></div>
    <div class="col-6 col-md"><div class="card stat-card text-center p-3"><div class="stat-value"><?= esc($total_pendentes) ?></div><small class="text-muted">Pendentes</small></div></div>
    <div class="col-6 col-md"><div class="card stat-card text-center p-3"><div class="stat-value"><?= esc($total_pagos) ?></div><small class="text-muted">Pagos</small></div></div>
</div>

<div class="card card-bolao">
    <div class="card-header bg-white fw-semibold">Últimos Palpites</div>
    <div class="card-body p-0">
        <?php if (empty($ultimos_palpites)): ?>
            <p class="p-3 mb-0 text-muted">Nenhum palpite registrado ainda.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-bolao mb-0">
                    <thead><tr><th>Apostador</th><th>Jogo</th><th>Palpite</th><th>Status</th><th>Data</th></tr></thead>
                    <tbody>
                        <?php foreach ($ultimos_palpites as $p): ?>
                        <tr>
                            <td><?= esc($p['nome_completo']) ?></td>
                            <td><?= esc($p['time_1']) ?> x <?= esc($p['time_2']) ?></td>
                            <td><strong><?= esc($p['palpite']) ?></strong></td>
                            <td><span class="badge badge-<?= strtolower($p['status_pagamento']) ?>"><?= esc($p['status_pagamento']) ?></span></td>
                            <td><?= esc(date('d/m/Y H:i', strtotime($p['data_envio']))) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
