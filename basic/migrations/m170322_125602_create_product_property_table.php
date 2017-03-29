<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_property`.
 */
class m170322_125602_create_product_property_table extends Migration
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

        $this->createTable('{{%product_property}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'title' => $this->string(),
            'value' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        
    }

    public function down()
    {
        $this->dropTable('{{%product_property}}');
    }
}
