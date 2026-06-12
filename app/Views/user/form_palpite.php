<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div class="card card-bolao">
    <div class="card-header bg-white">
        <h2 class="h5 mb-0">Registrar Palpite</h2>
    </div>
    <div class="card-body">
        <div class="alert alert-info py-2">
            <strong><?= esc($jogo['time_1']) ?> x <?= esc($jogo['time_2']) ?></strong><br>
            <small><?= esc(date('d/m/Y \à\s H:i', strtotime($jogo['data_jogo']))) ?> — Valor: R$ <?= number_format($jogo['valor_palpite'], 2, ',', '.') ?></small>
        </div>

        <?php if (session()->getFlashdata('erros')): ?>
            <div class="alert alert-danger">
                <?php foreach (session()->getFlashdata('erros') as $msg): ?>
                    <div><?= esc(is_array($msg) ? implode(', ', $msg) : $msg) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('palpite/enviar') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="id_jogo" value="<?= esc($jogo['id_jogo']) ?>">

            <div class="mb-3">
                <label for="nome_completo" class="form-label">Nome Completo</label>
                <input type="text" class="form-control" id="nome_completo" name="nome_completo"
                       value="<?= esc(old('nome_completo')) ?>" required minlength="3">
            </div>
            <div class="mb-3">
                <label for="re" class="form-label">RE (Registro do Empregado)</label>
                <input type="text" class="form-control" id="re" name="re"
                       value="<?= esc(old('re')) ?>" required>
                <div class="form-text">Um RE pode apostar em vários jogos, mas apenas uma vez por jogo.</div>
            </div>
            <div class="mb-3">
                <label for="palpite" class="form-label">Palpite (formato NxN)</label>
                <input type="text" class="form-control" id="palpite" name="palpite"
                       value="<?= esc(old('palpite')) ?>" required pattern="\d+x\d+"
                       placeholder="Ex: 2x1" title="Formato: número x número (ex: 2x1, 3x0)">
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">Enviar Palpite</button>
                <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary">Voltar</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
