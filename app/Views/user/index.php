<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div class="card card-bolao">
    <div class="card-header bg-white">
        <h2 class="h5 mb-0"><i class="bi bi-calendar-event me-2"></i>Jogos Disponíveis para Apostas</h2>
    </div>
    <div class="card-body">
        <?php if (empty($jogos)): ?>
            <p class="text-center text-muted py-4 mb-0">Nenhum jogo disponível no momento. Volte em breve!</p>
        <?php else: ?>
            <div class="list-group list-group-flush">
                <?php foreach ($jogos as $jogo): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2 px-0">
                        <div>
                            <div class="fw-bold fs-5"><?= esc($jogo['time_1']) ?> x <?= esc($jogo['time_2']) ?></div>
                            <small class="text-muted">
                                <i class="bi bi-clock"></i> <?= esc(date('d/m/Y \à\s H:i', strtotime($jogo['data_jogo']))) ?>
                                &mdash; R$ <?= number_format($jogo['valor_palpite'], 2, ',', '.') ?>
                            </small>
                        </div>
                        <a href="<?= base_url('palpite/' . $jogo['id_jogo']) ?>" class="btn btn-success">
                            <i class="bi bi-pencil-square"></i> Apostar
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
