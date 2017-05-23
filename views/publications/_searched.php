<?php
use yii\widgets\ListView;
?>
<br />
<?= ListView::widget([
   'dataProvider' => $publicationProvider,
   'layout' => "{items}\n{pager}",
   'options' => [
       'tag' => 'div',
       'class' => 'publications-wrapper',
       'id' => 'publications-wrapper',
   ],
   'layout' => "{items}\n{pager}",
   'itemView' => '../messages/_view.php',
   'itemOptions' => [
       'tag' => 'article',
       'class' => 'publication-view',
   ],
   'itemView' => '_view',
]) ?>
