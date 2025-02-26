<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreatePostsTable extends Migration {
    public function up() {
        $this->forge->addField([
            'id'      => ['type' => 'INT', 'auto_increment' => true, 'unsigned' => true],
            'title'   => ['type' => 'VARCHAR', 'constraint' => '255'],
            'content' => ['type' => 'TEXT'],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('posts');
    }

    public function down() {
        $this->forge->dropTable('posts');
    }
}
