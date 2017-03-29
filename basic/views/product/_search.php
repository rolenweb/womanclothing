<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'category_id') ?>

    <?= $form->field($model, 'source_url') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'star') ?>

    <?php // echo $form->field($model, 'code') ?>

    <?php // echo $form->field($model, 'info_tips') ?>

    <?php // echo $form->field($model, 'current_price') ?>

    <?php // echo $form->field($model, 'time_cout_down') ?>

    <?php // echo $form->field($model, 'cost_price') ?>

    <?php // echo $form->field($model, 'image_url') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
