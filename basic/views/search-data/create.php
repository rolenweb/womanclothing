<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SearchData */

$this->title = 'Create Search Data';
$this->params['breadcrumbs'][] = ['label' => 'Search Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="search-data-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
