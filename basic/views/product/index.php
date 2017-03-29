<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            [
                'attribute'=>'category_id',
                'label' => 'Category',
                'content'=>function($data){
                    return (empty($data->category) === false) ? $data->category->title : '(No set)';
                }
                
            ],
            //'source_url:url',
            'title',
            'star',
            'info_tips',
            'current_price',
            'time_cout_down',
            'cost_price',
            // 'image_url:url',
            'description:html',
            [
                'attribute'=>'created_at',
                'label' => 'Created',
                'content'=>function($data){
                    return date("d/m/Y H:i:s",$data->created_at);
                }
                
            ],
            [
                'attribute'=>'updated_at',
                'label' => 'Updated',
                'content'=>function($data){
                    return date("d/m/Y H:i:s",$data->updated_at);
                }
                
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
