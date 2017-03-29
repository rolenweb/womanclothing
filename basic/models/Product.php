<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $source_url
 * @property string $title
 * @property double $star
 * @property integer $code
 * @property string $info_tips
 * @property double $current_price
 * @property string $time_cout_down
 * @property double $cost_price
 * @property string $image_url
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'slug' => [
                'class' => 'app\components\SlugProduct',
                'in_attribute' => 'title',
                'out_attribute' => 'slug',
                'translit' => false
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'code', 'created_at', 'updated_at'], 'integer'],
            [['star', 'current_price', 'cost_price'], 'number'],
            [['time_cout_down'], 'safe'],
            [['description'], 'string'],
            [['source_url', 'title', 'info_tips', 'image_url','slug'], 'string', 'max' => 255],
            [['code'],'unique'],
            [['source_url','image_url'],'url'],
            [['description','title','info_tips'],'trim'],
            [['category_id', 'code','cost_price','source_url','title', 'image_url'],'required'],
            [['star','current_price','cost_price'],'filter','filter' => 'floatval']
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'source_url' => 'Source Url',
            'title' => 'Title',
            'star' => 'Star',
            'code' => 'Code',
            'info_tips' => 'Info Tips',
            'current_price' => 'Current Price',
            'time_cout_down' => 'Time Cout Down',
            'cost_price' => 'Cost Price',
            'image_url' => 'Image Url',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getProperties()
    {
        return $this->hasMany(ProductProperty::className(), ['product_id' => 'id']);
    }

    public function getSearchData()
    {
        return $this->hasMany(SearchData::className(), ['product_id' => 'id']);
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if (empty($this->time_cout_down) === false) {
                $this->time_cout_down = date("Y-m-d H:i:s",strtotime($this->time_cout_down));
            }
            return true;
        }
        return false;
    } 

    public function deleteSearchData()
    {
        SearchData::deleteAll(['id' => ArrayHelper::getColumn($this->searchData,'id')]);
        return;
    }

    public function getImageUrl()
    {
        return '@web/image/product/'.$this->code.'.jpg';
    }

    public function currentPrice()
    {
        if (empty($this->current_price) === false) {
            return '$'.number_format($this->current_price, 2);
        }else{
            return '$'.number_format($this->cost_price, 2);;
        }
    }

    public function similar()
    {
        $out = [];
        $list1 = self::find()
            ->where(
                [
                    'and',
                        [
                            '!=','id', $this->id
                        ],
                        [
                            'category_id' => $this->category_id
                        ],
                        [
                            '<','id',$this->id
                        ]
                ]
            )->orderBy(['id' => SORT_DESC])->limit(3)->all(); 

        $list2 = self::find()
            ->where(
                [
                    'and',
                        [
                            '!=','id', $this->id
                        ],
                        [
                            'category_id' => $this->category_id
                        ],
                        [
                            '>','id',$this->id
                        ]
                ]
            )->orderBy(['id' => SORT_ASC])->limit(3)->all(); 

        if (empty($list1) === false) {
            foreach ($list1 as $product) {
                $out[] = $product;
            }
        }
        if (empty($list2) === false) {
            foreach ($list2 as $product) {
                $out[] = $product;
            }
        }
        
        return $out;
    }

    public function seo()
    {
        Yii::$app->view->title = $this->title;
        
        Yii::$app->view->registerMetaTag(
            [
                'name' => 'description',
                'content' => 'Online shop fashion womens '.strtolower($this->category->title).'. '.$this->title.' at amazingly cheap prices.',
            ]
        );
        Yii::$app->view->registerMetaTag(
            [
                'name' => 'keywords',
                'content' => strtolower($this->title),
            ]
        );
        
        Yii::$app->view->registerMetaTag(
            [
                'name' => 'generator',
                'content' => 'ParsingWeb',
            ]
        );

        Yii::$app->view->registerMetaTag(
            [
                'name' => 'og:title',
                'content' => $this->title,
            ]
        );

        Yii::$app->view->registerMetaTag(
            [
                'name' => 'og:url',
                'content' => Url::to(['site/product','slug' => $this->slug],true),
            ]
        );

        Yii::$app->view->registerMetaTag(
            [
                'name' => 'og:type',
                'content' => 'website',
            ]
        );
        return;
    
    }
}
