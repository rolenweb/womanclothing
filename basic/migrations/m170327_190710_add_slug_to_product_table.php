<?php

use yii\db\Migration;

class m170327_190710_add_slug_to_product_table extends Migration
{
    public function up()
    {
        $this->addColumn('product', 'slug', 'string');
    }

    public function down()
    {
        $this->dropColumn('product', 'slug');
    }
}
