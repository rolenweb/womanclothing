<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m170319_120732_create_category_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'parent_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        
    }

    public function down()
    {
        $this->dropTable('{{%category}}');
    }
}
