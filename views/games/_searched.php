<?php
use yii\widgets\ListView;
?>
<h3><?= Yii::t('app', 'Games searched: {0}', [$q]) ?></h3>
<br />
<?= ListView::widget([
    'dataProvider' => $gameProvider,
    'itemOptions' => [
        'class' => 'user-view media',
        'tag' => 'article',
    ],
    'options' => [
        'tag' => 'div',
        'class' => 'users-wrapper',
        'id' => 'users-wrapper',
    ],
    'layout' => "{items}\n{pager}",
    'itemView' => '_view',
]) ?>
