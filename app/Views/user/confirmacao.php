<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div class="card card-bolao text-center">
    <div class="card-body py-5">
        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
        <h2 class="mt-3">Palpite Registrado com Sucesso!</h2>

        <?php if (session()->getFlashdata('nome_confirmacao')): ?>
            <p class="text-muted mt-3">
                <strong><?= esc(session()->getFlashdata('nome_confirmacao')) ?></strong>,
                seu palpite <strong><?= esc(session()->getFlashdata('palpite_confirmacao')) ?></strong>
                para o jogo <strong><?= esc(session()->getFlashdata('jogo_confirmacao')) ?></strong>
                foi registrado.
            </p>
            <p class="text-muted small">
                Pagamento com status <span class="badge badge-pendente">Pendente</span>.
                Dirija-se ao responsável para efetuar o pagamento.
            </p>
        <?php endif; ?>

        <a href="<?= base_url('/') ?>" class="btn btn-success mt-3"><i class="bi bi-house"></i> Voltar aos Jogos</a>
    </div>
</div>

<?= $this->endSection() ?>
