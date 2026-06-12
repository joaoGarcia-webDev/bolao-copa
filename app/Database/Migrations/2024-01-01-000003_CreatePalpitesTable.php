<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePalpitesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_palpite' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_jogo' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nome_completo' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            're' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'palpite' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'status_pagamento' => [
                'type'       => 'ENUM',
                'constraint' => ['Pendente', 'Pago'],
                'default'    => 'Pendente',
            ],
            'data_envio' => [
                'type' => 'DATETIME',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_palpite', true);
        $this->forge->addKey('id_jogo');
        $this->forge->addKey('status_pagamento');
        $this->forge->addKey('re');
        $this->forge->addForeignKey('id_jogo', 'jogos', 'id_jogo', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('palpites');
    }

    public function down()
    {
        $this->forge->dropTable('palpites');
    }
}
