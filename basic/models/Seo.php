<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class Seo extends Model
{
    public function metaTagIndex()
    {
        Yii::$app->view->title = 'Mens Clothing';
        Yii::$app->view->registerMetaTag(
            [
                'name' => 'description',
                'content' => 'Online shop fashion mens activewear, cardigans, sweaters, hoodies, long sleeves, jackets, coat, vests, waistcoats from Menclothing.top at amazingly cheap prices.',
            ]
        );
        Yii::$app->view->registerMetaTag(
            [
                'name' => 'keywords',
                'content' => 'mens fashion clothes, clothes mens online, clothing activewear mens, mens jackets, mens clothing shop',
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
                'content' => 'Mens Clothing',
            ]
        );

        Yii::$app->view->registerMetaTag(
            [
                'name' => 'og:url',
                'content' => Url::home(true),
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
