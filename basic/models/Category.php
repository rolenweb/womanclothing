<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $title
 * @property integer $parent_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Category extends \yii\db\ActiveRecord
{
    public $structure;
    public $current;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'slug' => [
                'class' => 'app\components\Slug',
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
            [['parent_id', 'created_at', 'updated_at'], 'integer'],
            [['title','slug'], 'string', 'max' => 255],
            [['title'],'uniqueParent','on' => 'create']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'parent_id' => 'Parent ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function uniqueParent($attribute, $params)
    {
        $saved = self::find()
            ->where(
                [
                    'and',
                        [
                            'parent_id' => $this->parent_id
                        ],
                        [
                            $attribute => $this->$attribute
                        ]
                ]
            )
            ->limit(1)
            ->one();
        if (empty($saved) === false) {
            $this->addError($attribute, $attribute.' is taken.');
        }
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['title','parent_id'];
        return $scenarios;
    }

    public function getSubs()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
    }

    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }

    public function createStructure()
    {
        $this->structure = [];
        $list = self::find()->with(['subs.subs.subs.subs'])->where(['parent_id' => null])->orderBy(['title' => SORT_ASC])->all();
        foreach ($list as $n => $item) {
            $this->structure[$n]['title'] = $item->title;
            $this->structure[$n]['slug'] = $item->slug;
            if (empty($item->subs) === false) {
                foreach ($item->subs as $nSub => $sub) {
                    $this->structure[$n]['subs'][$nSub]['title'] = $sub->title;
                    $this->structure[$n]['subs'][$nSub]['slug'] = $sub->slug;
                    if (empty($sub->subs) === false) {
                        foreach ($sub->subs as $nSub2 => $sub2) {
                            $this->structure[$n]['subs'][$nSub]['subs'][$nSub2]['title'] = $sub2->title;
                            $this->structure[$n]['subs'][$nSub]['subs'][$nSub2]['slug'] = $sub2->slug;
                        }
                    }
                }
            }
        }
        
        ArrayHelper::multisort($this->structure[0]['subs'], ['title'], [SORT_ASC]);
        return $this;
    }

    public function getCurrent($slug)
    {
        $this->current = self::find()->with('parent.parent')->where(['slug' => $slug])->limit(1)->one();
    }


    public function seo()
    {
        Yii::$app->view->title = 'Women '.strtolower($this->current->title);
        
        Yii::$app->view->registerMetaTag(
            [
                'name' => 'description',
                'content' => 'Online shop fashion womens '.strtolower($this->current->title).' from Womanclothing.top at amazingly cheap prices.',
            ]
        );
        Yii::$app->view->registerMetaTag(
            [
                'name' => 'keywords',
                'content' => 'women '.strtolower($this->current->title).', '.strtolower($this->current->title).' women online',
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
                'content' => 'Women '.strtolower($this->current->title),
            ]
        );

        Yii::$app->view->registerMetaTag(
            [
                'name' => 'og:url',
                'content' => $this->currentUrl(),
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

    public function currentUrl()
    {
        if ($this->current->parent->slug === 'women') {
            return Url::to(['site/index','cat1' => $this->current->slug],true);
        }else{
            return Url::to(['site/index','cat1' => $this->current->parent->slug,'cat2' => $this->current->slug],true);
        }
    }
}
