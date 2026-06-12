<?php

namespace App\Models;

use CodeIgniter\Model;

class PalpiteModel extends Model
{
    protected $table            = 'palpites';
    protected $primaryKey       = 'id_palpite';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'id_jogo',
        'nome_completo',
        're',
        'palpite',
        'status_pagamento',
        'data_envio',
    ];

    protected $useTimestamps = true;
    protected $createdField    = 'created_at';
    protected $updatedField    = 'updated_at';

    protected $validationRules = [
        'id_jogo'       => 'required|is_natural_no_zero',
        'nome_completo' => 'required|min_length[3]|max_length[150]',
        're'            => 'required|min_length[1]|max_length[20]',
        'palpite'       => 'required|regex_match[/^\d+x\d+$/i]',
        'status_pagamento' => 'required|in_list[Pendente,Pago]',
    ];

    protected $validationMessages = [
        'nome_completo' => [
            'required'   => 'O nome completo é obrigatório.',
            'min_length' => 'O nome deve ter no mínimo 3 caracteres.',
        ],
        're' => [
            'required' => 'O RE é obrigatório.',
        ],
        'palpite' => [
            'required'    => 'O palpite é obrigatório.',
            'regex_match' => 'O palpite deve estar no formato NxN (ex: 2x1).',
        ],
    ];

    /**
     * Verifica se o RE já possui palpite para o jogo informado.
     * Um RE pode apostar em vários jogos, mas apenas uma vez por jogo.
     */
    public function jaApostouNesteJogo(int $idJogo, string $re): bool
    {
        return $this->where('id_jogo', $idJogo)
                    ->where('re', trim($re))
                    ->countAllResults() > 0;
    }

    public function getTotalPalpites(): int
    {
        return $this->countAllResults();
    }

    public function getTotalArrecadado(): float
    {
        $result = $this->db->table($this->table . ' p')
            ->selectSum('j.valor_palpite', 'total')
            ->join('jogos j', 'j.id_jogo = p.id_jogo')
            ->get()
            ->getRowArray();

        return (float) ($result['total'] ?? 0);
    }

    public function getTotalPorStatus(string $status): int
    {
        return $this->where('status_pagamento', $status)->countAllResults();
    }

    public function getPalpitesComJogo(): array
    {
        return $this->db->table($this->table . ' p')
            ->select('p.*, j.time_1, j.time_2, j.data_jogo, j.valor_palpite')
            ->join('jogos j', 'j.id_jogo = p.id_jogo')
            ->orderBy('p.data_envio', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getPalpitesPorJogo(int $idJogo): array
    {
        return $this->db->table($this->table . ' p')
            ->select('p.*, j.time_1, j.time_2, j.data_jogo, j.valor_palpite')
            ->join('jogos j', 'j.id_jogo = p.id_jogo')
            ->where('p.id_jogo', $idJogo)
            ->orderBy('p.data_envio', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function togglePagamento(int $id): bool
    {
        $palpite = $this->find($id);

        if (! $palpite) {
            return false;
        }

        $novoStatus = $palpite['status_pagamento'] === 'Pago' ? 'Pendente' : 'Pago';

        return $this->update($id, ['status_pagamento' => $novoStatus]);
    }

    public function getRelatorioporJogo(): array
    {
        return $this->db->table('jogos j')
            ->select("
                j.id_jogo,
                j.time_1,
                j.time_2,
                j.data_jogo,
                j.valor_palpite,
                COUNT(p.id_palpite) AS total_palpites,
                COALESCE(SUM(j.valor_palpite), 0) AS arrecadado_total,
                COALESCE(SUM(CASE WHEN p.status_pagamento = 'Pago' THEN j.valor_palpite ELSE 0 END), 0) AS arrecadado_pago,
                COALESCE(SUM(CASE WHEN p.status_pagamento = 'Pendente' THEN j.valor_palpite ELSE 0 END), 0) AS arrecadado_pendente
            ")
            ->join('palpites p', 'p.id_jogo = j.id_jogo', 'left')
            ->groupBy('j.id_jogo, j.time_1, j.time_2, j.data_jogo, j.valor_palpite')
            ->orderBy('j.data_jogo', 'ASC')
            ->get()
            ->getResultArray();
    }
}
