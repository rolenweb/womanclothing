<?php

use yii\db\Migration;

/**
 * Handles the creation of table `link`.
 */
class m170318_145426_create_link_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%link}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx-link-url', '{{%link}}', 'url');
    }

    public function down()
    {
        $this->dropIndex('idx-link-url', '{{%link}}');
        $this->dropTable('{{%link}}');
    }
}
