<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUniqueReJogoToPalpites extends Migration
{
    public function up()
    {
        $this->forge->addUniqueKey(['re', 'id_jogo'], 'uk_palpites_re_jogo');
    }

    public function down()
    {
        $this->forge->dropKey('palpites', 'uk_palpites_re_jogo');
    }
}
