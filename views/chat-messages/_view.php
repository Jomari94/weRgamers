<?php

use yii\helpers\Html;

$sender = $model->id_user == Yii::$app->user->id ? 'sender' : 'nosender';

?>
<div class="chat-message-view <?= $sender ?>">
    <?= Html::img($model->user->profile->getAvatar(), ['class' => 'img64 img-circle'])?>
    <div class="chat-message-content">
        <?php if ($sender == 'nosender'): ?>
            <p><?= $model->user->username ?></p>
        <?php endif; ?>
        <p><?= $model->content ?></p>
    </div>
</div>
