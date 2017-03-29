<?php
use yii\helpers\Html;

$gold = round($product->star);
$balck = 5-$gold;

for ($i=0; $i < $gold; $i++) { 
	echo Html::tag('span','',['class' => 'glyphicon glyphicon-star gold']);
}
for ($i=0; $i < $balck; $i++) { 
	echo Html::tag('span','',['class' => 'glyphicon glyphicon-star']);
}

?>

