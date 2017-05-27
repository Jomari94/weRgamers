<?php

use yii\helpers\Html;

$sender = $model->id_user == Yii::$app->user->id ? 'sender' : 'nosender';

?>
<div class="message-view <?= $sender ?>">
    <?= Html::img($model->user->profile->getAvatar(), ['class' => 'img64 img-circle'])?><p><?= $model->content ?></p>
</div>
