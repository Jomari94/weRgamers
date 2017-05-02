<?php

use yii\helpers\Html;

$last = $model->last;
?>
<div class="conversation-view">
    <?=Html::a(Html::img($model->receiver->profile->getAvatar(), ['class' => 'img-rounded img64']), ['view', 'id' => $model->id]); ?>
    <div>
        <p><?= Html::a($model->receiver->username, ['view', 'id' => $model->id]) ?></p>
        <p><?= $last->sender->username ?>: <?= Html::encode($last->content) ?>  - <?= Yii::$app->formatter->asDatetime($last->created) ?></p>
    </div>
</div>
