<?php

use yii\db\Migration;

class m170327_182727_add_column_slug_to_category_table extends Migration
{
    public function up()
    {
        $this->addColumn('category', 'slug', 'string');
    }

    public function down()
    {
        $this->dropColumn('category', 'slug');
    }
}
