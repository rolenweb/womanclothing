<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "schedule_parse_search".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class ScheduleParseSearch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schedule_parse_search';
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public static function current()
    {
        $current = self::find()->limit(1)->one();
        if (empty($current)) {
            $product = Product::find()->limit(1)->one();
            $schedule = new self;
            $schedule->product_id = $product->id;
            $schedule->save();
            return $schedule;
        }else{
            return $current;
        }
    }

    public function next()
    {
        $nextProduct = Product::find()->where(['>','id',$this->product_id])->limit(1)->one();
        if (empty($nextProduct)) {
            $product = Product::find()->limit(1)->one();
            $this->product_id = $product->id;
            $this->save();
        }else{
            $this->product_id = $nextProduct->id;
            $this->save();
        }
    }
}
