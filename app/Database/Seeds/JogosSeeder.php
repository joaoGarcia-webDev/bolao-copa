<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class JogosSeeder extends Seeder
{
    public function run()
    {
        if ($this->db->table('jogos')->countAllResults() > 0) {
            return;
        }

        $jogos = [
            [
                'time_1'        => 'Brasil',
                'time_2'        => 'Argentina',
                'data_jogo'     => date('Y-m-d H:i:s', strtotime('+14 days 20:00')),
                'valor_palpite' => 10.00,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'time_1'        => 'Alemanha',
                'time_2'        => 'França',
                'data_jogo'     => date('Y-m-d H:i:s', strtotime('+16 days 16:00')),
                'valor_palpite' => 10.00,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'time_1'        => 'Portugal',
                'time_2'        => 'Espanha',
                'data_jogo'     => date('Y-m-d H:i:s', strtotime('+18 days 18:00')),
                'valor_palpite' => 15.00,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'time_1'        => 'Inglaterra',
                'time_2'        => 'Itália',
                'data_jogo'     => date('Y-m-d H:i:s', strtotime('+21 days 15:00')),
                'valor_palpite' => 12.00,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('jogos')->insertBatch($jogos);
    }
}
