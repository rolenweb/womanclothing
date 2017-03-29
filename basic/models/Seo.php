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
        Yii::$app->view->title = 'Women Clothing';
        Yii::$app->view->registerMetaTag(
            [
                'name' => 'description',
                'content' => 'Online shop fashion womens dresses, activewear, bottoms, dance costumes, denim, lingerie, jumpsuits, rompers from Womanclothing.top at amazingly cheap prices.',
            ]
        );
        Yii::$app->view->registerMetaTag(
            [
                'name' => 'keywords',
                'content' => 'women fashion clothes, clothes women online, clothing dresses women, women clothes plus size, women clothing shop',
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
                'content' => 'Women Clothing',
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
