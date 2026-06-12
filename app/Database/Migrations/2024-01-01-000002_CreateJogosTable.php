<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJogosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_jogo' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'time_1' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'time_2' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'data_jogo' => [
                'type' => 'DATETIME',
            ],
            'valor_palpite' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => '0.00',
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

        $this->forge->addKey('id_jogo', true);
        $this->forge->addKey('data_jogo');
        $this->forge->createTable('jogos');
    }

    public function down()
    {
        $this->forge->dropTable('jogos');
    }
}
