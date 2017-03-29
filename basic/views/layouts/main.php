<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Women Clothing',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            ['label' => 'Link', 'url' => ['/link/index'], 'visible' => !Yii::$app->user->isGuest],
            ['label' => 'Category', 'url' => ['/category/index'], 'visible' => !Yii::$app->user->isGuest],
            ['label' => 'Product', 'url' => ['/product/index'], 'visible' => !Yii::$app->user->isGuest],
            ['label' => 'Product Property', 'url' => ['/product-property/index'], 'visible' => !Yii::$app->user->isGuest],
            ['label' => 'Searh Data', 'url' => ['/search-data/index'], 'visible' => !Yii::$app->user->isGuest],
            Yii::$app->user->isGuest ? '' : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Women Clothing <?= date('Y') ?></p>

        <p class="pull-right powered"><?= Html::a(Html::img('@web/image/parsingweb.png', ['alt' => 'Parsing Web','class' => 'logo']),'http://parsingweb.ru')  ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
