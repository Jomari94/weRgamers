<?php

use yii\helpers\Html;

$sender = $model->sender->id == Yii::$app->user->id ? 'sender' : 'nosender';

?>
<div class="message-view <?= $sender ?>">
    <?= Html::img($model->sender->profile->getAvatar(), ['class' => 'img64 img-circle'])?><p><?= $model->content ?></p>
</div>
