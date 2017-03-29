<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product`.
 */
class m170322_124153_create_product_table extends Migration
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

        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'source_url' => $this->string(),
            'title' => $this->string(),
            'star' => $this->float(),
            'code' => $this->integer(),
            'info_tips' =>$this->string(),
            'current_price' => $this->float(),
            'time_cout_down' => $this->datetime(),
            'cost_price' => $this->float(),
            'image_url' => $this->string(),
            'description' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        
    }

    public function down()
    {
        $this->dropTable('{{%product}}');
    }
}
