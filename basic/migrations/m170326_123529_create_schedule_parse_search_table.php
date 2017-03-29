<?php

use yii\db\Migration;

/**
 * Handles the creation of table `schedule_parse_search`.
 */
class m170326_123529_create_schedule_parse_search_table extends Migration
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

        $this->createTable('{{%schedule_parse_search}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        
    }

    public function down()
    {
        $this->dropTable('{{%schedule_parse_search}}');
    }
}
