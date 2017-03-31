<?php
use yii\helpers\Html;
?>
  <div class="category">
    <ul class="list-group">
      <?php if (empty($category->structure[0]['subs']) === false): ?>
        <?php foreach ($category->structure[0]['subs'] as $item): ?>
          <?= Html::a($item['title'].Html::tag('span',Html::tag('span','',['class' => 'glyphicon glyphicon-arrow-right']),['class' => 'badge']),['site/index','cat1' => $item['slug']],['class' => 'list-group-item','title' => $item['title']]) ?>
          <?php if ($params['cat1'] === $item['slug']): ?>
            <?php if (empty($item['subs']) === false): ?>
              <?php foreach ($item['subs'] as $sub1): ?>
                <?= Html::a($sub1['title'].Html::tag('span',Html::tag('span','',['class' => 'glyphicon glyphicon-arrow-right']),['class' => 'badge']),['site/index', 'cat1' => $item['slug'],'cat2' => $sub1['slug']],['class' => 'list-group-item list-group-item-success','title' => $sub1['title']]) ?>
                  <?php if ($params['cat2'] === $item['slug']): ?>
                    <?php if (empty($sub1['subs']) === false): ?>
                      <?php foreach ($sub1['subs'] as $sub2): ?>
                        <?= Html::a($sub2['title'].Html::tag('span',Html::tag('span','',['class' => 'glyphicon glyphicon-arrow-right']),['class' => 'badge']),['site/index','cat1' => $item['slug'],'cat2' => $sub1['slug'],'cat3' => $sub2['slug']],['class' => 'list-group-item list-group-item-info','title' => $sub2['title']]) ?>
                      <?php endforeach ?>
                    <?php endif ?>
                  <?php endif ?>
              <?php endforeach ?>
            <?php endif ?>
          <?php endif ?>
        <?php endforeach ?>  
      <?php endif ?>
      
    </ul>
  </div>