<?php
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\widgets\LinkPager;
?>
<?php if (empty($products) === false): ?>
	<div class="row">
		<?php foreach ($products as $product): ?>
			<div class="col-sm-6 col-md-4 col-lg-3">
			    <div class="thumbnail">
			    	<?= Html::a( Html::img($product->imageUrl, ['alt' => $product->title,'class' => 'height-180']),['site/product','slug' => $product->slug],['role' => 'button','title' => $product->title]) ?>
			     	
			      	<div class="caption">
			        	<h3><?= StringHelper::truncate($product->title,18) ?></h3>
			        	<p>
			        		<?= Html::a($product->currentPrice(),['site/product','slug' => $product->slug],['class' => 'btn btn-primary btn-sm width-100','role' => 'button','title' => $product->title]) ?>
			        	</p>
			      </div>
			    </div>
			  </div>
		<?php endforeach ?>	
	</div>
	<?= LinkPager::widget([
	    	'pagination' => $pages,
		]);
	?>
<?php endif ?>