<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsVerifiedToUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'is_verified' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 0,
                'null'       => false,
                'after'      => 'status',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'is_verified');
    }
}
