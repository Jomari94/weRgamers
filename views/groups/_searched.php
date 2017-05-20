<?php
use yii\widgets\ListView;
?>
<h3><?= Yii::t('app', 'Groups searched: {0}', [$q]) ?></h3>
<br />
<?= ListView::widget([
    'dataProvider' => $groupProvider,
    'itemOptions' => [
        'class' => 'group-view',
        'tag' => 'article',
    ],
    'options' => [
        'tag' => 'div',
        'class' => 'groups-wrapper',
        'id' => 'groups-wrapper',
    ],
    'layout' => "{items}\n{pager}",
    'itemView' => '_view',
]) ?>
