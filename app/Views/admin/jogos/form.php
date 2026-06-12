<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h2 class="mb-4"><?= esc($titulo) ?></h2>

<div class="card card-bolao" style="max-width: 520px;">
    <div class="card-body">
        <?php if (session()->getFlashdata('erros')): ?>
            <div class="alert alert-danger">
                <?php foreach (session()->getFlashdata('erros') as $msg): ?>
                    <div><?= esc(is_array($msg) ? implode(', ', $msg) : $msg) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php $isEdit = ! empty($jogo); ?>
        <form method="post" action="<?= $isEdit ? base_url('admin/jogos/atualizar/' . $jogo['id_jogo']) : base_url('admin/jogos/salvar') ?>">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="time_1" class="form-label">Time 1</label>
                <input type="text" class="form-control" id="time_1" name="time_1"
                       value="<?= esc(old('time_1', $jogo['time_1'] ?? '')) ?>" required>
            </div>
            <div class="mb-3">
                <label for="time_2" class="form-label">Time 2</label>
                <input type="text" class="form-control" id="time_2" name="time_2"
                       value="<?= esc(old('time_2', $jogo['time_2'] ?? '')) ?>" required>
            </div>
            <div class="mb-3">
                <label for="data_jogo" class="form-label">Data e Hora do Jogo</label>
                <input type="datetime-local" class="form-control" id="data_jogo" name="data_jogo"
                       value="<?= esc(old('data_jogo', $jogo['data_jogo_input'] ?? '')) ?>" required>
            </div>
            <div class="mb-3">
                <label for="valor_palpite" class="form-label">Valor do Palpite (R$)</label>
                <input type="number" class="form-control" id="valor_palpite" name="valor_palpite"
                       step="0.01" min="0.01" value="<?= esc(old('valor_palpite', $jogo['valor_palpite'] ?? '')) ?>" required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Atualizar' : 'Cadastrar' ?></button>
                <a href="<?= base_url('admin/jogos') ?>" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
