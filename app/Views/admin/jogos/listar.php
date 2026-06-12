<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0"><?= esc($titulo) ?></h2>
    <a href="<?= base_url('admin/jogos/criar') ?>" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Novo Jogo</a>
</div>

<div class="card card-bolao">
    <div class="card-body p-0">
        <?php if (empty($jogos)): ?>
            <p class="p-3 mb-0 text-muted">Nenhum jogo cadastrado.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-bolao mb-0">
                    <thead><tr><th>Times</th><th>Data</th><th>Valor</th><th>Ações</th></tr></thead>
                    <tbody>
                        <?php foreach ($jogos as $jogo): ?>
                        <tr>
                            <td class="fw-semibold"><?= esc($jogo['time_1']) ?> x <?= esc($jogo['time_2']) ?></td>
                            <td><?= esc(date('d/m/Y H:i', strtotime($jogo['data_jogo']))) ?></td>
                            <td>R$ <?= number_format($jogo['valor_palpite'], 2, ',', '.') ?></td>
                            <td class="text-nowrap">
                                <a href="<?= base_url('admin/jogos/editar/' . $jogo['id_jogo']) ?>" class="btn btn-outline-secondary btn-sm">Editar</a>
                                <form method="post" action="<?= base_url('admin/jogos/excluir/' . $jogo['id_jogo']) ?>" class="d-inline"
                                      onsubmit="return confirm('Excluir este jogo?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Excluir</button>
                                </form>
                                <a href="<?= base_url('admin/palpites/jogo/' . $jogo['id_jogo']) ?>" class="btn btn-outline-success btn-sm">Palpites</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
