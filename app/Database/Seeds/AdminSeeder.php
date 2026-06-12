<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $exists = $this->db->table('administradores')
            ->where('usuario', 'admin')
            ->countAllResults();

        if ($exists > 0) {
            return;
        }

        $this->db->table('administradores')->insert([
            'nome'       => 'Administrador',
            'usuario'    => 'admin',
            'senha_hash' => password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
