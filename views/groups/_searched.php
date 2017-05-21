<?php
use yii\widgets\ListView;
?>
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
