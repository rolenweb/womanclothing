<?php

/* @var $this yii\web\View */


if (empty($category->current) === false) {
    //$this->title = $category->current->title;
    if (empty($category->current->parent) === false) {
        if ($category->current->parent->slug !== 'men') {
            $this->params['breadcrumbs'][] = ['label' => $category->current->parent->title, 'url' => ['site/index','cat1' => $category->current->parent->slug]];
        }
    }
    $this->params['breadcrumbs'][] = $this->title;
    
}else{
    //$this->title = 'Woman clothing';
}
?>
<div class="womanclothing-index">
    <div class="body-content">
        <div class="row">
            <div class="col-sm-3">
                <?= $this->render('_category',['category' => $category,'params' => $params]) ?>
            </div>
            <div class="col-sm-9">
                <?= $this->render('_product_list',['products' => $products,'params' => $params,'pages' => $pages,]) ?>
            </div>
        </div>
    </div>
</div>
