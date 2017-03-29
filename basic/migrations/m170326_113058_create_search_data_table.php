<?php

use yii\db\Migration;

/**
 * Handles the creation of table `search_data`.
 */
class m170326_113058_create_search_data_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%search_data}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'name_ss' => $this->string(),
            'title' => $this->string(),
            'url' => $this->string(),
            'snippet' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        
    }

    public function down()
    {
        $this->dropTable('{{%search_data}}');
    }
}
