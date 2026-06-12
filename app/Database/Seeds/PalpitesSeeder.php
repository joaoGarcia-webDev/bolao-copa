<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PalpitesSeeder extends Seeder
{
    public function run()
    {
        if ($this->db->table('palpites')->countAllResults() > 0) {
            return;
        }

        $jogos = $this->db->table('jogos')->orderBy('id_jogo', 'ASC')->get()->getResultArray();

        if (count($jogos) < 2) {
            return;
        }

        $agora = date('Y-m-d H:i:s');

        $palpites = [
            [
                'id_jogo'          => $jogos[0]['id_jogo'],
                'nome_completo'    => 'João Silva',
                're'               => 'RE001',
                'palpite'          => '2x1',
                'status_pagamento' => 'Pendente',
                'data_envio'       => $agora,
                'created_at'       => $agora,
                'updated_at'       => $agora,
            ],
            [
                'id_jogo'          => $jogos[0]['id_jogo'],
                'nome_completo'    => 'Maria Santos',
                're'               => 'RE002',
                'palpite'          => '1x1',
                'status_pagamento' => 'Pago',
                'data_envio'       => $agora,
                'created_at'       => $agora,
                'updated_at'       => $agora,
            ],
            [
                'id_jogo'          => $jogos[1]['id_jogo'],
                'nome_completo'    => 'João Silva',
                're'               => 'RE001',
                'palpite'          => '0x2',
                'status_pagamento' => 'Pendente',
                'data_envio'       => $agora,
                'created_at'       => $agora,
                'updated_at'       => $agora,
            ],
            [
                'id_jogo'          => $jogos[1]['id_jogo'],
                'nome_completo'    => 'Carlos Oliveira',
                're'               => 'RE003',
                'palpite'          => '3x1',
                'status_pagamento' => 'Pago',
                'data_envio'       => $agora,
                'created_at'       => $agora,
                'updated_at'       => $agora,
            ],
        ];

        $this->db->table('palpites')->insertBatch($palpites);
    }
}
