<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h2 class="mb-4"><?= esc($titulo) ?></h2>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="card stat-card text-center p-3"><div class="stat-value"><?= esc($total_geral_palpites) ?></div><small class="text-muted">Total Palpites</small></div></div>
    <div class="col-6 col-md-3"><div class="card stat-card text-center p-3"><div class="stat-value">R$ <?= number_format($total_geral_arrecadado, 2, ',', '.') ?></div><small class="text-muted">Arrecadado</small></div></div>
    <div class="col-6 col-md-3"><div class="card stat-card text-center p-3"><div class="stat-value">R$ <?= number_format($total_geral_pago, 2, ',', '.') ?></div><small class="text-muted">Pago</small></div></div>
    <div class="col-6 col-md-3"><div class="card stat-card text-center p-3"><div class="stat-value">R$ <?= number_format($total_geral_pendente, 2, ',', '.') ?></div><small class="text-muted">Pendente</small></div></div>
</div>

<div class="card card-bolao">
    <div class="card-body p-0">
        <?php if (empty($relatorio)): ?>
            <p class="p-3 mb-0 text-muted">Nenhum dado para exibir.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-bolao mb-0">
                    <thead>
                        <tr><th>Jogo</th><th>Data</th><th>Valor Unit.</th><th>Palpites</th><th>Total</th><th>Pago</th><th>Pendente</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($relatorio as $r): ?>
                        <tr>
                            <td class="fw-semibold"><?= esc($r['time_1']) ?> x <?= esc($r['time_2']) ?></td>
                            <td><?= esc(date('d/m/Y H:i', strtotime($r['data_jogo']))) ?></td>
                            <td>R$ <?= number_format($r['valor_palpite'], 2, ',', '.') ?></td>
                            <td><?= esc($r['total_palpites']) ?></td>
                            <td>R$ <?= number_format($r['arrecadado_total'], 2, ',', '.') ?></td>
                            <td class="text-success">R$ <?= number_format($r['arrecadado_pago'], 2, ',', '.') ?></td>
                            <td class="text-warning">R$ <?= number_format($r['arrecadado_pendente'], 2, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
