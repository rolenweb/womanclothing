<?php
use yii\helpers\Html;
use yii\helpers\StringHelper;

if (empty($product->category->parent) === false) {
	if ($product->category->parent->slug !== 'men') {
		$this->params['breadcrumbs'][] = ['label' => $product->category->parent->title, 'url' => ['site/index','cat1' => $product->category->parent->slug]];
		$this->params['breadcrumbs'][] = ['label' => $product->category->title, 'url' => ['site/index','cat1' => $product->category->parent->slug,'cat2' => $product->category->slug]];
	}else{
		$this->params['breadcrumbs'][] = ['label' => $product->category->title, 'url' => ['site/index','cat1' => $product->category->slug]];
	}
}	

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="womanclothing-product">
    <div class="body-content">
        <div class="row">
            <div class="col-sm-4">
            	<div class="thumbnail">
            		<?= Html::img($product->imageUrl, ['alt' => $product->title]) ?>	
            	</div>
            </div>
            <div class="col-sm-8 product-property">
               <h1><?= $this->title ?></h1>

               <div class="star">
               		<?= $this->render('_star',['product' => $product]) ?>
               </div>
               <div class="price">
					<span class="current">
						$<?= $product->current_price ?>
					</span>
					<span class="cost">
						<sup>$<?= $product->cost_price ?></sup>
						
					</span>
               </div>
               <div class="size">
               		<?php if (empty($product->properties) === false): ?>
               			<?php foreach ($product->properties as $property): ?>
               				<?php if ($property->title === 'size'): ?>
               					<span class="badge">
	               					<?= $property->value ?>
	               				</span>	
               				<?php endif ?>
               				
               			<?php endforeach ?>
               		<?php endif ?>
               </div>
               <div class="description">
               		<?= $product->description ?>
               </div>
               <div class="buy text-right">
               		<?= Html::a('Buy',$product->source_url,['class' => 'btn btn-success btn-sm width-100','target' => '_blank','rel' => 'nofollow']) ?>
               </div>
            </div>
        </div>
        <div class="row similar">
        	<div class="col-sm-12">
        		<h2>Products similar to <?= $this->title ?></h2>
        	</div>

        	<?php if (empty($similars) === false): ?>
        		<?php foreach ($similars as $similar): ?>
        			<div class="col-sm-6 col-md-4 col-lg-2">
					    <div class="thumbnail">
					    	<?= Html::a(Html::img($similar->imageUrl, ['alt' => $similar->title]),['site/product','slug' => $similar->slug],['role' => 'button','title' => $similar->title]) ?>
					     	
					      	<div class="caption">
					        	<h3><?= StringHelper::truncate($similar->title,10) ?></h3>
					        	<p>
					        		<?= Html::a($similar->currentPrice(),['site/product','slug' => $similar->slug],['class' => 'btn btn-primary btn-sm width-100','role' => 'button','title' => $similar->title]) ?>
					        	</p>
					      </div>
					    </div>
					  </div>
        		<?php endforeach ?>
        	<?php endif ?>
        </div>
        <?php if (empty($searchData) === false): ?>
        	<hr>
        	<div class="row search-data">
        		<div class="col-sm-12">
        			<h2>Relative data to <?= $this->title ?></h2>
        		</div>
	        	<?php foreach ($searchData as $item): ?>
	        		<div class="col-sm-12">
	        			<div class="single">
	        				<h3><?= $item->title ?></h3>
		        			<p>
		        				<?= $item->snippet ?>
		        			</p>
		        			<p class="text-right">
		        				<?= Html::a('More',$item->url,['class' => 'btn btn-default btn-xs','target' => '_blank','rel' => 'nofollow']) ?>
		        			</p>	
	        			</div>
	        		</div>
	        	<?php endforeach ?>
	        </div>	
        <?php endif ?>
    </div>
</div>
