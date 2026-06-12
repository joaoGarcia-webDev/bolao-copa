<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h2 class="mb-3"><?= esc($titulo) ?></h2>

<?php if (! empty($jogo)): ?>
    <p class="text-muted mb-3">
        Jogo: <strong><?= esc($jogo['time_1']) ?> x <?= esc($jogo['time_2']) ?></strong>
        — <a href="<?= base_url('admin/palpites') ?>">Ver todos</a>
    </p>
<?php endif; ?>

<div class="card card-bolao">
    <div class="card-body">
        <div class="mb-3" style="max-width: 400px;">
            <label class="form-label">Filtrar por jogo</label>
            <select class="form-select" onchange="if(this.value) window.location='<?= base_url('admin/palpites/jogo/') ?>'+this.value; else window.location='<?= base_url('admin/palpites') ?>';">
                <option value="">— Todos —</option>
                <?php foreach ($jogos as $j): ?>
                    <option value="<?= $j['id_jogo'] ?>" <?= ($filtro == $j['id_jogo']) ? 'selected' : '' ?>>
                        <?= esc($j['time_1']) ?> x <?= esc($j['time_2']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (empty($palpites)): ?>
            <p class="text-muted mb-0">Nenhum palpite encontrado.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-bolao mb-0">
                    <thead>
                        <tr><th>Apostador</th><th>RE</th><th>Jogo</th><th>Palpite</th><th>Status</th><th>Data</th><th>Ação</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($palpites as $p): ?>
                        <tr>
                            <td><?= esc($p['nome_completo']) ?></td>
                            <td><?= esc($p['re']) ?></td>
                            <td><?= esc($p['time_1']) ?> x <?= esc($p['time_2']) ?></td>
                            <td><strong><?= esc($p['palpite']) ?></strong></td>
                            <td><span class="badge badge-<?= strtolower($p['status_pagamento']) ?>"><?= esc($p['status_pagamento']) ?></span></td>
                            <td><?= esc(date('d/m/Y H:i', strtotime($p['data_envio']))) ?></td>
                            <td>
                                <form method="post" action="<?= base_url('admin/palpites/toggle/' . $p['id_palpite']) ?>" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <?= $p['status_pagamento'] === 'Pago' ? 'Marcar Pendente' : 'Marcar Pago' ?>
                                    </button>
                                </form>
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
