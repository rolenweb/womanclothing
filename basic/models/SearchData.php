<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "search_data".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $name_ss
 * @property string $title
 * @property string $url
 * @property string $snippet
 * @property integer $created_at
 * @property integer $updated_at
 */
class SearchData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'search_data';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'created_at', 'updated_at'], 'integer'],
            [['snippet'], 'string'],
            [['name_ss', 'title', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'name_ss' => 'Name Ss',
            'title' => 'Title',
            'url' => 'Url',
            'snippet' => 'Snippet',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
